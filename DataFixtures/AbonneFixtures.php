<?php

namespace App\DataFixtures;

use App\Entity\Abonne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class AbonneFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbAbonne = 1; $nbAbonne < 30000; $nbAbonne++) {
            $abonne = new Abonne();
            if($nbAbonne === 1)
            $abonne->setAdministrateur(['ROLE_ADMIN']);
            else
            $abonne->setAdministrateur(['ROLE_USER']);
            $abonne->setEmail($faker->email);
            $abonne->setUsername($faker->userName);
            $abonne->setNom($faker->lastName);
            $abonne->setPrenom($faker->firstName);
            $abonne->setCodePostal($faker->numberBetween(01000, 97500));
            $abonne->setVille($faker->city);
            $abonne->setPassword($this->encoder->encodePassword($abonne, 'azerty'));
            $manager->persist($abonne);

            //reférence
            $this->addReference('abonne_'. $nbAbonne, $abonne);
        }
        $manager->flush();
    }
}
