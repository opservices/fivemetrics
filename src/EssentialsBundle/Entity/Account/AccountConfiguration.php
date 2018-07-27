<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/08/17
 * Time: 15:54
 */

namespace EssentialsBundle\Entity\Account;

use Doctrine\ORM\Mapping as ORM;
use EssentialsBundle\Entity\EntityAbstract;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AccountConfiguration
 * @package EssentialsBundle\Entity\Account
 * @ORM\Entity
 * @ORM\Table(
 *     name="account_configuration",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="account_configuration_configuration",
 *              columns={"account_id", "name"}
 *          )
 *     }
 * )
 * @UniqueEntity(fields={"account", "name"}, message="")
 */
class AccountConfiguration extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="EssentialsBundle\Entity\Account\Account"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $account;

    /**
     * @ORM\Column(type="string")
     * @Assert\Regex(pattern="/^([a-zA-Z0-9\-\_\.])+$/")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $value;

    /**
     * AccountConfiguration constructor.
     * @param Account|null $account
     * @param string|null $name
     * @param string|null $value
     */
    public function __construct(
        Account $account = null,
        string $name = null,
        string $value = null
    ) {
        (is_null($account)) ?: $this->account = $account;
        (is_null($name)) ?: $this->name = $name;
        (is_null($value)) ?: $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return AccountConfiguration
     */
    public function setName(string $name): AccountConfiguration
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return AccountConfiguration
     */
    public function setValue(string $value): AccountConfiguration
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     * @return AccountConfiguration
     */
    public function setAccount(Account $account): AccountConfiguration
    {
        $this->account = $account;
        return $this;
    }

    public function toArray(bool $removeAccount = true)
    {
        $configuration = parent::toArray();

        unset($configuration['id']);

        if ($removeAccount) {
            unset($configuration['account']);
        }

        return $configuration;
    }
}
