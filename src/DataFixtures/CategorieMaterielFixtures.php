<?php

namespace App\DataFixtures;

use App\Entity\CategorieMateriel;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategorieMaterielFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $categorieMateriel = [
            1 => ['nomCategorie' => 'Crayons de Couleur'],
            2 => ['nomCategorie' => 'Crayons Aquarellable'],
            3 => ['nomCategorie' => 'Feutres à Eau'],
            4 => ['nomCategorie' => 'Feutres à Alcool'],
            5 => ['nomCategorie' => 'Pastel à Sec'],
            6 => ['nomCategorie' => 'Pastel à l\'Huile'],
            7 => ['nomCategorie' => 'Craies Aquarellables'],
            8 => ['nomCategorie' => 'Peintures Acryliques'],
            9 => ['nomCategorie' => 'Aquarelle en Godets'],
            10 => ['nomCategorie' => 'Petit Matériel important'],
            11 => ['nomCategorie' => 'Livres'],
            12 => ['nomCategorie' => 'PDF'],
            13 => ['nomCategorie' => 'Autres'],
        ];
        foreach ($categorieMateriel as $key => $value) {
            $categorieMateriel = new CategorieMateriel();
            $categorieMateriel->setNomCategorie($value['nomCategorie']);

            $manager->persist($categorieMateriel);

            //enregistrement référence
            $this->addReference('categorieMateriel_' . $key, $categorieMateriel);
        }
        $manager->flush();
    }
}
