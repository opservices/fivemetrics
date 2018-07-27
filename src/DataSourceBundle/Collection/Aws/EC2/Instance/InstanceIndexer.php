<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 11/03/17
 * Time: 11:17
 */

namespace DataSourceBundle\Collection\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EC2\Instance\Instance;
use EssentialsBundle\Pattern\Observer\ObservableInterface;
use EssentialsBundle\Pattern\Observer\ObserverInterface;

/**
 * Class Indexer
 * @package InstanceCollection\Aws\EC2\Instance
 */
class InstanceIndexer implements ObserverInterface
{
    const INDEXED_PROPERTIES = [
        'platform',
        'instanceType',
        'tenancy',
        'availabilityZone',
        'instanceState',
        'instanceId'
    ];

    /**
     * @var array
     */
    protected $indexes = [];

    /**
     * @param ObservableInterface $sender
     * @param $args
     * @return ObserverInterface
     */
    public function onChanged(
        ObservableInterface $sender,
        $args
    ): ObserverInterface {
        return $this->updateIndexes($sender, $args);
    }

    /**
     * @param InstanceCollection $collection
     * @param Instance $ec2
     * @return InstanceIndexer
     */
    protected function updateIndexes(
        InstanceCollection $collection,
        Instance $ec2
    ): InstanceIndexer {

        $platform = strtolower($ec2->getPlatform());
        $type     = $ec2->getInstanceType();
        $tenancy  = $ec2->getPlacement()->getTenancy();
        $az       = $ec2->getPlacement()->getAvailabilityZone();
        $state    = $ec2->getState()->getName();
        $id       = $ec2->getInstanceId();

        $index = count($collection) - 1;

        $this->indexes['platform'][$platform][]   = $index;
        $this->indexes['instanceType'][$type][]   = $index;
        $this->indexes['tenancy'][$tenancy][]     = $index;
        $this->indexes['availabilityZone'][$az][] = $index;
        $this->indexes['instanceState'][$state][] = $index;
        $this->indexes['instanceId'][$id][]       = $index;

        return $this;
    }

    /**
     * @param string $property
     * @return bool
     */
    public function isIndexedProperty(string $property): bool
    {
        return (in_array($property, self::INDEXED_PROPERTIES));
    }

    /**
     * @param string $property
     * @param string $value
     * @return array|null
     */
    public function findIndexes(string $property, string $value)
    {
        if (! $this->isIndexedProperty($property)) {
            throw new \InvalidArgumentException(
                'An invalid property has been provided.'
            );
        }

        if ($property == 'platform') {
            /*
             * http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ec2-2016-11-15.html#describereservedinstances
             *
             * Instance platform doesn't have "(Amazon VPC)".
             */
            $value = strtolower(str_replace(
                ' (Amazon VPC)',
                '',
                $value
            ));

            /*
             * The instance platform can only be windows or empty. Empty means
             * Linux/UNIX and to avoid empty instance platform it was solved
             * in the Instance entity constructor.
             *
             * The AWS does not have access to the internals of your Amazon EC2
             * instances or your EBS volumes (including whichever software you
             * are running). It does not matter if a reservation is to Windows
             * SQL Server or just Windows.
             *
             * http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ec2-2016-11-15.html#describereservedinstances
             * https://forums.aws.amazon.com/message.jspa?messageID=167770
             */
            $value = preg_replace(
                '/.*(linux\/unix|windows).*/',
                '$1',
                $value
            );
        }

        return (empty($this->indexes[$property][$value]))
            ? []
            : $this->indexes[$property][$value];
    }
}
