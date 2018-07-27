<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 06/06/17
 * Time: 10:06
 */

namespace GearmanBundle\Job;

use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Profiler\Profiler;

/**
 * Interface JobInterface
 * @package DataSourceBundle\Gearman\Job
 */
interface JobInterface
{
    /**
     * @return AccountInterface
     */
    public function getAccount(): AccountInterface;

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @return DateTime
     */
    public function getDateTime(): DateTime;

    /**
     * @return mixed
     */
    public function getProfiler();

    /**
     * @param Profiler $profiler
     * @return JobInterface
     */
    public function setProfiler(Profiler $profiler): JobInterface;
}
