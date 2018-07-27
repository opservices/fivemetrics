<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/07/17
 * Time: 16:33
 */

namespace DatabaseBundle\Command;

use DatabaseBundle\NoSql\DatabaseConnectionProvider;
use DatabaseBundle\NoSql\Metric\MetricRepository;
use EssentialsBundle\Entity\Account\Account;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use DatabaseBundle\DataFixtures\NoSql\LoadFixtures;
use DatabaseBundle\DataFixtures\NoSql\Configuration\Builder;
use DatabaseBundle\DataFixtures\NoSql\FixturesGenerator;

/**
 * Class DatabaseFixturesCommand
 * @package DatabaseBundle\Command
 */
class NoSqlFixturesCollectCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:nosql:fixtures:collect')
            ->setDescription('Generate a collect using the data fixtures configuration.')
            ->setHelp('This command allows you to keep the NoSql data updated...');

        $this->addOption(
            'user-uid',
            'u',
            InputOption::VALUE_REQUIRED,
            'The user uid that will be used as database.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $output->write('Generating data fixtures collect...');

            if ($input->getOption('user-uid')) {
                $accounts = $this->getContainer()
                    ->get('doctrine.orm.entity_manager')
                    ->getRepository(Account::class)
                    ->findBy([ 'uid' => $input->getOption('user-uid') ]);
            } else {
                $accounts = $this->getContainer()
                    ->get('doctrine.orm.entity_manager')
                    ->getRepository(Account::class)->findAll();
            }

            $metricRepository   = new MetricRepository();
            $connectionProvider = new DatabaseConnectionProvider();
            $connection = $connectionProvider->getConnection();

            $conf = json_decode(
                file_get_contents(LoadFixtures::FIXTURES_CONF),
                true
            );
            $conf = Builder::factory($conf);

            $metrics  = FixturesGenerator::generateSeries($conf, 1);

            foreach ($accounts as $account) {
                /** @var Account $account */
                $db = $connection->getClient()->selectDB($account->getUid());

                ($db->exists()) ?: $db->create();

                $metricRepository->putMetrics($account->getUid(), $metrics);
            }

            $output->writeln(' <fg=green>done</>');
        } catch (\Throwable $e) {
            $output->writeln(PHP_EOL);
            $output->writeln('<error>Failed to load NoSql data fixtures.</error>');
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
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
