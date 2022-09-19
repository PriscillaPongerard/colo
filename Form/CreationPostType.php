<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CreationPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titreSujet', TextType::class)
            ->add('catePost')
            ->add('categorieMat')
            ->add('livre')
            ->add('illustrateur')
            ->add(
                'post',
                TextareaType::class,
                array('attr' => array('rows' => '5', 'cols' => '50','placeholder' => 'Ecrivez votre message...' ))
            )
           
          
            ->add('photoPost', FileType::class, [
                'label' => 'photoPost (PDF, PNG...)',
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
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
