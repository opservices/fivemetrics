<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/03/17
 * Time: 20:42
 */

namespace DataSourceBundle\Entity\Gearman\Metadata;

use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class Metadata
 * @package DataSourceBundle\Entity\Gearman\Metadata
 */
class Metadata extends EntityAbstract
{
    protected $data;

    /**
     * Metadata constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->setData($data);
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     * @return Metadata
     */
    public function setData($data): Metadata
    {
        $this->data = $data;
        return $this;
    }
}
