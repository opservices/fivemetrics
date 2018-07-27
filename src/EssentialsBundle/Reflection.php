<?php

namespace EssentialsBundle;

use ReflectionClass;
use ReflectionProperty;

/**
 * Class Reflection
 * @package EssentialsBundle
 */
class Reflection
{
    /**
     * @param object $object
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public static function callMethodOnObject($object, $method, array $args = [])
    {
        $reflectionClass = new \ReflectionClass($object);
        $reflectionMethod = $reflectionClass->getMethod($method);
        $reflectionMethod->setAccessible(true);
        return $reflectionMethod->invokeArgs($object, $args);
    }

    /**
     * @param object $object
     * @param string $property
     * @return mixed
     */
    public static function getPropertyOnObject($object, $property)
    {
        $reflectionProperty = self::getReflectionProperty($object, $property);
        return $reflectionProperty->getValue($object);
    }

    /**
     * @param object $object
     * @param string $property
     */
    public static function setPropertyOnObject($object, $property, $value)
    {
        $reflectionProperty = self::getReflectionProperty($object, $property);
        $reflectionProperty->setValue($object, $value);
    }

    protected static function getReflectionProperty($object, $property)
    {
        $reflectionClass = new \ReflectionClass($object);
        $reflectionProperty = $reflectionClass->getProperty($property);
        $reflectionProperty->setAccessible(true);
        return $reflectionProperty;
    }

    public static function getObjectProperties(
        $class,
        int $scope = ReflectionProperty::IS_PUBLIC
    ) {
        $reflector  = new ReflectionClass($class);
        $properties = $reflector->getProperties($scope);

        unset($reflector);

        return array_map(function (ReflectionProperty $obj) {
            return $obj->getName();
        }, $properties);
    }
}
