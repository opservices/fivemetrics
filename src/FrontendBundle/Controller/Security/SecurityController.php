<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 19/06/17
 * Time: 11:13
 */

namespace FrontendBundle\Controller\Security;

use EssentialsBundle\Helpers\MailHelper;
use FrontendBundle\Form\LoginForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EssentialsBundle\Entity\Account\Account;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use EssentialsBundle\Api\ControllerAbstract;

class SecurityController extends ControllerAbstract
{
    /**
     * @Route("/login", name="security_login")
     * @Method({"GET", "POST"})
     */
    public function loginAction()
    {
        if (! empty($this->getUser())) {
            return $this->redirectToRoute('app_home', [ 'url' => '' ]);
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_account' => $lastUsername
        ]);

        return $this->render(
            '@Frontend/security/login.html.twig',
            [
                // last username entered by the user
                'form'  => $form->createView(),
                'error' => $error,
            ]
        );
    }

    /**
     * @Route("/logout", name="security_logout")
     * @Method({"GET"})
     */
    public function logoutAction()
    {
        throw new \Exception('This should not be reached.');
    }

    /**
     * @SWG\Get(
     *      summary="Request an URL to reset an user password",
     *      @SWG\Parameter(
     *          required=true,
     *          name="email",
     *          type="string",
     *          in="path",
     *          description="User for who the password will be changed",
     *      ),
     *      @SWG\Response(
     *          description="Status",
     *          response=200,
     *          @SWG\Schema(
     *              @SWG\Property(property="email", type="string")
     *          )
     *      )
     * )
     *
     * @Route("/forgot-password/{email}", name="security_forgot_password")
     * @Route("/forgot-password/")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function forgotAction(Account $account = null, Request $request = null)
    {
        $account = $account ?: $this->getUser();
        if (! $account) {
            $this->throwApiProblemResponse(JsonResponse::HTTP_NOT_FOUND, [ 'Account not found' ]);
        }

        $hostUrl = $request->headers->get('host');
        $generatedTime = time();
        $hash = $account->hashCodeBySalt($generatedTime);
        $token = base64_encode($account->getEmail() . ",$hash,$generatedTime");

        $body = $this->get('twig')->render('Emails/resetPassword.html.twig', [
            'account' => $account,
            'url' => "http://$hostUrl/reset-password/$token"
        ]);

        $message = (new \Swift_Message)
            ->setSubject("Reset Password")
            ->setFrom([$this->getParameter('mailer_user') => 'FiveMetrics'])
            ->setTo($account->getEmail())
            ->setBody($body, 'text/html');

        $this->get(MailHelper::class)->sendMessage($message);
        return $this->json(['email' =>  $account->getEmail()]);
    }

    /**
     * @Route("/reset-password/{token}", name="security_reset_password")
     * @Method({"GET", "POST"})
     */
    public function resetAction($token, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Account::class);

        $account = $this->getAccount($token, $repository);

        if ($request->isMethod('GET')) {
            $this->loginUser($account, $request);
            return $this->redirectToRoute('app_home', ['url' => "reset-password/$token"]);
        }

        $account->setPlainPassword($request->get('password'));
        $em->flush();

        return $this->redirectToRoute('app_home', ['url' => '']);
    }

    /**
     * @return Account $account
     */
    protected function getAccount($token, $repository)
    {
        if ($this->getUser()) {
            return $this->getUser();
        }

        list($email, $hash, $generatedTime) = str_getcsv(base64_decode($token));
        $this->checkTokenInfo($email, $hash, $generatedTime);
        $this->checkTokenExpirationTime($generatedTime);
        $account = $repository->findOneByEmail($email);
        $this->checkTokenInfoIntegrity($account, $generatedTime, $hash);
        return $account;
    }

    protected function checkTokenInfoIntegrity($account, $time, $hash)
    {
        if (! is_a($account, Account::class) || $account->hashCodeBySalt($time) !== $hash) {
            throw $this->createAccessDeniedException("Invalid token info");
        }
    }

    protected function checkTokenExpirationTime($generatedTime)
    {
        $ONE_DAY_IN_SECONDS = 24 * 60 * 60;
        if (($generatedTime - time()) > $ONE_DAY_IN_SECONDS) {
            throw $this->createAccessDeniedException("Invalid token info");
        }
    }

    protected function checkTokenInfo($email, $hash, $time)
    {
        if (! isset($email, $hash, $time)) {
            throw $this->createAccessDeniedException("Invalid token info");
        }
    }
}
