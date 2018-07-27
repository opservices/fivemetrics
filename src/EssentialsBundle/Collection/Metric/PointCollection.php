<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 16:18
 */

namespace EssentialsBundle\Collection\Metric;

use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Collection\TypedCollectionAbstract;
use EssentialsBundle\Entity\Metric\Point;

/**
 * Class PointCollection
 * @package EssentialsBundle\Collection\Metric
 */
class PointCollection extends TypedCollectionAbstract
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return 'EssentialsBundle\Entity\Metric\Point';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }

    /**
     * @param string $measurementName
     * @param TagCollection $tags
     * @return array
     */
    public function toInfluxPoints(
        string $measurementName,
        TagCollection $tags
    ): array {
        $array = [];

        foreach ($this->elements as $el) {
            /** @var Point $el */
            $array[] = $el->toInfluxPoint($measurementName, $tags);
        }

        return $array;
    }
}
