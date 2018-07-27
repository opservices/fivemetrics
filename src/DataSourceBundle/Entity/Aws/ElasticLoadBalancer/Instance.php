<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 15:14
 */

namespace DataSourceBundle\Entity\Aws\ElasticLoadBalancer;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class Instance
 * @package DataSourceBundle\Entity\Aws\ElasticLoadBalancer
 */
class Instance extends EntityAbstract
{
    /**
     * @var string
     */
    protected $instanceId;

    /**
     * Instance constructor.
     * @param string $instanceId
     */
    public function __construct(
        string $instanceId
    ) {
        $this->setInstanceId($instanceId);
    }

    /**
     * @return string
     */
    public function getInstanceId(): string
    {
        return $this->instanceId;
    }

    /**
     * @param string $instanceId
     * @return $this
     */
    public function setInstanceId(string $instanceId)
    {
        $this->instanceId = $instanceId;
        return $this;
    }
}
