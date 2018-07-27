<?php

namespace FrontendBundle\Controller\Api\V1;

use EssentialsBundle\Api\ControllerAbstract;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Exception\Exceptions;
use FrontendBundle\Form\AccountRegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

/**
 * Class BetaTesterController
 * @package FrontendBundle\Controller\Api\V1
 */
class BetaTesterController extends ControllerAbstract
{
    /**
     * @Route("/", name="betaTesterForm")
     * @Method("GET")
     */
    public function createFormView($form = null)
    {
        $form = $form ?: $this->createForm(AccountRegistrationForm::class);
        return $this->render(
            '@Frontend/account/registration.html.twig',
            [
                'form' => $form->createView(),
                'errors' => $form->getErrors(true),
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="newBetaTester")
     * @Method("POST")
     */
    public function newBetaTesterAction(Request $request)
    {
        $form = $this->createForm(AccountRegistrationForm::class);
        $form->handleRequest($request);

        if (! $form->isSubmitted() || ! $form->isValid()) {
            return $this->createFormView($form);
        }

        /** @var Account $account */
        $account = $form->getData();
        $account->setRoles(['ROLE_API_V1', 'ROLE_ALLOW_ONBOARDING', 'ROLE_PAYMENT_FREE']);

        try {
            $this->get('account.register')->register($account);
            $this->loginUser($account, $request);
        } catch (\InvalidArgumentException $e) {
            $this->get('error.dispatcher')->send($e->getMessage());
            $this->throwApiProblemResponse(Exceptions::VALIDATION_ERROR, [$e->getMessage()]);
        } catch (\Throwable $e) {
            $this->get('error.dispatcher')->send($e->getTrace());
            $this->throwApiProblemResponse(Exceptions::RUNTIME_ERROR);
        }

        return $this->redirectToRoute('app_home', [ 'url' => '' ]);
    }

    protected function sendUserAccessEmail(Account $account)
    {
        $message = new \Swift_Message('FiveMetrics access');

        $body = $this->get('twig')->render(
            'Emails/betaTesterAutoReply.html.twig',
            [
                'account' => [
                    'username' => $account->getEmail(),
                    'password' => $account->getPlainPassword(),
                ]
            ]
        );

        $message->setTo($account->getEmail())
            ->setFrom([$this->getParameter('mailer_user') => 'FiveMetrics'], 'FiveMetrics')
            ->setBody($body, 'text/html');

        $this->get('gearman.taskmanager')
            ->runBackground('mail-sender', serialize($message));
    }
}
