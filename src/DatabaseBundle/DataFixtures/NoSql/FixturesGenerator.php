<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/07/17
 * Time: 15:27
 */

namespace DatabaseBundle\DataFixtures\NoSql;

use DatabaseBundle\DataFixtures\NoSql\Configuration\Point as PointConf;
use DatabaseBundle\DataFixtures\NoSql\Configuration\Series;
use DatabaseBundle\DataFixtures\NoSql\Configuration\SeriesCollection;
use DatabaseBundle\DataFixtures\NoSql\Configuration\TagCollection as TagCollectionConf;
use DatabaseBundle\DataFixtures\NoSql\Configuration\Tag as TagConf;
use DatabaseBundle\DataFixtures\NoSql\Configuration\Value;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Collection\Metric\PointCollection;
use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\Metric\Metric;
use EssentialsBundle\Entity\Metric\Point;
use EssentialsBundle\Entity\Tag\Tag;

/**
 * Class FixturesGenerator
 * @package DatabaseBundle\DataFixtures\NoSql
 */
class FixturesGenerator
{
    public static function generateSeries(
        SeriesCollection $series,
        int $pointsTotal = null
    ): MetricCollection {
        $metrics = new MetricCollection();

        foreach ($series as $timeSeries) {
            /** @var Series $timeSeries */
            $total = (is_null($pointsTotal))
                ? $timeSeries->getTotal()
                : $pointsTotal;

            $dt     = new DateTime();
            $interval = sprintf("-%s seconds", $timeSeries->getInterval());
            for ($i=0; $i < $total; $i++) {
                $metrics->add(new Metric(
                    $timeSeries->getName(),
                    self::buildTags($timeSeries->getTags()),
                    self::buildPoints(
                        $timeSeries->getPoint(),
                        $dt,
                        $timeSeries->getTags()->find('unit')
                    )
                ));

                $dt->modify($interval);
            }
        }

        return $metrics;
    }

    protected static function buildPoints(
        PointConf $pointConf,
        DateTime $dt,
        TagConf $unit = null
    ): PointCollection {
        $sum = (is_null($pointConf->getSum()))
            ? null : self::valueGenerator($pointConf->getSum());

        $sampleCount = (is_null($pointConf->getSampleCount()))
            ? null : self::valueGenerator($pointConf->getSampleCount());

        $max = (is_null($pointConf->getMaximum()))
            ? null : self::valueGenerator($pointConf->getMaximum());

        $min = (is_null($pointConf->getMinimum()))
            ? null : self::valueGenerator($pointConf->getMinimum());

        $value = (is_null($pointConf->getValue()))
            ? null : self::valueGenerator($pointConf->getValue());

        return new PointCollection([new Point(
            $value,
            $min,
            $max,
            $sampleCount,
            $sum,
            clone($dt),
            (is_null($unit)) ? null : $unit->getValue()->getData()
        )]);
    }

    /**
     * @param TagCollectionConf $data
     * @return TagCollection
     */
    protected static function buildTags(TagCollectionConf $data): TagCollection
    {
        $tags = [];
        foreach ($data as $tag) {
            $tags[] = new Tag(
                $tag->getKey(),
                self::valueGenerator($tag->getValue())
            );
        }

        return new TagCollection($tags);
    }

    protected static function valueGenerator(Value $value)
    {
        if ($value->getType() == 'fixed') {
            return $value->getData();
        }

        $data = $value->getData();

        if (is_array($data)) {
            return $data[rand(0, count($data) - 1)];
        }

        /*
         * At this point "$data" can be only a Range object.
         * See "DatabaseBundle\DataFixtures\NoSql\Configuration\Builder::buildValueData"
         */
        return rand($data->getMinimum(), $data->getMaximum());
    }
}
