<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/01/17
 * Time: 09:45
 */

namespace EssentialsBundle\Entity\Metric;

use EssentialsBundle\Collection\Metric\PointCollection;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\Tag\Tag;

/**
 * Class Builder
 * @package Entity\Common\Metric
 */
class Builder
{
    /**
     * @param array $data
     * @return MetricCollection
     */
    public static function build(array $data): MetricCollection
    {
        $metrics = new MetricCollection();

        foreach ($data as $metric) {
            $metrics->add(
                new Metric(
                    $metric['name'],
                    self::buildTags($metric['tags']),
                    self::buildPoints($metric['points'])
                )
            );
        }

        return $metrics;
    }

    /**
     * @param array $data
     * @return TagCollection
     */
    protected static function buildTags(array $data): TagCollection
    {
        $tags = new TagCollection();

        foreach ($data as $tag) {
            $tags->add(new Tag($tag['key'], $tag['value']));
        }

        return $tags;
    }

    /**
     * @param array $data
     * @return PointCollection
     */
    public static function buildPoints(array $data): PointCollection
    {
        $datapoints = new PointCollection();

        foreach ($data as $point) {
            $datapoints->add(
                new Point(
                    $point['value'],
                    $point['minimum'],
                    $point['maximum'],
                    $point['sampleCount'],
                    $point['sum'],
                    new DateTime($point['time']),
                    $point['unit']
                )
            );
        }

        return $datapoints;
    }
}
