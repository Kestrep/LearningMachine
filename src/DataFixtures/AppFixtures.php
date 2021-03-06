<?php

namespace App\DataFixtures;

use App\Entity\Card;
use App\Entity\Category;
use App\Entity\Subcategory;
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
        for ($i = 0; $i < 4; $i++) {
            $user = new User();

            $email = $i ===0 ? 'aa@aa.aa' : "user n{$i}";
            $user
                ->setEmail($email)
                ->setRoles(['ROLE_USER'])
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setRegisterAt($faker->dateTimeBetween('-4 week', '-1week'))
                ->setLastConnectionAt($faker->dateTimeBetween('-1 week', 'now'))
                ->setPassword($this->passwordEncoder->encodePassword($user, 'pass'));

            $datetime = new \DateTime();
            for ($j=0; $j < mt_rand(2,3); $j++) { 
                $category = new Category();

                $category
                    ->setName("cat°{$j} user°{$i}")
                    ->setDescription($faker->sentence(9))
                    ->setCreatedAt($faker->dateTimeBetween('-1 hour', 'now'))
                    ->setUpdatedAt($faker->dateTimeBetween('-1 hour', 'now'))
                    ;
                $user->addCategory($category);

                for ($k=0; $k < mt_rand(2, 3); $k++) { 
                    $subcategory = new Subcategory();

                    $subcategory
                        ->setName("subcat°{$k} cat°{$j} user°{$i}")
                        ->setDescription($faker->sentence(9))
                        ->setCreatedAt($faker->dateTimeBetween('-1 hour', 'now'))
                        ->setUpdatedAt($faker->dateTimeBetween('-1 hour', 'now'))
                        ;
                    $category->addSubcategory($subcategory);

                    for ($l = 0; $l < mt_rand(2, 4); $l++) {
                        $card = new Card();

                        $temp_date = $faker->dateTimeBetween('-1 week', 'now');
            
                        $card
                            ->setCreatedAt($temp_date)
                            ->setPlayAt($temp_date)
                            ->setFrontMainContent($faker->sentence(mt_rand(2, 3)))
                            ->setFrontSubcontent($faker->sentence(mt_rand(4, 7)))
                            ->setBackMainContent($faker->sentence(mt_rand(2, 3)))
                            ->setBackSubcontent($faker->sentence(mt_rand(4, 7)))
                            ->setFrontClue($faker->sentence(mt_rand(2, 3)))
                            ->setBackClue($faker->sentence(mt_rand(2, 3)))
                            ->setNote($faker->sentence(mt_rand(8, 12)))
                            ->setStage(mt_rand(0, 6));
                        
                        $subcategory->addCard($card);
                        $manager->persist($card);
                    }

                    $manager->persist($subcategory);
                }

                $manager->persist($category);
            }

            $manager->persist($user);
        }        

        $manager->flush();
    }
}