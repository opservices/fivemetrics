<?php

namespace FrontendBundle\Controller;

use EssentialsBundle\Api\ControllerAbstract;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Account\AccountConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends ControllerAbstract
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="homepage")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $user = $this->getUser();
        if (empty($user)) {
            return $this->redirectToRoute('security_login');
        }

        if ($this->userHasRole('ROLE_DEVEL', $user)) {
            return $this->redirectToRoute('app_home', ['url' => '']);
        }

        if ($this->userHasRole('ROLE_ALLOW_ONBOARDING', $user)) {
            return $this->redirectToRoute('app_home', ['url' => 'onboarding']);
        }

        return $this->redirectToRoute('app_home', ['url' => '']);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function redirectToOnboarding(Account $user)
    {
        $db = $this->get('nosql.database.connection.provider')
            ->getConnection($user->getUid());

        $db->drop();
        $db->create();

        $em = $this->get('doctrine')
            ->getManager();

        $configurations = $em->getRepository(AccountConfiguration::class)
            ->findBy(['account' => $user]);

        foreach ($configurations as $conf) {
            $em->remove($conf);
        }

        $em->flush();

        return $this->redirect('/app/onboarding');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/app/{url}", name="app_home", requirements={"url" = ".*$"}, methods={"GET"}))
     * @Method({"GET"})
     */
    public function appAction($url)
    {
        /** @var Account $user */
        $user = $this->getUser();
        if (empty($user)) {
            return $this->redirectToRoute("security_login");
        }

        if ((empty($url)) && (is_null($user->getOnboardingDoneAt()))) {
            return $this->redirectToOnboarding($user);
        }

        return $this->render('FrontendBundle:web:index.html.php',[
            'frontend_php' => $this->get('kernel')->getRootDir() . '/../public/frontend.php',
            'username' => $user->getUsername()
        ]);
    }
}
