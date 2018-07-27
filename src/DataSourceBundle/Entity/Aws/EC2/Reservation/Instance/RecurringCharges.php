<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/03/17
 * Time: 15:49
 */

namespace DataSourceBundle\Entity\Aws\EC2\Reservation\Instance;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class RecurringCharges
 * @package DataSourceBundle\Entity\Aws\EC2\Reservation\Instance
 */
class RecurringCharges extends EntityAbstract
{
    /**
     * @var float
     */
    protected $amount;

    /**
     * @var string
     */
    protected $frequency;

    /**
     * RecurringCharges constructor.
     * @param float $amount
     * @param $frequency
     */
    public function __construct($amount, $frequency)
    {
        $this->setAmount($amount)
            ->setFrequency($frequency);
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return RecurringCharges
     */
    public function setAmount(float $amount): RecurringCharges
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param mixed $frequency
     * @return RecurringCharges
     */
    public function setFrequency($frequency): RecurringCharges
    {
        $this->frequency = $frequency;
        return $this;
    }
}
