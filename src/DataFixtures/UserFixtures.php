<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail("admin@invsys.com");
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'admin'
        ));
        $manager->persist($admin);
        for ($count = 1; $count <= 20; $count++) {
            $user = new User();
            $user->setEmail('user' . $count . '@invsys.com');
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'password'
            ));
            $manager->persist($user);
        }
        $manager->flush();
    }
}
