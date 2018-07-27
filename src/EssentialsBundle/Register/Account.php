<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/01/18
 * Time: 15:20
 */

namespace EssentialsBundle\Register;

use EssentialsBundle\Entity\Account\Account as AccountEntity;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use InfluxDB\Database\RetentionPolicy;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Account
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Account constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function registerFromApiParameters(
        array $parameters,
        array $validationGroups = [ 'Default', 'Registration' ]
    ): AccountEntity {
        /** @var AccountEntity $account */
        $account = EntityBuilderProvider::factory(AccountEntity::class)
            ->factory($parameters, $validationGroups);

        return $this->register($account);
    }

    public function register(AccountEntity $account): AccountEntity
    {
        $em = $this->container->get('doctrine')->getManager();
        $em->persist($account);
        $em->flush();

        $client = $this->container->get('nosql.database.connection.provider')
            ->getConnection()
            ->getClient();

        $db = $client->selectDB($account->getUid());
        $db->create();

        $db->alterRetentionPolicy(new RetentionPolicy(
            'autogen',
            '365d',
            '1',
            true
        ));

        return $account;
    }
}
