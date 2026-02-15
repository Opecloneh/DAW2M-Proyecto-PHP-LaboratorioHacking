<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_username', TextType::class, [
                'label' => 'Usuario',
                'attr' => [
                    'placeholder' => 'Nombre de usuario',
                    'autofocus' => true,
                ],
                'required' => true,
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'Contraseña',
                'attr' => [
                    'placeholder' => 'Contraseña',
                ],
                'required' => true,
            ]);
    }
}
