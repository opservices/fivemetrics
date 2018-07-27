<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/07/17
 * Time: 16:33
 */

namespace DatabaseBundle\Command;

use DatabaseBundle\Command\NoSqlImporter\NoSqlDatabase;
use DatabaseBundle\Command\NoSqlImporter\NoSqlDatabaseCollection;
use DatabaseBundle\Command\NoSqlImporter\NoSqlMeasurement;
use DatabaseBundle\NoSql\Metric\MetricRepository;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Metric\Metric;
use EssentialsBundle\Entity\Metric\Point;
use EssentialsBundle\Entity\TimePeriod\TimePeriodInterface;
use InfluxDB\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Class DatabaseFixturesCommand
 * @package DatabaseBundle\Command
 */
class NoSqlImporterCommand extends ContainerAwareCommand
{
    use LockableTrait;

    /**
     * @var Client
     */
    protected $remoteClient = null;

    /**
     * @var NoSqlDatabaseCollection
     */
    protected $remoteNoSqlDbs;

    protected function connect(
        string $host,
        string $user = '',
        string $pass = ''
    ) {
        $this->remoteClient = new Client(
            $host,
            8086,
            $user,
            $pass,
            false,
            false,
            10
        );

        return $this->remoteClient;
    }

    protected function initialize(
        InputInterface $input,
        OutputInterface $output
    ) {
        $msg = null;

        ($this->lock()) || $msg = 'The command is already running in another process.';
        (!empty($input->getOption('host'))) || $msg = "There's no host defined to connect.";

        if (is_null($msg)) {
            $this->remoteNoSqlDbs = new NoSqlDatabaseCollection();
            return 0;
        }

        $output->writeln("<error>$msg</error>");
        exit(1);
    }

    protected function configure()
    {
        $this->addOption(
            'host',
            'H',
            InputOption::VALUE_REQUIRED,
            'InfluxDB IP address.'
        );

        $this->addOption(
            'username',
            'u',
            InputOption::VALUE_REQUIRED,
            'Authentication username.'
        );

        $this->addOption(
            'password',
            'p',
            InputOption::VALUE_REQUIRED,
            'Authentication password.'
        );

        $this->setName('app:nosql:importer')
            ->setDescription('It imports the data from an external NoSql to the project NoSql.')
            ->setHelp('This command allows you to import NoSql data...');
    }

    protected function arrayFilter(array $arr, array $removeArr)
    {
        return array_filter($arr, function ($db) use ($removeArr) {
            return (!in_array($db, $removeArr));
        });
    }

    protected function getNoSqlDatabaseInstance(string $dbName): NoSqlDatabase
    {
        if (!$this->remoteNoSqlDbs->find($dbName)) {
            $this->remoteNoSqlDbs->add(new NoSqlDatabase(
                $dbName,
                $this->remoteClient->selectDB($dbName)
            ));
        };

        return $this->remoteNoSqlDbs->find($dbName);
    }

    protected function loadDatabasesInfo(Client $client)
    {
        $dbNames = $this->arrayFilter($client->listDatabases(), ['_internal']);

        foreach ($dbNames as $dbName) {
            $db = $this->getNoSqlDatabaseInstance($dbName);

            $arr = $db->getInfluxDb()
                ->query('SHOW MEASUREMENTS')
                ->getPoints();

            foreach ($arr as $measurement) {
                $db->getMeasurements()
                    ->add(new NoSqlMeasurement($measurement['name']));
            }
        }

        return $this;
    }

