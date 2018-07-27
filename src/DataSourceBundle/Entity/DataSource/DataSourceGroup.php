<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 01/09/17
 * Time: 13:49
 */

namespace DataSourceBundle\Entity\DataSource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EssentialsBundle\Entity\EntityAbstract;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DataSourceGroup
 * @package DataSourceBundle\Entity\DataSource
 * @ORM\Entity
 * @ORM\Table(name="data_source_groups")
 * @UniqueEntity(fields={"name"}, message="It looks like you already have this data source group.")
 */
class DataSourceGroup extends EntityAbstract
{
    /**
     * @var int $id
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string $name
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="^[a-zA-Z0-9][a-zA-Z0-9\-\_]+$",
     *     message="A data source group can have only numbers, characters, spaces, '-' or '_'."
     * )
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSource",
     *     inversedBy="groups"
     * )
     */
    protected $dataSources;

    /**
     * DataSourceGroup constructor.
     */
    public function __construct(string $name = null)
    {
        (is_null($name)) ?: $this->name = $name;
        $this->dataSources = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDataSources()
    {
        return $this->dataSources;
    }

    public function addDataSource(DataSource $dataSource): DataSourceGroup
    {
        $this->dataSources[] = $dataSource;
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->getName(),
        ];
    }
}
