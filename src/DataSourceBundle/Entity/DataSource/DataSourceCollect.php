<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/09/17
 * Time: 10:25
 */

namespace DataSourceBundle\Entity\DataSource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\EntityAbstract;

/**
 * @ORM\Entity(repositoryClass="DataSourceBundle\Repository\DataSourceCollectRepository")
 * @ORM\Table(name="data_source_collect", indexes={
 *     @ORM\Index(name="interval_idx", columns={"last_update"}),
 *     @ORM\Index(name="uid_idx", columns={"uid"})
 * })
 */
class DataSourceCollect extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id = null;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSource",
     *     inversedBy="collects"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $dataSource;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="EssentialsBundle\Entity\Account\Account",
     *     inversedBy="collects"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $account;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSourceParameterValue",
     *     mappedBy="collect"
     * )
     */
    protected $parameterValues;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isEnabled = true;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastUpdate = null;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    protected $uid = '';

    /**
     * DataSourceCollect constructor.
     * @param Account|null $account
     * @param DataSource|null $dataSource
     */
    public function __construct(
        Account $account = null,
        DataSource $dataSource = null,
        string $uid = null
    ) {
        $this->parameterValues = new ArrayCollection();
        (is_null($account)) ?: $this->setAccount($account);
        (is_null($dataSource)) ?: $this->setDataSource($dataSource);
        (is_null($uid)) ?: $this->setUid($uid);
    }

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     * @return DataSourceCollect
     */
    public function setUid(string $uid): DataSourceCollect
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @param DateTime $lastUpdate
     * @return DataSourceCollect
     */
    public function setLastUpdate(DateTime $lastUpdate): DataSourceCollect
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     * @return DataSourceCollect
     */
    public function setIsEnabled(bool $isEnabled): DataSourceCollect
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    /**
     * @return DataSource
     */
    public function getDataSource(): DataSource
    {
        return $this->dataSource;
    }

    /**
     * @param DataSource $ds
     * @return DataSourceCollect
     */
    public function setDataSource(DataSource $ds): DataSourceCollect
    {
        $this->dataSource = $ds;
        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     * @return DataSourceCollect
     */
    public function setAccount(Account $account): DataSourceCollect
    {
        $this->account = $account;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getParameterValues()
    {
        return $this->parameterValues;
    }

    /**
     * @param $values
     * @return DataSourceCollect
     */
    public function setParameterValues(ArrayCollection $values): DataSourceCollect
    {
        $this->parameterValues = $values;
        return $this;
    }

    /**
     * @return array
     */
    public function getParametersAsArray(): array
    {
        return array_map(function (DataSourceParameterValue $el) {
            return [
                'name' => $el->getParameter()->getName(),
                'value' => $el->getValue(),
            ];
        }, $this->getParameterValues()->toArray());
    }

    public function toArray()
    {
        $ret = [
            'id' => $this->getId(),
            'uid' => $this->getUid(),
            'lastUpdate' => $this->getLastUpdate(),
            'account' => $this->getAccount()->toArray(),
            'dataSource' => $this->getDataSource()->toArray(),
            'parameters' => $this->getParametersAsArray(),
        ];

        return $ret;
    }
}
