<?php

namespace EssentialsBundle\Entity\Account;

/**
 * Class Payment
 * @author Sidney Souza
 */
class Payment
{
    const IS_OK = true;
    const IS_NOT_OK = false;

    const VERIFIED = 'ROLE_PAYMENT_VERIFIED';
    const FREE = 'ROLE_PAYMENT_FREE';
    const TRIAL = 'ROLE_TRIAL';


    /**
     * Return whether payment is successfully completed
     *
     * This method check whether an account has a valid
     * payment method configured
     *
     * @param Account $account
     * @return bool
     */
    public function isOk(Account $account): bool
    {
        return (
            $account->hasRole(self::VERIFIED)
            || $account->hasRole(self::FREE)
            || $account->hasRole(self::TRIAL)
        );
    }

    /**
     * Define an account payment status
     *
     * @param Account $account
     * @param bool $isOK The payment status. It may be either
     *                   PAYMENT::OK or PAYMENT::IS_NOT_OK
     *
     */
    public function setStatus(Account $account, bool $isOK)
    {
        if (self::IS_OK === $isOK) {
            $account->addRole(self::VERIFIED);
            $account->removeRole(self::TRIAL);
        }

        if (self::IS_NOT_OK === $isOK) {
            $account->removeRole(self::VERIFIED);
        }
    }

    /**
     * Return what type of payment belongs to the given account
     *
     * @param Account $account
     * @return string
     */
    public function getType(Account $account): string
    {
        if ($account->hasRole(self::VERIFIED)
            || $account->hasRole(self::FREE)
        ) {
            return 'verified';
        }

        if ($account->hasRole(self::TRIAL)) {
            return 'trial';
        }

        return 'pending';
    }
}
