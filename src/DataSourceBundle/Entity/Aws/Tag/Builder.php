<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 13/02/17
 * Time: 16:36
 */

namespace DataSourceBundle\Entity\Aws\Tag;

use DataSourceBundle\Collection\Aws\Tag\TagCollection;

/**
 * Class Builder
 * @package DataSourceBundle\Entity\Aws\Tag
 */
class Builder
{
    /**
     * @param array|null $data
     * @return TagCollection
     */
    public static function build(array $data = null): TagCollection
    {
        if (empty($data)) {
            return new TagCollection();
        }

        $tags = new TagCollection();

        foreach ($data as $tag) {
            $tags->add(
                new Tag($tag['Key'], $tag['Value'])
            );
        }

        return $tags;
    }
}
