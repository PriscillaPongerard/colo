<?php

namespace App\DataFixtures;

use App\Entity\Livre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class LivreFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbLivres = 1; $nbLivres < 30000; $nbLivres++) {
            $illustrateur = $this->getReference('illustrateur_' . $faker->numberBetween(1,8));
            $categorieMateriel = $this->getReference('categorieMateriel_' . $faker->numberBetween(1, 13));
            $livre = new Livre();
            $livre->setIllustrateur($illustrateur);
            $livre->setCategorieMateriel($categorieMateriel);
            $livre->setTitreLivre($faker->title);
            $livre->setDateSorti($faker->dateTime);
            $livre->setLienPourAcheter($faker->url);
            $livre->setNbrPages($faker->randomDigit);
            $livre->setLienPresentation($faker->url);
            $manager->persist($livre);

            //enregistre le livre dans une réference
            $this->addReference('livre_' . $nbLivres, $livre);
        }
        $manager->flush();
    }
}
