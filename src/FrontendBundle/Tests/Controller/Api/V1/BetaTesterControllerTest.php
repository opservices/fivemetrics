<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/10/17
 * Time: 09:05
 */

namespace FrontendBundle\Tests\Controller\Api\V1;

use EssentialsBundle\Entity\Account\Account;
use FrontendBundle\Form\AccountRegistrationForm;
use Symfony\Component\Form\Test\TypeTestCase;

class BetaTesterControllerTest extends TypeTestCase
{
    /**
     * @test
     */
    public function newBetaTester()
    {
        $account = new Account();
        $account
            ->setUsername('aaa')
            ->setEmail('test@test.com')
            ->setPlainPassword('test');

        $formData = [
            "username" => "aaa",
            "email" => "test@test.com",
            "plainPassword" => [
                'first' =>"test",
                'second' => "test",
            ]
        ];

        $form = $this->factory->create(AccountRegistrationForm::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($account, $form->getData());
    }
}
