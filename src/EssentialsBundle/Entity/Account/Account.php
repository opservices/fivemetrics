<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 12/03/17
 * Time: 11:35
 */

namespace EssentialsBundle\Entity\Account;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\EntityAbstract;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Account
 * @package EssentialsBundle\Entity\Account
 * @ORM\Entity
 * @ORM\Table(name="account")
 * @UniqueEntity(fields={"email"}, message="The account email was already taken, try another")
 */
class Account extends EntityAbstract implements AccountInterface, UserInterface
{

    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", length=64, nullable=false, unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $password;

    /**
     * @Assert\NotBlank(groups={"Registration"})
     * @var string
     */
    protected $plainPassword;

    /**
     * @ORM\Column(type="json_array")
     */
    protected $roles = [];

    /**
     * @Assert\NotBlank(groups={"BuiltInstance"})
     * @ORM\Column(type="string", length=64, unique=true, nullable=false)
     */
    protected $uid;

    /**
     * @var string
     * @Assert\Length(max=64, maxMessage="The ""name"" field is too long. It should have {{ limit }} character or less.")
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $username;

    /**
     * @ORM\OneToMany(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSourceParameterValue",
     *     mappedBy="account"
     * )
     */
    protected $dataSourceParameterValues;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="DataSourceBundle\Entity\DataSource\DataSourceCollect",
     *     mappedBy="account"
     * )
     */
    protected $collects;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $onboardingDoneAt;

    /**
     * Api Key to generate a token
     *
     * @var string
     * @Assert\Length(max=64)
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $apiKey = "";

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    public function __construct()
    {
        $this->dataSourceParameterValues = new ArrayCollection();
        $this->collects = new ArrayCollection();
        $this->createdAt = new DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getOnboardingDoneAt()
    {
        return $this->onboardingDoneAt;
    }

    /**
     * @param DateTime $onboardingDoneAt|null
     * @return Account
     */
    public function setOnboardingDoneAt($onboardingDoneAt): Account
    {
        $this->onboardingDoneAt = $onboardingDoneAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return (empty($this->username))
            ? $this->email
            : $this->username;
    }

    /**
     * @param string $username
     * @return Account
     */
    public function setUsername(string $username): Account
    {
        $this->username = $username;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->hasRole('ROLE_USER')
            ? $this->roles
            : array_merge([ 'ROLE_USER' ], $this->roles);
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     * @return Account
     */
    public function setUid(string $uid): Account
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * @param array $roles
     * @return Account
     */
    public function setRoles(array $roles): Account
    {
        $this->roles = array_values($roles);
        return $this;
    }

    public function addRole(string $role): Account
    {
        if (! $this->hasRole($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(string $role): bool
    {
        $size = count($this->roles);

        $filter = function ($value) use ($role) {
            return ($value != $role);
        };

        $this->setRoles(array_filter($this->roles, $filter));
        return ($size > count($this->roles));
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials(): Account
    {
        $this->plainPassword = null;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Account
     */
    public function setEmail(string $email): Account
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return Account
     */
    public function setPlainPassword(string $plainPassword): Account
    {
        $this->plainPassword = $plainPassword;
        /*
         * Forces the object to look "dirty" to Doctrine. Avoids
         * Doctrine *not* saving this entity, if only plainPassword changes
         */
        $this->password = null;

        return $this;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Generates a Account token and returns it to be after used
     *
     * @return string $token
     */
    public function generateApiKey(): string
    {
        return sha1(random_bytes(40));
    }

    /**
     * Generates a hash based on account info plus a salt
     *
     * @param mixed $salt
     * @return string $hash
     */
    public function hashCodeBySalt($salt = ""): string
    {
        return md5($this->hashCode() . $salt);
    }

    public function toArray()
    {
        $data =  parent::toArray();

        $data['createdAt'] = $this->createdAt->format(DateTime::RFC3339);
        if ($this->onboardingDoneAt) {
            $data['onboardingDoneAt'] = $this->onboardingDoneAt->format(DateTime::RFC3339);
        }

        ($data['id']) ?: $data['id'] = null;

        return $data;
    }
}
