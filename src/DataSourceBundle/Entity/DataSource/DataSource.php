<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/09/17
 * Time: 08:45
 */

namespace DataSourceBundle\Entity\DataSource;

use Doctrine\Common\Collections\ArrayCollection;
use EssentialsBundle\Entity\EntityAbstract;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="data_source")
 * @UniqueEntity(fields={"name"}, message="It looks like you already have this data source.")
 */
class DataSource extends EntityAbstract
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="^[a-zA-Z0-9][a-zA-Z0-9\-\_\.]+$",
     *     message="A data source group can have only numbers, characters, spaces, dots, '-' or '_'."
     * )
     * @ORM\Column(type="string", length=64, nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    protected $label;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    protected $description;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    protected $icon;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSourceGroup",
     *     inversedBy="dataSources"
     * )
     */
    protected $groups;

    /**
     * @ORM\OneToMany(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSourceParameterValue",
     *     mappedBy="dataSource"
     * )
     */
    protected $dataSourceParameterValues;

    /**
     * @var DataSourceConfiguration
     * @ORM\OneToOne(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSourceConfiguration",
     *     inversedBy="dataSource"
     * )
     */
    protected $dataSourceConfiguration;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSourceParameter",
     *     mappedBy="dataSource"
     * )
     */
    protected $parameters;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSourceCollect",
     *     mappedBy="dataSource"
     * )
     */
    protected $collects;

    /**
     * DataSource constructor.
     * @param string|null $name
     * @param ArrayCollection $groups
     */
    public function __construct(
        string $name = null,
        array $groups = null
    ) {
        $this->groups = new ArrayCollection((is_null($groups)) ? [] : $groups);
        $this->dataSourceParameterValues = new ArrayCollection();
        $this->parameters = new ArrayCollection();
        $this->collects = new ArrayCollection();

        (is_null($name)) ?: $this->setName($name);
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return DataSource
     */
    protected function setName(string $name): DataSource
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @return mixed
     */
    public function setParameters(ArrayCollection $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataSourceParameterValues()
    {
        return $this->dataSourceParameterValues;
    }

    /**
     * @return DataSourceConfiguration
     */
    public function getDataSourceConfiguration(): DataSourceConfiguration
    {
        return $this->dataSourceConfiguration;
    }

    /**
     * @return DataSource
     */
    public function setDataSourceConfiguration(
        DataSourceConfiguration $configuration
    ): DataSource {
        $this->dataSourceConfiguration = $configuration;
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'icon' => $this->getIcon(),
            'groups' => $this->getGroups()->toArray(),
            'configuration' => $this->getDataSourceConfiguration()->toArray(),
            'parameters' => array_map(
                function (DataSourceParameter $param) {
                    return $param->toArray();
                },
                $this->getParameters()->toArray()
            ),
        ];
    }
}
