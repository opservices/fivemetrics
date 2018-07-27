<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/02/17
 * Time: 17:04
 */

namespace DataSourceBundle\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class IamInstanceProfile
 * @package DataSourceBundle\Entity\Aws\EC2\Instance
 */
class IamInstanceProfile extends EntityAbstract
{
    /**
     * @var string
     */
    protected $arn;

    /**
     * @var string
     */
    protected $id;

    public function __construct(string $arn, string $id)
    {
        $this->setArn($arn)
            ->setId($id);
    }

    /**
     * @return string
     */
    public function getArn(): string
    {
        return $this->arn;
    }

    /**
     * @param string $arn
     * @return IamInstanceProfile
     */
    public function setArn(string $arn): IamInstanceProfile
    {
        $this->arn = $arn;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return IamInstanceProfile
     */
    public function setId(string $id): IamInstanceProfile
    {
        $this->id = $id;
        return $this;
    }
}
