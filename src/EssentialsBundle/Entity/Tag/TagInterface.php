<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/01/17
 * Time: 17:32
 */

namespace EssentialsBundle\Entity\Tag;

/**
 * Interface TagInterface
 * @package Entity\Common\Tag
 */
interface TagInterface
{
    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return string
     */
    public function getValue(): string;
}
