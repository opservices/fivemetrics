<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 06/06/17
 * Time: 13:59
 */

namespace DatabaseBundle\Gearman\Queue\CollectResult;

use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\Entity\DateTime\DateTime;
use GearmanBundle\Job\Job as GearmanJob;

/**
 * Class Job
 * @package DatabaseBundle\Gearman\Queue\NoSql\Writer
 */
class Job extends GearmanJob
{
    /**
     * @var int
     */
    protected $collectId;

    public function __construct(
        AccountInterface $account,
        int $collectId,
        DateTime $datetime = null,
        $data = null
    ) {
        parent::__construct($account, $datetime, $data);
        $this->setCollectId($collectId);
    }

    public function getData(): MetricCollection
    {
        return parent::getData();
    }

    public function getCollectId(): int
    {
        return $this->collectId;
    }

    public function setCollectId(int $id): Job
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException(
                "The collect id must be an integer greater than zero."
            );
        }

        $this->collectId = $id;
        return $this;
    }
}
