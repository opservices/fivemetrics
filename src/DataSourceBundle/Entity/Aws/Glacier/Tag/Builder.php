<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/25/17
 * Time: 3:46 PM
 */

namespace DataSourceBundle\Entity\Aws\Glacier\Tag;

use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Entity\Aws\Tag\Tag;

/**
 * Class Builder
 * @package DataSourceBundle\Aws\Glacier\Measurement\Tag
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

        foreach ($data as $key => $value) {
            $tags->add(
                new Tag($key, $value)
            );
        }

        return $tags;
    }
}
