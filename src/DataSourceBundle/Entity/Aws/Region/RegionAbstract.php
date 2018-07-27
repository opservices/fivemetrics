<?php

namespace DataSourceBundle\Entity\Aws\Region;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class RegionAbstract
 */
abstract class RegionAbstract extends EntityAbstract implements RegionInterface
{
    /**
     * @var
     */
    protected $code;

    /**
     * @var
     */
    protected $name;


    /**
     * RegionAbstract constructor.
     * @param string $code
     * @param string $name
     */
    public function __construct(string $code, string $name)
    {
        $this->setCode($code)
            ->setName($name);
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set code
     *
     * @param String $code
     * @return $this
     */
    protected function setCode(string $code)
    {
        if (empty($code)) {
            throw new \InvalidArgumentException("Region code can't be empty.");
        }

        $this->code = $code;

        return $this;
    }

    /**
     * Get name.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param String $name
     * @throws \InvalidArgumentException
     * @return $this
     */
    protected function setName(string $name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("Region ame can't be empty.");
        }

        $this->name = $name;

        return $this;
    }
}
