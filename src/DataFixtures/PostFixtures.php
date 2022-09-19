<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Faker;

class PostFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbPost = 1; $nbPost < 30000; $nbPost++) {
            $abonne = $this->getReference('abonne_' . $faker->numberBetween(1, 8));
            $categoriePost = $this->getReference('categoriePost_' . $faker->numberBetween(1, 5));
            $illustrateur = $this->getReference('illustrateur_' . $faker->numberBetween(1, 8));
            $categorieMateriel = $this->getReference('categorieMateriel_' . $faker->numberBetween(1, 13));
            $livre = $this->getReference('livre_' . $faker->numberBetween(1, 8));
            $post = new Post();
            $post->setAbonne($abonne);
            $post->setCategorieMat($categorieMateriel);
            $post->setCatePost($categoriePost);
            $post->setIllustrateur($illustrateur);
            $post->setLivre($livre);
            $post->setTitreSujet($faker->title);
            $post->setDateCreation($faker->dateTime);
            $post->setPost($faker->text);

            $manager->persist($post);

            //enregistre l'illustrateur dans une réference pour l'utiliser dans les livres
            $this->addReference('post_' . $nbPost, $post);
        }
        $manager->flush();
    }
}
