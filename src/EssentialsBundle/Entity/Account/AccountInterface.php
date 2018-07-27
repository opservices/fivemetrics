<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 12/03/17
 * Time: 11:44
 */

namespace EssentialsBundle\Entity\Account;

/**
 * Interface AccountInterface
 * @package EssentialsBundle\Entity\Account
 */
interface AccountInterface
{
    public function getEmail(): string;

    public function getUid();

    public function getRoles(): array;
}
