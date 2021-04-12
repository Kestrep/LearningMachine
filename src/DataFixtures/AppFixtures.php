<?php

namespace App\DataFixtures;

use App\Entity\Card;
use App\Entity\User;
use Faker\Factory;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
        for ($i=0; $i < 30; $i++) { 
            $card = new Card();

            $card
                ->setCreatedAt($faker->dateTimeBetween('-1 week', 'now'))
            
                ->setFrontMainContent($faker->sentence(mt_rand(2,3)))
                ->setFrontSubcontent($faker->sentence(mt_rand(4,7)))

                ->setBackMainContent($faker->sentence(mt_rand(2,3)))
                ->setBackSubcontent($faker->sentence(mt_rand(4,7)))
                
                ->setFrontClue($faker->sentence(mt_rand(2,3)))
                ->setBackClue($faker->sentence(mt_rand(2,3)))

                ->setNote($faker->sentence(mt_rand(8,12)))
                ->setStage(mt_rand(0,7));

            $manager->persist($card);
        }

        for ($i=0; $i < 30; $i++) { 
            $user = new User();

            $user
                ->setEmail($faker->email())
                ->setRoles(['ROLE_USER'])
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setRegisterAt($faker->dateTimeBetween('-4 week', '-1week'))
                ->setLastConnectionAt($faker->dateTimeBetween('-1 week', 'now')) 
                ->setPassword($this->passwordEncoder->encodePassword($user, 'pass'))
                ;

            $manager->persist($user);
        }

        $manager->flush();
    }
}





