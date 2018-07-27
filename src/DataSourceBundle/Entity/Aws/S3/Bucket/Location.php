<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/6/17
 * Time: 6:09 PM
 */

namespace DataSourceBundle\Entity\Aws\S3\Bucket;

use DataSourceBundle\Entity\Aws\EntityAbstract;
use EssentialsBundle\Exception\Exceptions;

/**
 * Class Location
 * @package DataSourceBundle\Entity\Aws\S3\Bucket
 */
class Location extends EntityAbstract
{

    /**
     * @var string
     */
    protected $locationConstraint;

    /**
     * Location constructor.
     * @param string $locationConstraint
     */
    public function __construct($locationConstraint)
    {
        $this->setLocationConstraint($locationConstraint);
    }

    /**
     * @return string
     */
    public function getLocationConstraint(): string
    {
        return $this->locationConstraint;
    }

    /**
     * @param string $locationConstraint
     * @return Location
     */
    public function setLocationConstraint(string $locationConstraint): Location
    {
        if (empty($locationConstraint)) {
            throw new \InvalidArgumentException(
                "Invalid location",
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->locationConstraint = $locationConstraint;
        return $this;
    }
}
