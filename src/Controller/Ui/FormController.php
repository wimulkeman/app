<?php

namespace App\Controller\Ui;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type as Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    #[Route('/_ui/form', name: '_ui_form')]
    public function index(): Response
    {
        $form = $this->createFormBuilder()
            ->add('text', Form\TextType::class)
            ->add('birthday', Form\BirthdayType::class)
            ->add('button', Form\ButtonType::class)
            ->add('checkbox', Form\CheckboxType::class)
            ->add('choice', Form\ChoiceType::class, [
                'choices' => ['Foo' => 'foo', 'bar' => 'bar']
            ])
            ->add('color', Form\ColorType::class)
            ->add('country', Form\CountryType::class)
            ->add('currency', Form\CurrencyType::class)
            ->getForm()
        ;

        return $this->render('ui/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
