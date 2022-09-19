<?php

namespace App\Form;

use App\Entity\Illustrateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AjoutIllustrateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomIllustrateur', TextType::class)
            ->add('prenom', TextType::class)
            ->add('lienSite', UrlType::class)
            ->add('lienFacebook', UrlType::class)
            ->add('lienInsta', UrlType::class)
            ->add('chaineYoutube')
            ->add('logo', FileType::class, [
                'label' => 'Logo (PDF, PNG...)',
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
            'data_class' => Illustrateur::class,
        ]);
    }
}
