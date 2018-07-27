<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/06/17
 * Time: 14:45
 */

namespace EssentialsBundle\Entity\Account;

use EssentialsBundle\Entity\Builder\EntityBuilderAbstract;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AccountBuilder
 * @package EssentialsBundle\Entity\Account
 */
class AccountBuilder extends EntityBuilderAbstract
{
    /**
     * @var PasswordGenerator
     */
    protected $passwordGenerator;

    /**
     * AccountBuilder constructor.
     * @param ValidatorInterface $validator
     * @param bool $isDebug
     * @param PasswordGenerator $passwordGenerator
     */
    public function __construct(ValidatorInterface $validator, bool $isDebug = false, PasswordGenerator $passwordGenerator = null)
    {
        parent::__construct($validator, $isDebug);
        $this->passwordGenerator = $passwordGenerator ?? new PasswordGenerator();
    }

    /**
     * @param array $data
     * @param array $validationGroups
     * @return Account
     * @throws \ReflectionException
     */
    public function factory(array $data, array $validationGroups = ['BuiltInstance']): Account
    {
        if (empty($data['plainPassword']) && empty($data['password'])) {
            $data['plainPassword'] = $this->passwordGenerator->build();
        }

        $account = $this->getInstance(Account::class, $data);

        if (! empty($validationGroups)) {
            $this->validate($account, $validationGroups);
        }

        return $account;
    }
}
