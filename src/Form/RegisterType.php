<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Usuario',
                'attr' => ['placeholder' => 'Nombre de usuario'],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'attr' => ['placeholder' => 'ejemplo@correo.com'],
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Las contraseñas deben coincidir.',
                'first_options' => ['label' => 'Contraseña', 'attr' => ['placeholder' => 'Contraseña']],
                'second_options' => ['label' => 'Repite la contraseña', 'attr' => ['placeholder' =>
                    'Repite la contraseña']],
                'required' => true,
            ])
            ->add('birthDate', DateType::class, [
                'label' => 'Fecha de nacimiento',
                'widget' => 'single_text', // input tipo date
                'required' => true
            ])
            ->add('register', SubmitType::class, [
                'label' => 'Registrarse',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
