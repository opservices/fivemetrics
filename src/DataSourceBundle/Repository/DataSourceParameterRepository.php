<?php

namespace DataSourceBundle\Repository;

use Doctrine\ORM\EntityRepository;
use EssentialsBundle\Entity\Account\Account;

class DataSourceParameterRepository extends EntityRepository
{
    public function findByAccount(Account $account)
    {
        $dql = "
            SELECT p, pv FROM DataSourceBundle:DataSource\DataSourceParameter as p
            JOIN p.dataSourceParameterValues AS pv
            WHERE pv.account = :account
        ";

        $query = $this->getEntityManager()
            ->createQuery($dql);

        $query->setParameter(':account', $account);

        return $query->getResult();
    }
}
