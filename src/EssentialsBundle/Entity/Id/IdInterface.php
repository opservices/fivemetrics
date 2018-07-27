<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 19/05/17
 * Time: 08:35
 */

namespace EssentialsBundle\Entity\Id;

/**
 * Interface IdInterface
 * @package EssentialsBundle\Entity\Id
 */
interface IdInterface
{
    public function getValue(): string;
}
