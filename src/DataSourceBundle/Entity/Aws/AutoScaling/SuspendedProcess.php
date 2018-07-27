<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 03/02/17
 * Time: 10:37
 */

namespace DataSourceBundle\Entity\Aws\AutoScaling;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class SuspendedProcess
 * @package DataSourceBundle\Entity\Aws\AutoScaling
 */
class SuspendedProcess extends EntityAbstract
{
    /**
     * @var string
     */
    protected $processName;

    /**
     * @var string
     */
    protected $suspensionReason;

    public function __construct(
        string $processName,
        string $suspensionReason
    ) {
        $this->setProcessName($processName)
            ->setSuspensionReason($suspensionReason);
    }

    /**
     * @return string
     */
    public function getProcessName(): string
    {
        return $this->processName;
    }

    /**
     * @param string $processName
     * @return SuspendedProcess
     */
    public function setProcessName(string $processName): SuspendedProcess
    {
        $this->processName = $processName;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuspensionReason(): string
    {
        return $this->suspensionReason;
    }

    /**
     * @param string $suspensionReason
     * @return SuspendedProcess
     */
    public function setSuspensionReason(string $suspensionReason): SuspendedProcess
    {
        $this->suspensionReason = $suspensionReason;
        return $this;
    }
}
