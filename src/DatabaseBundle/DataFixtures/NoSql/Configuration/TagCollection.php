<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/08/17
 * Time: 15:05
 */

namespace DatabaseBundle\DataFixtures\NoSql\Configuration;

/**
 * Class TagCollection
 * @package DatabaseBundle\DataFixtures\NoSql\Configuration
 */
class TagCollection extends \EssentialsBundle\Collection\Tag\TagCollection
{
    public function getClass(): string
    {
        return Tag::class;
    }
}
