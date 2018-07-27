<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/01/17
 * Time: 11:08
 */

namespace EssentialsBundle\Entity;

/**
 * Class EntityAbstract
 * @package Entity
 */
abstract class EntityAbstract implements \JsonSerializable
{
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->getObjectVarsRecursive($this->toArray());
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
                $result[$key] = $this->getObjectVarsRecursive(get_object_vars($value));
            }

            if (is_array($value)) {
                $result[$key] = $this->getObjectVarsRecursive($value);
            }

            $result[$key] = $value;
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

    /**
     * @return string
     */
    public function hashCode()
    {
        return md5(json_encode($this));
    }

    /**
     * @param EntityAbstract $entity
     * @return bool
     */
    public function equals($entity): bool
    {
        return ($this->hashCode() == $entity->hashCode());
    }

    public function clone()
    {
        return unserialize(serialize($this));
    }
}
