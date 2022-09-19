<?php

namespace App\DataFixtures;

use App\Entity\Illustrateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Faker;

class IllustrateurFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbIllustrateur = 1; $nbIllustrateur < 30000; $nbIllustrateur++) {
            $illustrateur = new Illustrateur();

            $illustrateur->setNomIllustrateur($faker->userName);
            $illustrateur->setPrenom($faker->firstName);
            $illustrateur->setLienSite($faker->url);
            $illustrateur->setLienFacebook($faker->url);
            $illustrateur->setLienInsta($faker->url);
            $illustrateur->setChaineYoutube($faker->url);
            $manager->persist($illustrateur);

            //enregistre l'illustrateur dans une réference pour l'utiliser dans les livres
            $this->addReference('illustrateur_' . $nbIllustrateur, $illustrateur);
        }
        $manager->flush();
    }
}
