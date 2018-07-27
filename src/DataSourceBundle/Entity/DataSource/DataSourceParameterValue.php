<?php

namespace DataSourceBundle\Entity\DataSource;

use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\EntityAbstract;
use Doctrine\ORM\Mapping as ORM;
use EssentialsBundle\Exception\Exceptions;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="data_source_parameter_values",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="data_source_parameter_values_configuration",
 *              columns={"account_id", "data_source_id", "collect_id", "parameter_id"}
 *          )
 *     }
 * )
 * @UniqueEntity(fields={"account", "dataSource", "collect", "parameter"}, message="")
 */
class DataSourceParameterValue extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSource",
     *     inversedBy="dataSourceParameterValues"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $dataSource;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="EssentialsBundle\Entity\Account\Account",
     *     inversedBy="dataSourceParameterValues"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $account;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSourceParameter",
     *     inversedBy="dataSourceParameterValues"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $parameter;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $value;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSourceCollect",
     *     inversedBy="parameterValues"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $collect;

    /**
     * DataSourceParameterValue constructor.
     * @param DataSource|null $dataSource
     * @param Account|null $account
     * @param DataSourceParameter|null $parameter
     * @param DataSourceCollect|null $collect
     * @param mixed $value
     */
    public function __construct(
        DataSource $dataSource = null,
        Account $account = null,
        DataSourceParameter $parameter = null,
        DataSourceCollect $collect = null,
        $value = null
    ) {
        (is_null($dataSource)) ?: $this->setDataSource($dataSource);
        (is_null($account)) ?: $this->setAccount($account);
        (is_null($parameter)) ?: $this->setParameter($parameter);
        (is_null($value)) ?: $this->setValue($value);
        (is_null($collect)) ?: $this->setCollect($collect);
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
     * @return DataSourceParameterValue
     */
    public function setDataSource(DataSource $dataSource): DataSourceParameterValue
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param Account $account
     * @return DataSourceParameterValue
     */
    public function setAccount($account): DataSourceParameterValue
    {
        $this->account = $account;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return json_decode($this->value, true);
    }

    /**
     * @param $value
     * @return DataSourceParameterValue
     */
    public function setValue($value): DataSourceParameterValue
    {
        $this->value = json_encode($value);
        return $this;
    }

    /**
     * @return DataSourceParameter
     */
    public function getParameter(): DataSourceParameter
    {
        return $this->parameter;
    }

    /**
     * @param DataSourceParameter $parameter
     * @return DataSourceParameterValue
     */
    public function setParameter(DataSourceParameter $parameter): DataSourceParameterValue
    {
        $this->parameter = $parameter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCollect()
    {
        return $this->collect;
    }

    /**
     * @param DataSourceCollect $collect
     * @return DataSourceParameterValue
     */
    public function setCollect(DataSourceCollect $collect): DataSourceParameterValue
    {
        $this->collect = $collect;
        return $this;
    }

    public function toArray()
    {
        return [
            'value' => $this->getValue(),
        ];
    }
}
