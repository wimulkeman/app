<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Profile\ChangePasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class, [
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'is_granted' => 'ROLE_ADMIN',
            ])
            ->add('preferredLocale', LocaleType::class, [
                'required' => false,
                'placeholder' => 'No preferred locale',
            ])
            ->add('preferredTimezone', TimezoneType::class, [
                'required' => false,
                'placeholder' => 'No preferred timezone',
            ])
            ->add('changePassword', ChangePasswordType::class, [
                'inherit_data' => true,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
