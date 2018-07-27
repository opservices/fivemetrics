<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class RegionProvider
 */
class RegionProvider
{
    const REGIONS = [
        "us-east-1" => "Virginia",
        "us-east-2" => "Ohio",
        "us-west-1" => "California",
        "us-west-2" => "Oregon",
        "ca-central-1" => "CanadaCentral",
        "ap-south-1" => "Mumbai",
        "ap-northeast-2" => "Seoul",
        "ap-southeast-1" => "Singapore",
        "ap-southeast-2" => "Sydney",
        "ap-northeast-1" => "Tokyo",
        "eu-central-1" => "Frankfurt",
        "eu-west-1" => "Ireland",
        "eu-west-2" => "London",
        "eu-west-3" => "Paris",
        "sa-east-1" => "SaoPaulo",
    ];

    /**
     * Returns a region objects. All valid region codes are available in
     * RegionProvider::REGIONS.
     *
     * @param string $region is a region code.
     * @return RegionInterface
     * @throws \InvalidArgumentException if an invalid region code is provided.
     */
    public static function factory(string $region): RegionInterface
    {
        if (empty(self::REGIONS[$region])) {
            throw new \InvalidArgumentException(
                "An unsupported region code has been provided: " . $region
            );
        }

        $class = 'DataSourceBundle\\Entity\\Aws\\Region\\' . self::REGIONS[$region];
        return new $class();
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

    /**
     * Returns a regions data.
     *
     * @param string $code
     * @return array
     */
    protected function getRegionData(string $code)
    {
        $region = $this->factory($code);

        $data = [
            "name"  => $region->getName(),
            "code"    => $region->getCode()
        ];

        unset($region);

        return $data;
    }
}
