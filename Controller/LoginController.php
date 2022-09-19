<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    //controller pour la page d'accueil avec la connection et la deconnexion.
    //Fonction pour la connection de l'abonné.
    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('login/login.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    //la fonction pour la déconnexion de l'abonné
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }
}