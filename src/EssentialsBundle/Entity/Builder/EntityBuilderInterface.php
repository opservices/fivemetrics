<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/06/17
 * Time: 15:05
 */

namespace EssentialsBundle\Entity\Builder;

/**
 * Interface EntityBuilderInterface
 * @package EssentialsBundle\Entity
 */
interface EntityBuilderInterface
{
    public function factory(array $data, array $validationGroups = []);
}
