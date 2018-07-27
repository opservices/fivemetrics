<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 18/09/17
 * Time: 14:29
 */

namespace DataSourceBundle\Entity\DataSource;

use Doctrine\ORM\Mapping as ORM;
use EssentialsBundle\Entity\EntityAbstract;
use EssentialsBundle\Exception\Exceptions;

/**
 * @ORM\Entity
 * @ORM\Table(name="data_source_configuration")
 */
class DataSourceConfiguration extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToOne(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSource",
     *     mappedBy="dataSourceConfiguration"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $dataSource;

    /**
     * @ORM\Column(type="integer")
     */
    protected $collectInterval;

    /**
     * @ORM\Column(type="integer")
     */
    protected $maxConcurrency;

    /**
     * DataSourceConfiguration constructor.
     * @param DataSource|null $ds
     * @param int|null $collectInterval
     */
    public function __construct(
        DataSource $ds = null,
        int $collectInterval = null,
        int $maxConcurrency = null
    ) {
        (is_null($ds)) ?: $this->setDataSource($ds);
        (is_null($collectInterval)) ?: $this->setCollectInterval($collectInterval);
        (is_null($maxConcurrency)) ?: $this->setMaxConcurrency($maxConcurrency);
    }

    /**
     * @return mixed
     */
    public function getMaxConcurrency()
    {
        return $this->maxConcurrency;
    }

    /**
     * @param mixed $maxConcurrency
     * @return DataSourceConfiguration
     */
    public function setMaxConcurrency($maxConcurrency): DataSourceConfiguration
    {
        $this->maxConcurrency = $maxConcurrency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @param DataSource $dataSource
     * @return DataSourceConfiguration
     */
    public function setDataSource(DataSource $dataSource): DataSourceConfiguration
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCollectInterval()
    {
        return $this->collectInterval;
    }

    /**
     * @param int $collectInterval
     * @return DataSourceConfiguration
     */
    public function setCollectInterval(int $collectInterval): DataSourceConfiguration
    {
        if ((! is_int($collectInterval)) || ($collectInterval <= 0)) {
            throw new \InvalidArgumentException(
                'The collect interval must be an integer greater than zero.',
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->collectInterval = $collectInterval;
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'collectInterval' => $this->getCollectInterval(),
            'maxConcurrency' => $this->getMaxConcurrency(),
        ];
    }
}
