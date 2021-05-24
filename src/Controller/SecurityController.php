<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    public static function getSubscribedServices()
    {
        return parent::getSubscribedServices() + [
            TranslatorInterface::class,
        ];
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $data = ['email' => $lastUsername];

        $form = $this->container->get('form.factory')
            ->createNamed('', LoginType::class, $data)
        ;

        if ($error) {
            $form->addError(new FormError(
                $this->get(TranslatorInterface::class)->trans($error->getMessageKey(), $error->getMessageData(), 'security'),
                $error->getMessageKey(),
                $error->getMessageData(),
            ));
        }

        return $this->render('security/login.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
