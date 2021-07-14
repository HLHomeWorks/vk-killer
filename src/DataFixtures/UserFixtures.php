<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
     private $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
     public function __construct(UserPasswordHasherInterface $passwordHasher)
     {
         $this->passwordHasher = $passwordHasher;
     }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setEmail('user@example.com')
            ->setRoles(['ROLE_USER'])
            ->setSex(1)
            ->setAbout('обо мне')
            ->setBirthdate(new DateTime('2014-01-18'))
            ->setCity(99)
            ->setFirstName('Иван')
            ->setSecondName('Иванов');

        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            '123123'
        ));

        $manager->persist($user);
        $manager->flush();
    }
}
