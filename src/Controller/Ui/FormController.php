<?php

namespace App\Controller\Ui;

use App\Form\TodoItemType;
use App\Form\TodoListType;
use App\Repository\TodoItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type as Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    #[Route('/_ui/form', name: '_ui_form')]
    public function index(): Response
    {
        $form = $this->createFormBuilder()
            ->add('fullname', Form\TextType::class)
            ->add('biography', Form\TextType::class, [
                'help' => 'Limited to 64 characters.'
            ])
            ->add('birthday', Form\BirthdayType::class, [
                'widget' => 'single_text',
            ])
            ->add('checkbox', Form\CheckboxType::class, [
                'label' => 'I accept to not be distributed',
            ])
            ->add('fav_pizza', Form\ChoiceType::class, [
                'choices' => ['4 cheeses' => 'foo']
            ])
            ->add('fav_color', Form\ColorType::class)
            ->add('country', Form\CountryType::class)
            ->add('currency', Form\CurrencyType::class)
            ->getForm()
        ;

        $form->get('fullname')->addError(new FormError('Please, say your name'));

        return $this->render('ui/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/_ui/form/collection', name: '_ui_form_collection')]
    public function collection(Request $request, TodoItemRepository $itemRepository): Response
    {
        $items = $itemRepository->findAllOrdered();

        $form = $this->createFormBuilder(['items' => $items])
            ->add('items', TodoListType::class)
            ->add('submit', Form\SubmitType::class)
            ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $itemRepository->save($items);

            return $this->redirectToRoute('_ui_form_collection');
        }

        return $this->render('ui/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
