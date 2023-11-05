<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\User\UserInterface;


class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager):void
    {
         $admin = new User();
         $admin->setEmail('toto@gmx.fr')
                ->setPlainPassword('password')
                ->setRoles(['ROLE_USER','ROLE_ADMIN'])
                ->setIsVerified(true)
                ->setConfirmed(false);
                $manager->persist($admin);
        for($i=0;$i<4;$i++){
            $user = new User();
            $user->setEmail($this->faker->email())
                 ->setPlainPassword('password')
                 ->setRoles(['ROLE_USER'])
                 ->setIsVerified(true)
                 ->setConfirmed(false);
                 $manager->persist($user);
        }
        $manager->flush();    
    }
}
