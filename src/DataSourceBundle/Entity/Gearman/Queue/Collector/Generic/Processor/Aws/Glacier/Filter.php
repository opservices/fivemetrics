<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/27/17
 * Time: 2:27 PM
 */

namespace DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier;

use DataSourceBundle\Entity\Aws\Glacier\Vault\Vault;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Filter as AwsFilter;

/**
 * Class Filter
 * @package DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier
 */
class Filter extends AwsFilter
{
    /**
     * @var Vault
     */
    protected $vault;

    /**
     * Filter constructor.
     * @param string $namespace
     * @param array $measurementNames
     * @param Vault|null $vault
     */
    public function __construct($namespace, array $measurementNames, Vault $vault = null)
    {
        parent::__construct($namespace, $measurementNames);
        (is_null($vault))?: $this->setVault($vault);
    }

    /**
     * @return Vault
     */
    public function getVault(): Vault
    {
        return $this->vault;
    }

    /**
     * @param Vault $vault
     * @return Filter
     */
    public function setVault(Vault $vault): Filter
    {
        $this->vault = $vault;
        return $this;
    }
}
