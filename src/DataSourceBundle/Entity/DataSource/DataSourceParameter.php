<?php

namespace DataSourceBundle\Entity\DataSource;

use Doctrine\Common\Collections\ArrayCollection;
use EssentialsBundle\Entity\EntityAbstract;
use EssentialsBundle\Exception\Exceptions;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="DataSourceBundle\Repository\DataSourceParameterRepository")
 * @ORM\Table(
 *     name="data_source_parameters",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="configuration",
 *              columns={"name", "data_source_id"}
 *          )
 *     }
 * )
 * @UniqueEntity(fields={"name", "dataSource"}, message="")
 */
class DataSourceParameter extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSource",
     *     inversedBy="parameters"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $dataSource;

    /**
     * @TODO Check if is possible to change this to a OneToOne relationship because One parameter will never had more than one value, so it doesn't make any sense
     * @ORM\OneToMany(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSourceParameterValue",
     *     mappedBy="parameter"
     * )
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $dataSourceParameterValues;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="^[a-zA-Z0-9][a-zA-Z0-9\-\_]+$",
     *     message="A data source parameter can have only numbers, characters, spaces, '-' or '_'."
     * )
     * @ORM\Column(type="string", nullable=false)
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
     * DataSourceParameter constructor.
     * @param string $name
     * @param DataSource|null $dataSource
     */
    public function __construct(string $name = "", DataSource $dataSource = null)
    {
        if (! empty($name)) {
            $this->setName($name);
        }

        $this->setDataSource($dataSource ?: new DataSource());
        $this->dataSourceParameterValues = new ArrayCollection();
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @param DataSource $dataSource
     * @return DataSourceParameter
     */
    public function setDataSource(DataSource $dataSource): DataSourceParameter
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return DataSourceParameter
     */
    public function setName(string $name): DataSourceParameter
    {
        if (preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\-\_\.]+$/', $name)) {
            $this->name = $name;
            return $this;
        }

        throw new \InvalidArgumentException(
            "The parameter name can't be empty and must have only letters, numbers, dots, \"-\" and \"_\"",
            Exceptions::VALIDATION_ERROR
        );
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'description' => $this->getDescription(),
        ];
    }

    /**
     * @return array
     */
    public function getDataSourceParameterValues()
    {
        return $this->dataSourceParameterValues;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->dataSourceParameterValues[0]->getValue();
    }
}
