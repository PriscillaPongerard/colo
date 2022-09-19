<?php

/** @noinspection PhpUndefinedConstantInspection */

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class InscriptionAbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('codePostal')
            ->add('ville', TextType::class)
            ->add("password", RepeatedType::class, [
                "required" => false,
                "type" => PasswordType::class,
                "invalid_message" => "Les mots de passe doivent être identiques",
                "first_options"  => [
                    "label" => "Password",
                ],
                "second_options" => [
                    "label" => "Repeat Password",
                ],
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar (PDF, PNG...)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '9900k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/gif',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Merci de téléchargé un fichier valide en format Jpeg, PNG ou GIF',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
