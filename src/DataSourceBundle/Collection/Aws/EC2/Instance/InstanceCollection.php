<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 15:36
 */

namespace DataSourceBundle\Collection\Aws\EC2\Instance;

use EssentialsBundle\Collection\TypedCollectionAbstract;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\Reservation;
use EssentialsBundle\Pattern\Observer\ObservableInterface;
use EssentialsBundle\Pattern\Observer\ObserverInterface;

/**
 * Class InstanceCollection
 * @package InstanceCollection\Aws\EC2
 */
class InstanceCollection extends TypedCollectionAbstract implements ObservableInterface
{
    /**
     * @var array
     */
    protected $indexes = [];

    /**
     * @var array
     */
    protected $observers = [];

    /**
     * @var InstanceIndexer
     */
    protected $indexer = null;

    /**
     * InstanceCollection constructor.
     * @param array $elements
     * @param InstanceIndexer|null $indexer
     */
    public function __construct(
        array $elements = [],
        InstanceIndexer $indexer = null
    ) {
        if (! is_null($indexer)) {
            $this->addObserver($indexer);
            $this->indexer = $indexer;
        };

        parent::__construct($elements);
    }

    /**
     * @return InstanceIndexer|null
     */
    public function getIndexer()
    {
        return $this->indexer;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\EC2\Instance\Instance';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
        $this->last();
        $index = $this->key();

        if ($removed) {
            $key   = $removed->getInstanceId();
            $index = $this->indexes[$key];
            unset($this->indexes[$key]);
        }

        if ($added) {
            $key = $added->getInstanceId();
            $this->indexes[$key] = $index;

            foreach ($this->observers as $obs) {
                $obs->onChanged($this, $added);
            }
        }

    }

    /**
     * @param ObserverInterface $observer
     * @return ObservableInterface
     */
    public function addObserver(
        ObserverInterface $observer
    ): ObservableInterface {
        $this->observers[] = $observer;
        return $this;
    }

    /**
     * @param string $instanceId
     * @return mixed
     */
    public function find(string $instanceId)
    {
        return (isset($this->indexes[$instanceId]))
            ? $this->elements[$this->indexes[$instanceId]]
            : null;
    }

    /**
     * @param Reservation $reservation
     * @return array
     */
    protected function findReservationMatches(Reservation $reservation): array
    {
        $type     = $reservation->getInstanceType();
        $tenancy  = $reservation->getInstanceTenancy();
        $az       = $reservation->getAvailabilityZone();
        $platform = $reservation->getProductDescription();

        $indexes = array_intersect(
            $this->getIndexer()->findIndexes('platform', $platform),
            $this->getIndexer()->findIndexes('tenancy', $tenancy),
            $this->getIndexer()->findIndexes('instanceType', $type)
        );

        if (! empty($az)) {
            $indexes = array_intersect(
                $indexes,
                $this->getIndexer()->findIndexes('availabilityZone', $az)
            );
        }

        return (is_null($indexes)) ? [] : $indexes;
    }

    /**
     * @param string $instanceState
     * @return array
     */
    protected function findInstanceStateMatches(string $instanceState): array
    {
        return $this->getIndexer()->findIndexes('instanceState', $instanceState);
    }

    /**
     * @param Reservation $reservation
     * @param string|null $instanceState
     * @return InstanceCollection
     */
    public function matchReservation(
        Reservation $reservation,
        string $instanceState = null
    ): InstanceCollection {
        if (is_null($this->getIndexer())) {
            throw new \RuntimeException(
                "Isn't possible match reservations without an InstanceIndexer."
            );
        }

        $instances = new InstanceCollection();
        $indexes   = $this->findReservationMatches($reservation);

        if (! is_null($instanceState)) {
            $indexes = array_intersect(
                $indexes,
                $this->findInstanceStateMatches($instanceState)
            );
        }

        foreach ($indexes as $index) {
            $instances->add(clone($this->at($index)));
        }

        return $instances;
    }

    public function clear()
    {
        $this->indexes = [];
        return parent::clear();
    }
}
