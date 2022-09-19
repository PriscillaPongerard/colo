<?php

namespace App\Controller;

use DateTime;
use App\Entity\Abonne;
use App\Form\InscriptionAbonneType;
use App\Form\ModifProfilAbonneType;
use App\Repository\AbonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class AbonneController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserPasswordEncoderInterface $encoder, TranslatorInterface $translator, AbonneRepository $abonneRepository)
    {
        $user = new Abonne();
        //creer formulaire
        $registerForm = $this->createForm(InscriptionAbonneType::class, $user);
        //recuperer les donne envoye au form
        $registerForm->handleRequest($request);
        //les conditions pour la validation du nouveau abonne
        //si le form est valide et correspond 

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {

            //permet de verifier l'email et pseudo pour rediriger vers la page de connexion
            $data = $registerForm->getData();
            $qb = $abonneRepository->createQueryBuilder('a');
            $abonne = $qb->where(
                $qb->expr()->orx(
                    $qb->expr()->eq('a.username', ':username'),
                    $qb->expr()->eq('a.email', ':email'),
                )
            )
                ->setParameter('username', $data->getUsername())
                ->setParameter('email', $data->getEmail())
                ->getQuery()
                ->getResult();
            $message = $translator->trans('The email or the nickname already exist');
            if ($abonne) {
                $this->addFlash('danger', $message);
                return $this->redirectToRoute('login');
            };

            //hasher le mot de passe avant l'envoi dans la bdd
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            //modif l'objet abonne en donnant nouveau mdp hashed
            $user->setPassword($hashed);
            //pour récupérer donnée avatar 
            $avatarFile = $registerForm['avatar']->getData();
            // condition pour que l'avatar soit traiter uniquement lors du telechargement

            if ($avatarFile) {
                //recuperer et donne le nom du fichier avatar
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                //condition pour inclure en sécurité le nom dans l'url
                //reprend le nom pour que tous soit enregistrer le nom pour pas qu'il y est de code dedans
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                // on reprend le nom securisé + id unique + l'extension d'originie
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();
                //déplace les avatar dans le fichier pour stocker

                try {
                    //on enregistre le fichier dans le dossier defini dans services.yaml 
                    $avatarFile->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (Exception $e) {
                    // return $e->getMessage("le format ne correspond pas");
                }
                //met à jour la propriété 'avatarFilename' pour stocker le nom du fichier
                $user->setAvatar($newFilename);
            }
            //met à jour la date d'inscription
            $user->setDateInscription(new DateTime());

            //prepare l'objet avant et l'envoie en bdd
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            //creer un nouveau token de mdp pour le user et le stock en session pour connecter l'utilisateur auto apres inscription
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->container->get('security.token_storage')->setToken($token);
            $this->container->get('session')->set('security_main', serialize($token));
            //message pour avertir que l'enregistrement de l'abonné
            $this->addFlash(
                'success',
                'Bienvenue, maintenant vous avez accès au contenu. '
            );
            // redirection vers la liste des participants
            return $this->redirect($this->generateUrl('listeIllustrateur'));
        }

        //redirection vers la creation de l'abonné si erreur dans la création
        return $this->render('abonne/inscription.html.twig', [
            'user' => $user,
            'registerForm' => $registerForm->createView(),
        ]);
    }

    /**
     * 
     *@Route("/monProfil",name="monProfil", methods={"GET"})
     */
    public function monProfil()
    {
        $profil = $this->getUser();
        return $this->render("abonne/monProfil.html.twig", [
            'profil' => $profil
        ]);
    }


    /**
     * @Route("/modificationProfil/{id}/", name="modificationProfil", methods={"GET","POST"})
     */
    public function modificationProfil(Request $request, Abonne $abonne, UserPasswordEncoderInterface $encoder, SluggerInterface $slugger, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $modifProfilForm = $this->createForm(ModifProfilAbonneType::class, $abonne);
        $modifProfilForm->handleRequest($request);

        if ($modifProfilForm->isSubmitted() && $modifProfilForm->isValid()) {
            //hasher le mot de passe avant l'envoi dans la bdd
            $hashed = $encoder->encodePassword($abonne, $abonne->getPassword());
            //modif l'objet abonne en donnant nouveau mdp hashed
            $abonne->setPassword($hashed);

            //pour récupérer donnée avatar 
            $avatarFile = $modifProfilForm->get('avatar')->getData();

            // condition pour que l'avatar soit traiter uniquement lors du telechargement
            // si l'avatar est true on rentre dedans 
            if ($avatarFile) {

                //recuperer et donne le nom du fichier avatar
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                //condition pour inclure en sécurité le nom dans l'url
                //reprend le nom pour que tous soit enregistrer le nom pour pas qu'il y est de code dedans
                $safeFilename = $slugger->slug($originalFilename);
                // on reprend le nom securisé + id unique + l'extension d'originie
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();

                //partie pour supprimer l'ancien avatar lors de la modif
                if (!empty($avatarFile)) {
                    $avatar = $abonne->getAvatar();
                    if ($avatar) {
                        //on va chercher les images
                        $avatarModif = $this->getParameter("avatar_directory") . '/' . $abonne->getAvatar();
                        // on verifie l'existance de l'image
                        if (file_exists($avatarModif)) {
                            //on supprime l'avatar
                            unlink($avatarModif);
                        }
                    }
                    //déplace les avatar dans le fichier pour stocker
                    try {
                        //on enregistre le fichier dans le dossier defini dans services.yaml 
                        $avatarFile->move(
                            $this->getParameter("avatar_directory"),
                            $newFilename
                        );
                    } catch (Exception $e) {
                        return $e->getMessage("erreur d'enregistrement");
                    }
                    //met à jour la propriété 'avatarFilename' pour stocker le nom du fichier
                    $abonne->setAvatar($newFilename);
                }
            }
            //prepare l'objet avant et l'envoie en bdd
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($abonne);
            $entityManager->flush();

            //creer un nouveau token de mdp pour le user et le stock en session pour connecter l'utilisateur auto apres inscription
            $token = new UsernamePasswordToken($abonne, null, 'main', $abonne->getRoles());
            $this->container->get('security.token_storage')->setToken($token);
            $this->container->get('session')->set('security_main', serialize($token));

            //message pour avertir que l'enregistrement de l'abonné
            $this->addFlash(
                'succes',
                'Vos changements ont été pris en compte. '
            );

            return $this->redirectToRoute('monProfil');
        }


        return $this->render('abonne/modificationProfil.html.twig', [
            'abonne' => $abonne,
            'modifProfilForm' => $modifProfilForm->createView(),
        ]);
    }

    /**
     * @Route("/supprimerProfil/{id}", name="supprimerProfil", methods={"DELETE"})
     */
    public function delete(Request $request, Abonne $abonne): Response
    {
        $session = $this->get('session');
        $session = new Session();
        $session->invalidate();
        if ($this->isCsrfTokenValid('delete' . $abonne->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($abonne);
            $entityManager->flush();

            $avatar = $abonne->getAvatar();
            if ($avatar) {
                //on récupère l'image dans uploads
                //on génère le chemin physique de l'image
                $avatar = $this->getParameter("avatar_directory") . "/" . $abonne->getAvatar();
                //on vérifie qu'une image existe, si oui on la supprime car elle n'a plus d'usage
                //si $newFilename nouvel avatar donc on supprime l'ancien dans uploads
                if (file_exists($avatar)) {
                    unlink($avatar);
                }
            }
        }
        return $this->redirectToRoute('accueil');
    }
}
