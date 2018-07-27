<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 11:11
 */

namespace GearmanBundle\Worker\Process;

use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class Descriptor
 * @package Gearman\Worker
 */
class Descriptor extends EntityAbstract
{
    const TYPES = [
        'file',
        'pipe'
    ];

    const MODES = [
        'a',
        'r',
        'w'
    ];

    protected $type = 'file';

    /**
     * @var string
     */
    protected $file = '/dev/null';

    /**
     * @var string
     */
    protected $mode;

    /**
     * Descriptor constructor.
     * @param string $mode
     * @param string|null $type
     * @param string|null $file
     */
    public function __construct(
        string $mode,
        string $type = null,
        string $file = null
    ) {
        $this->setMode($mode);
        (is_null($type)) ?: $this->setType($type);
        (is_null($file)) ?: $this->setFile($file);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Descriptor
     */
    public function setType(string $type): Descriptor
    {
        if (! in_array($type, self::TYPES)) {
            throw new \InvalidArgumentException(
                "An invalid descriptor type has been provided."
            );
        }

        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return Descriptor
     */
    public function setFile(string $file): Descriptor
    {
        if (empty($file)) {
            throw new \InvalidArgumentException(
                "An empty filename has been provided."
            );
        }

        $this->file = $file;
        return $this;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     * @return Descriptor
     */
    public function setMode(string $mode): Descriptor
    {
        if (! in_array($mode, self::MODES)) {
            throw new \InvalidArgumentException(
                "An invalid descriptor mode has been provided."
            );
        }

        $this->mode = $mode;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $arr = [ $this->getType() ];
        ($this->getType() == "pipe") ?: $arr[] = $this->getFile();
        $arr[] = $this->getMode();

        return $arr;
    }
}
