<?php

namespace EssentialsBundle\Controller\Api\V1;

use EssentialsBundle\Api\ControllerAbstract;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Account\Payment;
use EssentialsBundle\Exception\Exceptions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AccountController
 * @package EssentialsBundle\Controller\Api\V1
 * @Route("/account")
 */
class AccountController extends ControllerAbstract
{
    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/", name="get_account")
     * @Method("GET")
     * @SWG\Get(
     *     summary="Retrieve the account properties.",
     *     @SWG\Response(
     *          response=200,
     *          description="It will return an object with the account properties.",
     *          @SWG\Schema(
     *              @SWG\Property(property="username", type="string"),
     *              @SWG\Property(property="email", type="string"),
     *              @SWG\Property(property="uid", type="string"),
     *              @SWG\Property(property="paymentType", type="string")
     *          ),
     *     )
     * )
     */
    public function getAccountAction()
    {
        $account = $this->get('array.mapper')->fieldSelector(
            $this->getUser()->toArray(),
            [ 'uid', 'username', 'email' ]
        );

        $account['paymentType'] = (new Payment)->getType($this->getUser());

        return $this->createApiResponse($account);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="account_register")
     * @Method("POST")
     * @SWG\Post(
     *     summary="To create an account.",
     *     @SWG\Parameter(
     *          required=true,
     *          in="body",
     *          name="account",
     *          type="string",
     *          description="A JSON string representing the new account.
    <br><br>Example:
    <code>{""email"":""the account email"",""username"":""the username is optional""}</code><br>",
     *          @SWG\Schema(
     *              @SWG\Property(property="uid", type="string"),
     *              @SWG\Property(property="username", type="string")
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=201,
     *          description="The account was created.",
     *          @SWG\Schema(
     *              @SWG\Property(property="email", type="string"),
     *              @SWG\Property(property="username", type="string")
     *          )
     *     ),
     *     @SWG\Response(
     *          response=400,
     *          description="If an email that already exists was provided."
     *     ),
     *     @SWG\Response(
     *          response=403,
     *          description="If a logged user try perform this action."
     *     )
     * )
     */
    public function registerAction(Request $request)
    {
        if (! $this->userHasRole('ROLE_ALLOW_CREATE_ACCOUNT', $this->getUser())) {
            throw new \RuntimeException(
                'You aren\'t allowed to perform this action.',
                Exceptions::ACCESS_DENIED
            );
        }

        $parameters = json_decode($request->getContent(), true);
        (is_array($parameters)) ?: $parameters = [];
        $parameters['roles'] = [ 'ROLE_API_V1', 'ROLE_ALLOW_ONBOARDING', 'ROLE_TRIAL' ];

        $account = $this->get('account.register')->registerFromApiParameters($parameters);
        $account->eraseCredentials();

        $response = $this->get('array.mapper')
            ->fieldSelector(
                $account->toArray(),
                [ 'uid', 'username', 'plainPassword' ]
            );

        return $this->createApiResponse(
            $response,
            Response::HTTP_CREATED
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{uid}", name="account_delete", requirements={"uid": "[^/]+"})
     * @Method("DELETE")
     * @SWG\Delete(
     *     summary="To delete an account.",
     *     @SWG\Parameter(
     *          required=true,
     *          name="uid",
     *          in="path",
     *          type="string",
     *          description="It's the account email that will be removed."
     *     ),
     *     @SWG\Response(
     *          response=204,
     *          description="The account was removed.",
     *     ),
     *     @SWG\Response(
     *          response=403,
     *          description="You can't perform this action.",
     *     ),
     *     @SWG\Response(
     *          response=404,
     *          description="The account wasn't found.",
     *     )
     * )
     */
    public function deleteAccountAction($uid)
    {
        if (! $this->userHasRole('ROLE_ADMIN')) {
            throw new \RuntimeException(
                "You can't perform this action.",
                Exceptions::ACCESS_DENIED
            );
        }

        $em = $this->getDoctrine()
            ->getManager();

        $accountToRemove = $em->getRepository(Account::class)
            ->findOneBy([
                'uid' => $uid
            ]);

        if (empty($accountToRemove)) {
            throw new \InvalidArgumentException(
                'Account not found.',
                Exceptions::RESOURCE_NOT_FOUND
            );
        }

        $client = $this->get('nosql.database.connection.provider')
            ->getConnection()
            ->getClient();

        $client->selectDB($accountToRemove->getUid())
            ->drop();

        $em->remove($accountToRemove);
        $em->flush();

        return $this->createApiResponse(
            '{}',
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * Generates an Account Token and updated the current Account
     *
     * @Route("/token/", name="generate_token");
     * @Method("PUT");
     * @SWG\PUT(
     *   summary="Generate a Account Token",
     *   @SWG\Response(response=404, description="Bad request: Log in to continue"),
     *   @SWG\Response(
     *          response=200,
     *          description="Success",
     *          @SWG\Schema(
     *              @SWG\Property(property="uid", type="string"),
     *              @SWG\Property(property="apiKey", type="string")
     *          )
     *     )
     *  )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function generateTokenAction()
    {
        $account = $this->getLoggedAccount();
        $this->storeAccountApiKey($account, $account->generateApiKey());
        return $this->createApiResponse([
            'uid' => $account->getUid(),
            'apiKey' => $account->getApiKey()
        ]);
    }

    /**
     * Erase an Account Token
     *
     * @Route("/token/", name="erase_token");
     * @Method("DELETE");
     * @SWG\DELETE(
     *   summary="Erase a Account Token",
     *   @SWG\Response(response=404, description="Bad request: Log in to continue"),
     *   @SWG\Response(
     *          response=200,
     *          description="Success",
     *          @SWG\Schema(
     *              @SWG\Property(property="uid", type="string"),
     *          )
     *     )
     *  )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function eraseTokenAction()
    {
        $account = $this->getLoggedAccount();
        $this->storeAccountApiKey($account, '');
        return $this->createApiResponse(['uid' => $account->getUid()]);
    }


    protected function getLoggedAccount()
    {
        $account = $this->getUser();
        if (! $account) {
            throw new \RuntimeException("Log in to continue", Exceptions::ACCESS_DENIED);
        }
        return $account;
    }

    protected function storeAccountApiKey($account, $key)
    {
        $account->setApiKey($key);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
    }
}
