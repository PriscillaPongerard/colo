<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Forms;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;


class LangueController extends AbstractController
{
    /**
     * @Route("/change_locale/{locale}", name="change_locale")
     */
    public function changeLocale($locale, Request $request)
    {
        // On stocke la langue dans la session
        $request->getSession()->set('_locale', $locale);

        // On revient sur la page précédente
        return $this->redirect($request->headers->get('referer'));
    }

    public function messageError($translator)
    {


        $vendorDirectory = realpath(__DIR__ . '/../vendor');
        $vendorFormDirectory = $vendorDirectory . '/symfony/form';
        $vendorValidatorDirectory = $vendorDirectory . '/symfony/validator';

        // creates the validator - details will vary
        $validator = Validation::createValidator();

        // there are built-in translations for the core error messages
        $translator->addResource(
            'xlf',
            $vendorFormDirectory . '/Resources/translations/validators.en.xlf',
            'en',
            'validators'
        );
        $translator->addResource(
            'xlf',
            $vendorValidatorDirectory . '/Resources/translations/validators.en.xlf',
            'en',
            'validators'
        );

        $formFactory = Forms::createFormFactoryBuilder()
            // ...
            ->addExtension(new ValidatorExtension($validator))
            ->getFormFactory();
    }

    public function new(Request $request)
    {

        $form = $this->createFormBuilder()
            ->add('task', TextType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('dueDate', DateType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Type(\DateTime::class),
                ]
            ])
            ->getForm();
        // ...
    }
}
