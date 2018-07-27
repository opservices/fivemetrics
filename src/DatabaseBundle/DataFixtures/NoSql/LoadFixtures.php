<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/07/17
 * Time: 13:56
 */

namespace DatabaseBundle\DataFixtures\NoSql;

use DatabaseBundle\DataFixtures\NoSql\Configuration\Builder;
use DatabaseBundle\Gearman\Queue\CollectResult\Job;
use DatabaseBundle\NoSql\DatabaseConnectionProvider;
use DatabaseBundle\NoSql\Metric\MetricRepository;
use Doctrine\ORM\EntityManagerInterface;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use GearmanBundle\TaskManager\TaskManager;

/**
 * Class LoadFixtures
 * @package DatabaseBundle\DataFixtures\NoSql
 */
class LoadFixtures
{
    const FIXTURES_CONF = __DIR__ . '/fixtures.json';

    /**
     * @param EntityManagerInterface $manager
     * @param DatabaseConnectionProvider $connectionProvider
     * @param MetricRepository $metricRepository
     * @param string $conf
     */
    public static function load(
        EntityManagerInterface $manager,
        DatabaseConnectionProvider $connectionProvider,
        TaskManager $taskManager,
        string $conf = self::FIXTURES_CONF
    ) {
        $conf = json_decode(file_get_contents($conf), true);
        $series = Builder::factory($conf);

        $metrics  = FixturesGenerator::generateSeries($series);
        $repo = $manager->getRepository(Account::class);

        $accounts = $repo->findAll();
        $users = (is_array($conf['users'])) ? $conf['users'] : [];

        $connection = $connectionProvider->getConnection();
        $time = new DateTime();

        foreach ($accounts as $account) {
            $db = $connection->getClient()->selectDB($account->getUid());

            $db->drop();
            $db->create();

            if (! in_array($account->getEmail(), $users)) {
                continue;
            }

            foreach ($metrics as $metric) {
                $taskManager->runBackground(
                    'active-collect-result',
                    serialize(new Job(
                        $account,
                        1,
                        $time,
                        new MetricCollection([$metric])
                    ))
                );
            }
        }
    }
}
