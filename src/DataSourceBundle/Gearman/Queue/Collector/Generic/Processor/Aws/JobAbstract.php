<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 03/03/17
 * Time: 09:48
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws;

use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Job\JobInterface;
use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Profiler\Profiler;

/**
 * Class JobAbstract
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws
 */
abstract class JobAbstract
    extends \DataSourceBundle\Gearman\Queue\Collector\Generic\Job\JobAbstract
    implements JobInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var RegionInterface
     */
    protected $region;

    /**
     * JobAbstract constructor.
     * @param AccountInterface $account
     * @param DateTime $datetime
     * @param RegionInterface $region
     * @param string $key
     * @param string $secret
     * @param null $data
     * @param Profiler|null $profiler
     */
    public function __construct(
        AccountInterface $account,
        DateTime $datetime,
        RegionInterface $region,
        string $key,
        string $secret,
        $data = null,
        Profiler $profiler = null
    ) {
        parent::__construct($account, $datetime, $data, $profiler);
        $this->setRegion($region)
            ->setKey($key)
            ->setSecret($secret);
    }

    /**
     * @return string
     */
    abstract public function getProcessor(): string;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return JobAbstract
     */
    public function setKey(string $key): JobAbstract
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     * @return JobAbstract
     */
    public function setSecret(string $secret): JobAbstract
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * @return RegionInterface
     */
    public function getRegion(): RegionInterface
    {
        return $this->region;
    }

    /**
     * @param RegionInterface $region
     * @return JobAbstract
     */
    public function setRegion(RegionInterface $region): JobAbstract
    {
        $this->region = $region;
        return $this;
    }
}
