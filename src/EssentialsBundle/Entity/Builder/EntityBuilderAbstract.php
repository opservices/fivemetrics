<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/06/17
 * Time: 16:04
 */

namespace EssentialsBundle\Entity\Builder;

use EssentialsBundle\Exception\Exceptions;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class EntityBuilderAbstract
 * @package EssentialsBundle\Entity\Builder
 */
abstract class EntityBuilderAbstract implements EntityBuilderInterface
{
    protected $validator;

    protected $isDebug;

    public function __construct(ValidatorInterface $validator, bool $isDebug = false)
    {
        $this->validator = $validator;
        $this->isDebug = $isDebug;
    }

    protected function validate($entity, array $validationGroups)
    {
        $errors = $this->validator->validate(
            $entity,
            null,
            $validationGroups
        );

        if (! $errors->count()) {
            return;
        }

        $error = $errors->get(0);

        $msg = $error->getMessage();

        if ($this->isDebug) {
            $msg = sprintf(
                "Message: '%s' Property: '%s'",
                $error->getMessage(),
                $error->getPropertyPath()
            );

            (!$error->getInvalidValue()) ?: $msg .= " Value: '" . $error->getInvalidValue() . "'";
        }

        throw new \InvalidArgumentException($msg, Exceptions::VALIDATION_ERROR);
    }

    /**
     * @param string $class
     * @param array $data
     * @return mixed
     * @throws \ReflectionException
     */
    protected function getInstance(string $class, array $data)
    {
        $entity = new $class();
        $reflector = new \ReflectionClass($class);

        foreach ($data as $propertyName => $value) {
            $property = $reflector->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($entity, $value);
        }

        return $entity;
    }
}
