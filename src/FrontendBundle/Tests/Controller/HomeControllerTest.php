<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/07/17
 * Time: 13:52
 */

namespace FrontendBundle\Tests\Controller;

use EssentialsBundle\Api\Test\ApiTestCase;

/**
 * Class HomeControllerTest
 * @package FrontendBundle\Tests\FrontendBundle\Controller
 */
class HomeControllerTest extends ApiTestCase
{
    /**
     * @test
     */
    public function homeAccessWithoutLogin()
    {
        $client = $this->getClient();
        $client->request('GET', '/');
        $client->getResponse()->getContent();
        $crawler = $client->getCrawler();

        $this->assertEquals(
            0,
            $crawler->filter("script[src='/js/app.js']")->count()
        );
    }

    /**
     * @testdox Should redirect user to onboarding when they have the role ROLE_ALLOW_ONBOARDING
     */
    public function redirectToOnboarding()
    {
        $account = $this->getAccountInstance();
        $account->addRole('ROLE_ALLOW_ONBOARDING');
        $this->assertEquals('/app/onboarding', $this->getRedirectLink($account));
    }

    /**
     * @testdox Should redirect user to home when they have no role ROLE_ALLOW_ONBOARDING
     */
    public function redirectToHome()
    {
        $account = $this->getAccountInstance();
        $account->removeRole('ROLE_ALLOW_ONBOARDING');
        $this->assertEquals('/app/', $this->getRedirectLink($account));
    }

    /**
     * @param $account
     * @return null|string
     */
    protected function getRedirectLink($account)
    {
        $client = $this->login($account);
        $client->request('GET', '/');
        $crawler = $client->getCrawler();
        $client->request('GET', '/logout');
        return $crawler->filter('body > a')->attr('href');
    }
}
