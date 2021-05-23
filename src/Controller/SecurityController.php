<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
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

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $data = ['email' => $lastUsername];
        $options = [
            'csrf_token_id' => 'authenticate',
        ];

        /** @var Form $form */
        $form = $this->container->get('form.factory')
            ->createNamedBuilder('', FormType::class, $data, $options)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('_remember_me', CheckboxType::class)
            ->add('submit', SubmitType::class)
            ->getForm()
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
