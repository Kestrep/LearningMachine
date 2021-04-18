<?php

namespace App\DataFixtures;

use App\Entity\Card;
use App\Entity\Category;
use App\Entity\SubCategory;
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

        // Génération des utilisateurs
        for ($i = 0; $i < 30; $i++) {
            $user = new User();

            $user
                ->setEmail($faker->email())
                ->setRoles(['ROLE_USER'])
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setRegisterAt($faker->dateTimeBetween('-4 week', '-1week'))
                ->setLastConnectionAt($faker->dateTimeBetween('-1 week', 'now'))
                ->setPassword($this->passwordEncoder->encodePassword($user, 'pass'));

            for ($j=0; $j < mt_rand(1,5); $j++) { 
                $category = new Category();

                $category->setName($faker->word());
                $user->addCategory($category);

                for ($k=0; $k < mt_rand(1, 3); $k++) { 
                    $subCategory = new SubCategory();

                    $subCategory->setName($faker->word());
                    $category->addSubCategory($subCategory);

                    for ($l = 0; $l < 30; $l++) {
                        $card = new Card();
            
                        $card
                            ->setCreatedAt($faker->dateTimeBetween('-1 week', 'now'))
                            ->setFrontMainContent($faker->sentence(mt_rand(2, 3)))
                            ->setFrontSubcontent($faker->sentence(mt_rand(4, 7)))
                            ->setBackMainContent($faker->sentence(mt_rand(2, 3)))
                            ->setBackSubcontent($faker->sentence(mt_rand(4, 7)))
                            ->setFrontClue($faker->sentence(mt_rand(2, 3)))
                            ->setBackClue($faker->sentence(mt_rand(2, 3)))
                            ->setNote($faker->sentence(mt_rand(8, 12)))
                            ->setStage(mt_rand(0, 7));
                        
                        $subCategory->addCard($card);
                        $manager->persist($card);
                    }

                    $manager->persist($subCategory);
                }

                $manager->persist($category);
            }

            $manager->persist($user);
        }        

        $manager->flush();
    }
}
