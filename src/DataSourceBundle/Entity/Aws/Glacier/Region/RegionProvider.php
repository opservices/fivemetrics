<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/24/17
 * Time: 1:51 PM
 */

namespace DataSourceBundle\Entity\Aws\Glacier\Region;

use DataSourceBundle\Entity\Aws\Region\RegionInterface;

/**
 * Class RegionProvider
 * @package DataSourceBundle\Entity\Aws\S3\Region
 */
class RegionProvider extends \DataSourceBundle\Entity\Aws\Region\RegionProvider
{
    const REGIONS = [
        "us-east-1" => "Virginia",
        "us-east-2" => "Ohio",
        "us-west-1" => "California",
        "us-west-2" => "Oregon",
        "ap-south-1" => "Mumbai",
        "ap-northeast-2" => "Seoul",
        "ap-southeast-2" => "Sydney",
        "ap-northeast-1" => "Tokyo",
        "ca-central-1" => "CanadaCentral",
        "eu-central-1" => "Frankfurt",
        "eu-west-1" => "Ireland",
        "eu-west-2" => "London",
    ];

    /**
     * @param string $region
     * @return RegionInterface
     */
    public static function factory(string $region): RegionInterface
    {
        if (empty(self::REGIONS[$region])) {
            throw new \InvalidArgumentException(
                "An unsupported region code has been provided: " . $region
            );
        }

        return parent::factory($region);
    }

    /**
     * Returns all supported regions.
     *
     * @param bool $extended when it's true all region data is returned and self::REGIONS content otherwise.
     * @return array
     */
    public function listAvailableRegions(bool $extended = false)
    {
        if (! $extended) {
            return array_keys(self::REGIONS);
        }

        $regions = [];
        foreach (self::REGIONS as $code => $class) {
            $regions[] = $this->getRegionData($code);
        }

        return $regions;
    }
}
