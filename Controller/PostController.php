<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Abonne;
use App\Entity\CategorieMateriel;
use App\Entity\Illustrateur;
use App\Entity\Post;
use App\Form\CreationPostType;
use App\Repository\PostRepository;
use App\Form\SearchForm;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PostController extends AbstractController
{
    /**
     * @Route("/listePost", name="listePost")
     */
    public function listePost(PostRepository $postRepository, Request $request)
    {

        $post = $postRepository->findAll();
        return $this->render('post/listePost.html.twig', [
            'post' => $post,

        ]);
    }

    /**
     * @Route("/creationPost", name="creationPost")
     */
    public function ajoutSujet(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();

        $registerForm = $this->createForm(CreationPostType::class, $post);
        $registerForm->handleRequest($request);

        $abonne = $this->getUser();

        $abonne = $entityManager->getRepository(Abonne::class)->find($abonne->getId());
        $post->setAbonne($abonne);
        //conditions pour la validation d'enregistrer un nouveau post
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            //pour récuperer la photo post
            /** @var UploadedFile $photoPost */
            $photoPostFile = $registerForm['photoPost']->getData();

            // condition pour que le logo soit traiter uniquement lors du telechargement
            if ($photoPostFile) {
                $originalFilename = pathinfo($photoPostFile->getClientOriginalName(), PATHINFO_FILENAME);

                //condition pour inclure en sécurité le nom dans l'url
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoPostFile->guessExtension();

                //déplace les avatar dans le fichier pour stocker
                try {
                    $photoPostFile->move(
                        $this->getParameter('photoPost_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                //met à jour la propriété 'couvertureLivreFile' pour stocker le nom du fichier
                $post->setPhotoPost($newFilename);
                $post->setDateCreation(new DateTime());
                //$createurPost = $this->getUser($abonne);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($post);
                $entityManager->flush();

                //message pour avertir que l'illustrateur a été enregistrer
                $this->addFlash(
                    'notice',
                    'Enregistrement validé '
                );

                // redirection vers la liste du post
                // $createurPost = $this->getUser(Abonne $username);
                return $this->redirect($this->generateUrl('listePost'));
            }
        }
        //redirection vers la creation du post si erreur dans la création

        return $this->render('post/creationPost.html.twig', [
            'post' => $post,
            'registerForm' => $registerForm->createView(),
        ]);
    }
  
    /**
     * @Route("/detailPost/{id}/", name="detailPost", methods={"GET","POST"})
     */
    public function detailPost($id, Request $request)
    {
        $postRepo = $this->getDoctrine()->getRepository(Post::class);
        $post = $postRepo->find($id);

        return $this->render('post/detailPost.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/reponsePost/{id}/", name="reponsePost", methods={"GET","POST"})
     */
    public function reponsePost($id, Request $request)
    {
        $postRepo = $this->getDoctrine()->getRepository(Post::class);
        $post = $postRepo->find($id);

        return $this->render('post/reponsePost.html.twig', [
            'post' => $post,
        ]);
    }


}
