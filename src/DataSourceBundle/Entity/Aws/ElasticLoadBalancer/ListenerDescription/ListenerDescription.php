<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 15:58
 */

namespace DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ListenerDescription;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class ListenerDescription
 * @package Entity\Aws\ElasticLoadBalancer\ListenerDescription
 */
class ListenerDescription extends EntityAbstract
{
    /**
     * @var Listener
     */
    protected $listener;

    /**
     * @var array
     */
    protected $policyNames;

    /**
     * ListenerDescription constructor.
     * @param Listener $listener
     * @param array $policyNames
     */
    public function __construct(
        Listener $listener,
        array $policyNames
    ) {
        $this->setListener($listener)
            ->setPolicyNames($policyNames);
    }

    /**
     * @return Listener
     */
    public function getListener(): Listener
    {
        return $this->listener;
    }

    /**
     * @param Listener $listener
     * @return ListenerDescription
     */
    public function setListener(Listener $listener): ListenerDescription
    {
        $this->listener = $listener;
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
     * @return ListenerDescription
     */
    public function setPolicyNames(array $policyNames): ListenerDescription
    {
        $this->policyNames = $policyNames;
        return $this;
    }
}
