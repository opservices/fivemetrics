<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 13/09/17
 * Time: 15:43
 */

namespace DataSourceBundle\Api\V1\DataSourceCollect;

use DataSourceBundle\Entity\DataSource\DataSource;
use DataSourceBundle\Entity\DataSource\DataSourceParameter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Account\AccountConfiguration;
use EssentialsBundle\Exception\Exceptions;
use EssentialsBundle\KernelLoader;

class ParametersResolver
{
    protected $accountConfs = [];

    public function process(
        Account $account,
        DataSource $ds,
        array $parameters
    ): array {
        $em = $this->getEntityManagerInstance();
        $accountConfs = $this->retrieveAccountConfigurations($em, $account);
        $parameters = RequestDataSourceParameterBuilder::factory($parameters);

        /** @var ArrayCollection $dsParams */
        $dsParams = $ds->getParameters();
        $resolvedParams = [];
        foreach ($dsParams as $dsParam) {
            /** @var DataSourceParameter $dsParam */
            $name = $dsParam->getName();
            $param = $parameters->find($name);

            $resolvedParams[$name] = null;
            if ($param) {
                $resolvedParams[$name] = $param->getValue();
            } elseif (isset($accountConfs[$name])) {
                $resolvedParams[$name] = $accountConfs[$name];
            }

            if (is_null($resolvedParams[$name])) {
                throw new \InvalidArgumentException(
                    "The parameter " . $name . " was not found to data source " . $ds->getName() . ".",
                    Exceptions::VALIDATION_ERROR
                );
            }
        }

        return $resolvedParams;
    }

    protected function retrieveAccountConfigurations(
        ObjectManager $em,
        Account $account
    ): array {
        if (isset($this->accountConfs[$account->getUid()])) {
            return $this->accountConfs[$account->getUid()];
        }

        $confRepo = $em->getRepository(AccountConfiguration::class);
        $accountRepo = $em->getRepository(Account::class);

        $accountConfigurations = $confRepo->findBy([
            'account' => $accountRepo->findOneBy([
                'email' => $account->getEmail(),
            ])
        ]);

        foreach ($accountConfigurations as $conf) {
            /** @var AccountConfiguration $accountConf */
            $this->accountConfs[$account->getUid()][$conf->getName()] = $conf->getValue();
        }

        if (is_null($this->accountConfs[$account->getUid()])) {
            $this->accountConfs[$account->getUid()] = [];
        }

        return $this->accountConfs[$account->getUid()];
    }

    protected function getEntityManagerInstance(): ObjectManager
    {
        return KernelLoader::load()
            ->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}
