<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 15:30
 */

namespace DataSourceBundle\Entity\Aws\ElasticLoadBalancer;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class BackendServerDescription
 * @package DataSourceBundle\Entity\Aws\ElasticLoadBalancer
 */
class BackendServerDescription extends EntityAbstract
{
    /**
     * @var int
     */
    protected $instancePort;

    /**
     * @var array
     */
    protected $policyNames;

    /**
     * BackendServerDescription constructor.
     * @param int $instancePort
     * @param array $policyNames
     */
    public function __construct(
        int $instancePort,
        array $policyNames
    ) {
        $this->setInstancePort($instancePort)
            ->setPolicyNames($policyNames);
    }

    /**
     * @return int
     */
    public function getInstancePort(): int
    {
        return $this->instancePort;
    }

    /**
     * @param int $instancePort
     * @return BackendServerDescription
     */
    public function setInstancePort(int $instancePort): BackendServerDescription
    {
        $this->instancePort = $instancePort;
        return $this;
    }

    /**
     * @return array
     */
    public function getPolicyNames(): array
    {
        return $this->policyNames;
    }

    /**
     * @param array $policyNames
     * @return BackendServerDescription
     */
    public function setPolicyNames(array $policyNames): BackendServerDescription
    {
        $this->policyNames = $policyNames;
        return $this;
    }
}
