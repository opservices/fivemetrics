<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/09/17
 * Time: 16:15
 */

namespace DataSourceBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DataSourceCollectRepository extends EntityRepository
{
    public function findByInterval(
        int $collectInterval = null,
        bool $isEnabled = null
    ) {
        $conditions = [];
        (is_null($collectInterval)) ?: $conditions[] = '(conf.collectInterval = :collectInterval)';
        (is_null($isEnabled)) ?: $conditions[] = '(collect.isEnabled = :isEnabled)';

        $dql = sprintf(
            'SELECT collect
                  FROM DataSourceBundle:DataSource\DataSourceCollect AS collect
                  LEFT JOIN collect.dataSource AS ds
                  LEFT JOIN ds.dataSourceConfiguration AS conf
                  %s',
            (empty($conditions)) ? '' : 'WHERE ' . implode(' AND ', $conditions)
        );

        $query = $this->getEntityManager()
            ->createQuery($dql);

        if (! is_null($isEnabled)) {
            $query->setParameter(':isEnabled', $isEnabled);
        }

        if (! is_null($collectInterval)) {
            $query->setParameter(':collectInterval', $collectInterval);
        }

        return $query->getResult();
    }
}
