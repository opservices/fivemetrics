<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 30/03/18
 * Time: 10:22
 */

namespace EssentialsBundle\Entity\Account;


class PasswordGenerator
{
    /**
     * @var string
     */
    protected $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-';

    /**
     * @var int
     */
    protected $length = 8;

    /**
     * PasswordGenerator constructor.
     * @param string|null $chars
     * @param int|null $length
     */
    public function __construct(string $chars = null, int $length = null)
    {
        (! $chars) ?: $this->chars = $chars;
        (! $length) ?: $this->length = $length;
    }


    /**
     * @return string
     */
    public function build(): string
    {
        return substr(str_shuffle($this->chars), 0, $this->length);
    }
}
