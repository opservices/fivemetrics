<?php

namespace EssentialsBundle\Tests\Entity\Account;

use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Account\Payment;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    /**
     * @var Account
     */
    private $account;

    /**
     * @var Payment
     */
    private $payment;

    public function setUp()
    {
        $this->account = new Account;
        $this->payment = new Payment;
    }

    /**
     * @testdox Should return if an account payment is OK
     * @dataProvider roleProvider
     */
    public function isPaymentOk($role)
    {
        $this->assertFalse($this->payment->isOk($this->account));

        $this->account->addRole($role);
        $this->assertTrue($this->payment->isOk($this->account));
    }

    public function roleProvider()
    {
        return [
            ['ROLE_PAYMENT_VERIFIED'],
            ['ROLE_PAYMENT_FREE'],
            ['ROLE_TRIAL'],
        ];
    }

    /**
     * @testdox Should define whether a payment is ok or not
     */
    public function setPaymentStatus()
    {
        $this->assertFalse($this->payment->isOk($this->account));

        $this->payment->setStatus($this->account, Payment::IS_OK);
        $this->assertTrue($this->payment->isOk($this->account));

        $this->payment->setStatus($this->account, Payment::IS_NOT_OK);
        $this->assertFalse($this->payment->isOk($this->account));
    }

    /**
     * @testdox Should evaluate the payment type based on an account roles
     * @dataProvider statusProvider
     */
    public function getPaymentType($role, $status)
    {
        $this->account->addRole($role);
        $this->assertEquals($status, $this->payment->getType($this->account));
    }

    public function statusProvider()
    {
        return [
            [Payment::VERIFIED, 'verified'],
            [Payment::FREE, 'verified'],
            [Payment::TRIAL, 'trial'],
            ['FAKE', 'pending']
        ];
    }
}
