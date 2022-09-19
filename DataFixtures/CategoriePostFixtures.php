<?php

namespace App\DataFixtures;

use App\Entity\CategoriePost;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoriePostFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $categoriePost = [
            1 => ['categorieSujet' => 'Tutoriel'],
            2 => ['categorieSujet' => 'Concours'],
            3 => ['categorieSujet' => 'Partage Mise en Couleurs'],
            4 => ['categorieSujet' => 'Freebie'],
            5 => ['categorieSujet' => 'Divers'],
        ];
        foreach ($categoriePost as $key => $value) {
            $categoriePost = new CategoriePost();
            $categoriePost->setCategorieSujet($value['categorieSujet']);

            $manager->persist($categoriePost);

            $this->addReference('categoriePost_' . $key, $categoriePost);
        }
        $manager->flush();
    }
}
