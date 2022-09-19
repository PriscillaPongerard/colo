<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Materiel;
use App\Form\SearchForm;
use App\Form\AjoutMaterielType;
use App\Repository\MaterielRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class MaterielController extends AbstractController
{
    /**
     * @Route("/ajoutMateriel", name="ajoutMateriel")
     */
    public function ajoutMateriel(Request $request): Response
    {
        $materiel = new Materiel();
        $registerForm = $this->createForm(AjoutMaterielType::class, $materiel);
        $registerForm->handleRequest($request);
        //conditions pour la validation d'enregistrer du nouveau materiel
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            //pour récuperer la photo du materiel
            /** @var UploadedFile $photoMaterielFile */
            $photoMaterielFile = $registerForm['photoMateriel']->getData();
            // condition pour que la photo soit traiter uniquement lors du telechargement
            if ($photoMaterielFile) {
                $originalFilename = pathinfo($photoMaterielFile->getClientOriginalName(), PATHINFO_FILENAME);

                //condition pour inclure en sécurité le nom dans l'url
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoMaterielFile->guessExtension();

                //déplace les photoMateriel dans le fichier pour stocker
                try {
                    $photoMaterielFile->move(
                        $this->getParameter('photoMateriel_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {  }
                //met à jour la propriété 'couvertureLivreFile' pour stocker le nom du fichier
                $materiel->setPhotoMateriel($newFilename);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($materiel);
                $entityManager->flush();

                //message pour avertir que le livre a été enregistrer
                $this->addFlash(
                    'notice',
                    'Enregistrement validé ');
                // redirection vers la liste du materiel
                return $this->redirect($this->generateUrl('listeMateriel'));
            }
        }
        //redirection vers l'ajout de matériel si erreur dans la création
        return $this->render('materiel/ajoutMateriel.html.twig', [
            'materiel' => $materiel,
            'registerForm' => $registerForm->createView(),
        ]);
    }

    /**
     * @Route("/listeMateriel", name="listeMateriel")
     */
    public function listeMateriel(MaterielRepository $materielRepository, Request $request)
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        $materiel = $materielRepository->findSearch($data);
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('materiel/listeMaterielAjax.html.twig', ['materiel' => $materiel]),
                'sorting' => $this->renderView('materiel/filtreMateriel.html.twig', ['materiel' => $materiel]),
            ]);
        }
        return $this->render('materiel/listeMateriel.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView()
        ]);
    }
}
