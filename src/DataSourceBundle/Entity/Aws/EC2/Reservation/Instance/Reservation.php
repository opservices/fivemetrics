<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/03/17
 * Time: 15:38
 */

namespace DataSourceBundle\Entity\Aws\EC2\Reservation\Instance;

use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\RecurringChargesCollection;
use DataSourceBundle\Entity\Aws\EntityAbstract;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Reservation
 * @package DataSourceBundle\Entity\Aws\EC2\Reservation\Instance
 */
class Reservation extends EntityAbstract
{
    /**
     * @var string
     */
    protected $availabilityZone;

    /**
     * @var string
     */
    protected $currencyCode;

    /**
     * @var int
     */
    protected $duration;

    /**
     * @var DateTime
     */
    protected $end;

    /**
     * @var float
     */
    protected $fixedPrice;

    /**
     * @var int
     */
    protected $instanceCount;

    /**
     * @var string
     */
    protected $instanceTenancy;

    /**
     * @var string
     */
    protected $instanceType;

    /**
     * @var string
     */
    protected $offeringClass;

    /**
     * @var string
     */
    protected $offeringType;

    /**
     * @var string
     */
    protected $productDescription;

    /**
     * @var RecurringChargesCollection
     */
    protected $recurringCharges;

    /**
     * @var string
     */
    protected $reservedInstancesId;

    /**
     * @var string
     */
    protected $scope;

    /**
     * @var DateTime
     */
    protected $start;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var TagCollection
     */
    protected $tags;

    /**
     * @var float
     */
    protected $usagePrice;

    /**
     * Reservation constructor.
     * @param string $reservedInstancesId
     * @param string $instanceType
     * @param DateTime $start
     * @param DateTime $end
     * @param int $duration
     * @param float $usagePrice
     * @param int $fixedPrice
     * @param int $instanceCount
     * @param string $productDescription
     * @param string $state
     * @param string $instanceTenancy
     * @param string $currencyCode
     * @param string $offeringType
     * @param RecurringChargesCollection $recurringCharges
     * @param string $offeringClass
     * @param string $scope
     * @param string|null $availabilityZone
     * @param TagCollection|null $tags
     */
    public function __construct(
        string $reservedInstancesId,
        string $instanceType,
        DateTime $start,
        DateTime $end,
        int $duration,
        float $usagePrice,
        int $fixedPrice,
        int $instanceCount,
        string $productDescription,
        string $state,
        string $instanceTenancy,
        string $currencyCode,
        string $offeringType,
        RecurringChargesCollection $recurringCharges,
        string $offeringClass,
        string $scope,
        string $availabilityZone = null,
        TagCollection $tags = null
    ) {
        $this->setReservedInstancesId($reservedInstancesId)
            ->setInstanceType($instanceType)
            ->setStart($start)
            ->setEnd($end)
            ->setDuration($duration)
            ->setUsagePrice($usagePrice)
            ->setFixedPrice($fixedPrice)
            ->setInstanceCount($instanceCount)
            ->setProductDescription($productDescription)
            ->setState($state)
            ->setInstanceTenancy($instanceTenancy)
            ->setCurrencyCode($currencyCode)
            ->setOfferingType($offeringType)
            ->setRecurringCharges($recurringCharges)
            ->setOfferingClass($offeringClass)
            ->setScope($scope);

        (is_null($availabilityZone)) ?: $this->setAvailabilityZone($availabilityZone);
        (is_null($tags)) ?: $this->setTags($tags);
    }

    /**
     * @return string|null
     */
    public function getAvailabilityZone()
    {
        return (string) $this->availabilityZone;
    }

    /**
     * @param string $availabilityZone
     * @return Reservation
     */
    public function setAvailabilityZone(string $availabilityZone): Reservation
    {
        $this->availabilityZone = $availabilityZone;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     * @return Reservation
     */
    public function setCurrencyCode(string $currencyCode): Reservation
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     * @return Reservation
     */
    public function setDuration(int $duration): Reservation
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEnd(): DateTime
    {
        return $this->end;
    }

    /**
     * @param DateTime $end
     * @return Reservation
     */
    public function setEnd(DateTime $end): Reservation
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return float
     */
    public function getFixedPrice(): float
    {
        return $this->fixedPrice;
    }

    /**
     * @param float $fixedPrice
     * @return Reservation
     */
    public function setFixedPrice(float $fixedPrice): Reservation
    {
        $this->fixedPrice = $fixedPrice;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstanceCount(): int
    {
        return $this->instanceCount;
    }

    /**
     * @param int $instanceCount
     * @return Reservation
     */
    public function setInstanceCount(int $instanceCount): Reservation
    {
        $this->instanceCount = $instanceCount;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstanceTenancy(): string
    {
        return $this->instanceTenancy;
    }

    /**
     * @param string $instanceTenancy
     * @return Reservation
     */
    public function setInstanceTenancy(string $instanceTenancy): Reservation
    {
        $this->instanceTenancy = $instanceTenancy;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstanceType(): string
    {
        return $this->instanceType;
    }

    /**
     * @param string $instanceType
     * @return Reservation
     */
    public function setInstanceType(string $instanceType): Reservation
    {
        $this->instanceType = $instanceType;
        return $this;
    }

    /**
     * @return string
     */
    public function getOfferingClass(): string
    {
        return $this->offeringClass;
    }

    /**
     * @param string $offeringClass
     * @return Reservation
     */
    public function setOfferingClass(string $offeringClass): Reservation
    {
        $this->offeringClass = $offeringClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getOfferingType(): string
    {
        return $this->offeringType;
    }

    /**
     * @param string $offeringType
     * @return Reservation
     */
    public function setOfferingType(string $offeringType): Reservation
    {
        $this->offeringType = $offeringType;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductDescription(): string
    {
        return $this->productDescription;
    }

    /**
     * @param string $productDescription
     * @return Reservation
     */
    public function setProductDescription(string $productDescription): Reservation
    {
        $this->productDescription = $productDescription;
        return $this;
    }

    /**
     * @return RecurringChargesCollection
     */
    public function getRecurringCharges(): RecurringChargesCollection
    {
        return $this->recurringCharges;
    }

    /**
     * @param RecurringChargesCollection $recurringCharges
     * @return Reservation
     */
    public function setRecurringCharges(RecurringChargesCollection $recurringCharges): Reservation
    {
        $this->recurringCharges = $recurringCharges;
        return $this;
    }

    /**
     * @return string
     */
    public function getReservedInstancesId(): string
    {
        return $this->reservedInstancesId;
    }

    /**
     * @param string $reservedInstancesId
     * @return Reservation
     */
    public function setReservedInstancesId(string $reservedInstancesId): Reservation
    {
        $this->reservedInstancesId = $reservedInstancesId;
        return $this;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     * @return Reservation
     */
    public function setScope(string $scope): Reservation
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getStart(): DateTime
    {
        return $this->start;
    }

    /**
     * @param DateTime $start
     * @return Reservation
     */
    public function setStart(DateTime $start): Reservation
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return Reservation
     */
    public function setState(string $state): Reservation
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return TagCollection|null
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param TagCollection $tags
     * @return Reservation
     */
    public function setTags(TagCollection $tags): Reservation
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return float
     */
    public function getUsagePrice(): float
    {
        return $this->usagePrice;
    }

    /**
     * @param float $usagePrice
     * @return Reservation
     */
    public function setUsagePrice(float $usagePrice): Reservation
    {
        $this->usagePrice = $usagePrice;
        return $this;
    }
}
