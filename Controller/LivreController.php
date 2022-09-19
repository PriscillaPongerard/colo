<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Data\SearchData;
use App\Form\SearchForm;
use App\Entity\Illustrateur;
use App\Form\AjoutLivreType;
use App\Entity\CategorieMateriel;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class LivreController extends AbstractController
{
    /**
     * @Route("/ajoutLivre", name="ajoutLivre")
     */
    public function ajoutLivre(Request $request): Response
    {
        $livre = new Livre();
        $registerForm = $this->createForm(AjoutLivreType::class, $livre);
        $registerForm->handleRequest($request);
        //conditions pour la validation d'enregistrer un nouveau livre
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            //pour récuperer la couverture du livre
            /** @var UploadedFile $couvertureLivreFile */
            $couvertureLivreFile = $registerForm['couvertureLivre']->getData();
            // condition pour que la couverture de livre soit traiter uniquement lors du telechargement
            if ($couvertureLivreFile) {
                $originalFilename = pathinfo($couvertureLivreFile->getClientOriginalName(), PATHINFO_FILENAME);

                //condition pour inclure en sécurité le nom dans l'url
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $couvertureLivreFile->guessExtension();

                //déplace les avatar dans le fichier pour stocker
                try {
                    $couvertureLivreFile->move(
                        $this->getParameter('couvertureLivre_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                //met à jour la propriété 'couvertureLivreFile' pour stocker le nom du fichier
                $livre->setCouvertureLivre($newFilename);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($livre);
                $entityManager->flush();

                //message pour avertir que le livre a été enregistrer
                $this->addFlash(
                    'notice',
                    'Enregistrement validé '
                );
                // redirection vers la liste des livre
                return $this->redirect($this->generateUrl('listeLivre'));
            }
        }
        //redirection vers la creation de l'illustrateur si erreur dans la création
        return $this->render('livre/ajoutLivre.html.twig', [
            'livre' => $livre,
            'registerForm' => $registerForm->createView(),
        ]);
    }


    /**
     * @Route("/listeLivre", name="listeLivre")
     */
    public function listeLivre(LivreRepository $livreRepository, Request $request)
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        $livre = $livreRepository->findSearch($data);
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('livre/listeLivreAjax.html.twig', ['livre' => $livre]),
                'sorting' => $this->renderView('livre/filtreLivre.html.twig', ['livre' => $livre]),
            ]);
        }
        return $this->render('livre/listeLivre.html.twig', [
            'livre' => $livre,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/listeLivre/{id}/", name="listeLivre.id", methods={"GET","POST"})
     */
    public function listeLivreId(Illustrateur $id, Request $request)
    {
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        $livre = $this->getDoctrine()->getRepository(Livre::class);
        $livre = $livre->findBy([
            'illustrateur' => $id
        ]);
        return $this->render('livre/listeLivreParIllustrateur.html.twig', [
            'livre' => $livre,
            'illustrateur' => $id,
            'form' => $form->createView()
        ]);
    }
}
