<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/01/17
 * Time: 08:53
 */

namespace DataSourceBundle\Entity\Aws;

/**
 * Class EntityAbstract
 * @package DataSourceBundle\Entity\Aws
 */
class EntityAbstract extends \EssentialsBundle\Entity\EntityAbstract
{
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->getObjectVarsRecursive(get_object_vars($this));
    }

    /**
     * @param array $arr
     * @return array
     */
    protected function getObjectVarsRecursive(array $arr): array
    {
        $result = [];

        foreach ($arr as $key => $value) {
            if (is_null($value)) {
                continue;
            }

            if (is_object($value)) {
                $result[ucfirst($key)] = $this->getObjectVarsRecursive(
                    get_object_vars($value)
                );
            }

            if (is_array($value)) {
                $result[ucfirst($key)] = $this->getObjectVarsRecursive($value);
            }

            $result[ucfirst($key)] = $value;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return json_decode(
            json_encode(
                $this->getObjectVarsRecursive(get_object_vars($this))
            ),
            true
        );
    }
}
