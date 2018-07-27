<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/05/17
 * Time: 12:19
 */

namespace EssentialsBundle\Api\Test;

use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Account\AccountInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class ApiTestCase
 * @package EssentialsBundle\Api\Test
 */
class ApiTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected static $staticClient;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ResponseAsserter
     */
    protected $responseAsserter;

    public static function setUpBeforeClass()
    {
        self::$staticClient = static::createClient();
    }

    public function tearDown()
    {
    }

    protected function getService($id)
    {
        return self::$staticClient->getContainer()->get($id);
    }

    protected function getContainer()
    {
        return self::$staticClient->getContainer();
    }

    /**
     * @return ResponseAsserter
     */
    protected function asserter()
    {
        if ($this->responseAsserter === null) {
            $this->responseAsserter = new ResponseAsserter();
        }

        return $this->responseAsserter;
    }

    protected function getAccountInstance(
        array $findCriteria = ['email' => 'tester@fivemetrics.io' ]
    ): Account {
        return $this->getService('doctrine.orm.entity_manager')
            ->getRepository(Account::class)
            ->findOneBy($findCriteria);
    }

    /**
     * @return Client
     */
    protected function getClient(): Client
    {
        $client = self::$staticClient;
        $client->restart();
        (empty($client->getResponse())) ?: $client->getResponse()->setContent('');

        return $client;
    }

    /**
     * @param AccountInterface $account
     * @return Client
     */
    protected function logIn(AccountInterface $account): Client
    {
        $session = $this->getService('session');

        // the firewall context defaults to the firewall name
        $firewallContext = 'secured_area';

        $token = new UsernamePasswordToken(
            $account,
            null,
            $firewallContext,
            $account->getRoles()
        );

        $session->set('_security_' . $firewallContext, serialize($token));
        $session->save();

        $client = $this->getClient();

        $cookie = new Cookie($session->getName(), $session->getId());

        $client->getCookieJar()->set($cookie);

        return $client;
    }
}
