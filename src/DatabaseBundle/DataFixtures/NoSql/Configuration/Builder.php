<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/08/17
 * Time: 15:18
 */

namespace DatabaseBundle\DataFixtures\NoSql\Configuration;

/**
 * Class Builder
 * @package DatabaseBundle\DataFixtures\NoSql\Configuration
 */
class Builder
{
    public static function factory(array $conf): SeriesCollection
    {
        $seriesCollection = new SeriesCollection();

        foreach ($conf['series'] as $arr) {
            $seriesCollection->add(new Series(
                $arr['name'],
                $arr['total'],
                $arr['interval'],
                self::buildTags($arr['tags']),
                self::buildPoint($arr['point'])
            ));
        }

        return $seriesCollection;
    }

    protected static function buildTags(array $conf): TagCollection
    {
        $tags = new TagCollection();

        foreach ($conf as $tag) {
            $tags->add(new Tag(
                $tag['key'],
                new Value(
                    $tag['value']['type'],
                    self::buildValueData($tag['value'])
                )
            ));
        }

        return $tags;
    }

    protected static function buildPoint(array $conf): Point
    {
        $pointKeys = [ 'value', 'minimum', 'maximum', 'sampleCount', 'sum' ];
        $values = [];

        foreach ($pointKeys as $key) {
            $values[$key] = (isset($conf[$key]))
                ? new Value(
                    $conf[$key]['type'],
                    self::buildValueData($conf[$key])
                ) : null;
        }

        return new Point(
            $values['value'],
            $values['minimum'],
            $values['maximum'],
            $values['sampleCount'],
            $values['sum']
        );
    }

    protected static function buildValueData(array $conf)
    {
        $data = $conf['data'];

        return (($conf['type'] == 'random') && (isset($data['range'])))
            ? new Range($data['range']['min'], $data['range']['max'])
            : $data;
    }
}
