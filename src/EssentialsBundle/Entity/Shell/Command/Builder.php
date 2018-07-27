<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 29/01/17
 * Time: 03:36
 */

namespace EssentialsBundle\Entity\Shell\Command;

use EssentialsBundle\Collection\Shell\Command\ArgumentCollection;

/**
 * Class Builder
 * @package DataSourceBundle\Entity\Shell\Command
 */
class Builder
{
    /**
     * @param array $data
     * @return Command
     */
    public static function build(array $data): Command
    {
        $arguments = new ArgumentCollection();

        foreach ($data['arguments'] as $arg) {
            $value = (isset($arg['value'])) ? $arg['value'] : null;
            $arguments->add(
                new Argument($arg['name'], $value)
            );
        }

        return new Command($data['executable'], $arguments);
    }
}
