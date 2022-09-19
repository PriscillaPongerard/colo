<?php

namespace App\DataFixtures;

use App\Entity\Materiel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class MaterielFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbMateriel = 1; $nbMateriel < 30000; $nbMateriel++) {
            $categorieMateriel = $this->getReference('categorieMateriel_' . $faker->numberBetween(1, 13));
            $materiel = new Materiel();
            $materiel->setCategorie($categorieMateriel);
            $materiel->setNomMateriel($faker->username);
            $materiel->setMarque($faker->name);
            $materiel->setLienAchat($faker->url);
            $materiel->setLienMarque($faker->url);
            $materiel->setLienPresentation($faker->url);
            $manager->persist($materiel);

            //enregistre le livre dans une réference
            $this->addReference('materiel_' . $nbMateriel, $materiel);
        }
        $manager->flush();
    }
}