    protected function showResume(
        OutputInterface $output,
        array $header,
        array $data
    ) {
        $table = new Table($output);

        $table->setHeaders($header)
            ->setRows($data)
            ->setStyle('borderless')
            ->render();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $output->writeln(' <fg=green>*</> Starting data importer...' . PHP_EOL);

            $client = $this->connect(
                $input->getOption('host'),
                (string)$input->getOption('username'),
                (string)$input->getOption('password')
            );
            $this->loadDatabasesInfo($client);

            $timePeriod = $this->getContainer()
                ->get('timeperiods')
                ->factory('lastMinute');

            $metricsRepo = $this->getContainer()
                ->get('nosql.metric.repository');

            $sleepTime = 60;
            $sleepMsg = sprintf(
                "\n > Waiting %s seconds before import again...\n",
                $sleepTime
            );

            while (true) {
                $timePeriod->update();

                $msg = sprintf(
                    '> Importing data since %s.',
                    $timePeriod->getStart(TimePeriodInterface::RELATIONAL_FORMAT)
                );

                $output->writeln($msg);
                $this->importEntries($output, $timePeriod, $metricsRepo);
                $output->writeln($sleepMsg);

                sleep($sleepTime);
            }

            $output->writeln('');
            $output->writeln(' <fg=green>done</>');
            $this->release();
        } catch (\Throwable $e) {
            $output->writeln(PHP_EOL);
            $output->writeln('<error>Failed to import NoSql data.</error>');
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    protected function importEntries(
        OutputInterface $output,
        TimePeriodInterface $timePeriod,
        MetricRepository $metricsRepo
    ): NoSqlImporterCommand {
        $resume  = [];
        $headers = ['Database name', 'Measurement name', 'Imported entries'];

        $genericDbClient = $this->getContainer()
            ->get('nosql.database.connection.provider')
            ->getConnection();

        foreach ($this->remoteNoSqlDbs as $db) {
            /** @var NoSqlDatabase $db */
            $measurements = $db->getMeasurements();
            $localDb  = $genericDbClient->getClient()
                ->selectDB($db->getName());

            ($localDb->exists()) || $localDb->create();

            unset($localDb);

            foreach ($measurements as $measurement) {
                /** @var NoSqlMeasurement $measurement */
                $metricCollection = $this->retrieveMeasurementEntries(
                    $metricsRepo,
                    $db,
                    $measurement,
                    $timePeriod
                );

                $resume[] = [
                    $db->getName(),
                    $measurement->getName(),
                    count($metricCollection)
                ];

                $metricsRepo->putMetrics($db->getName(), $metricCollection);

                $metricCollection->clear();
                unset($metricCollection);
            }

            $this->showResume($output, $headers, $resume);
            $resume = [];
        }

        return $this;
    }

    /**
     * @param NoSqlMeasurement $measurement
     * @param Metric $metric
     * @return NoSqlImporterCommand
     */
    protected function updateMeasurementTime(
        NoSqlMeasurement $measurement,
        Metric $metric
    ): NoSqlImporterCommand {

        /** @var Point $point */
        $point = $metric->getPoints()->first();

        $time = $point->getTime()
            ->format(TimePeriodInterface::NO_SQL_FORMAT);

        $measurement->setTime($time);

        return $this;
    }

    /**
     * @param MetricRepository $metricsRepo
     * @param NoSqlDatabase $db
     * @param TimePeriodInterface $timePeriod
     * @return MetricCollection
     */
    protected function retrieveMeasurementEntries(
        MetricRepository $metricsRepo,
        NoSqlDatabase $db,
        NoSqlMeasurement $measurement,
        TimePeriodInterface $timePeriod
    ): MetricCollection {
        $query = $this->queryBuilder($measurement, $timePeriod);
        $points = $db->getInfluxDb()->query($query)->getPoints();

        if (empty($points)) {
            return new MetricCollection();
        }

        $metricCollection = $metricsRepo->influxPointsToMetrics(
            $points,
            $measurement->getName()
        );

        $this->updateMeasurementTime($measurement, $metricCollection->first());

        return $metricCollection;
    }

    protected function queryBuilder(
        NoSqlMeasurement $measurement,
        TimePeriodInterface $timePeriod
    ): string {
        $time = ($measurement->getTime() > 0)
            ? $measurement->getTime()
            : $timePeriod->getStart(TimePeriodInterface::NO_SQL_FORMAT);

        return sprintf(
            'SELECT * FROM "%s" WHERE time > %s ORDER BY time DESC',
            $measurement->getName(),
            $time
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param string $message
     * @param bool $default
     * @return bool
     */
    protected function confirm(
        InputInterface $input,
        OutputInterface $output,
        string $message,
        bool $default = false
    ): bool {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            $message,
            $default
        );

        return $helper->ask($input, $output, $question);
    }
}
