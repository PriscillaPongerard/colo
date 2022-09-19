<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchForm;
use App\Entity\Illustrateur;
use App\Form\AjoutIllustrateurType;
use App\Repository\IllustrateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class IllustrateurController extends AbstractController
{
    /**
     * @Route("/ajoutIllustrateur", name="ajoutIllustrateur")
     */
    public function ajoutIllustrateur(Request $request, TranslatorInterface $translator): Response
    {
        $illustrateur = new Illustrateur();
        $registerForm = $this->createForm(AjoutIllustrateurType::class, $illustrateur);
        $registerForm->handleRequest($request);

        //conditions pour la validation d'enregistrer un nouveau illustrateur
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            //pour récuperer le logo de l'illustrateur
            /** @var UploadedFile $logoFile */
            $logoFile = $registerForm['logo']->getData();

            // condition pour que le logo soit traiter uniquement lors du telechargement
            if ($logoFile) {
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);

                //condition pour inclure en sécurité le nom dans l'url
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();

                //déplace les avatar dans le fichier pour stocker
                try {
                    $logoFile->move(
                        $this->getParameter('logo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                //met à jour la propriété 'couvertureLivreFile' pour stocker le nom du fichier
                $illustrateur->setLogo($newFilename);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($illustrateur);
                $entityManager->flush();

                //message pour avertir que l'illustrateur a été enregistrer
                $this->addFlash(
                    'notice',
                    'Enregistrement validé '
                );

                // redirection vers la liste des illustrateur
                return $this->redirect($this->generateUrl('listeIllustrateur'));
            }
        }
        //redirection vers la creation de l'illustrateur si erreur dans la création
        return $this->render('illustrateur/ajoutIllustrateur.html.twig', [
            'illustrateur' => $illustrateur,
            'registerForm' => $registerForm->createView(),
        ]);
    }

    /**
     * @Route("/listeIllustrateur", name="listeIllustrateur")
     */
    public function listeIllustrateur(IllustrateurRepository $illustrateurRepository, Request $request)
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        $illustrateur = $illustrateurRepository->findSearch($data);
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('illustrateur/listeIllustrateurAjax.html.twig', ['illustrateur' => $illustrateur]),
                'sorting' => $this->renderView('illustrateur/filtreIllustrateur.html.twig', ['illustrateur' => $illustrateur]),
            ]);
        }
        return $this->render('illustrateur/listeIllustrateur.html.twig', [
            'illustrateur' => $illustrateur,
            'form' => $form->createView()
        ]);
    }
}
