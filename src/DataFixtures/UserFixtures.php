<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    const USER_COUNT = 10;
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setPseudoname("xNousex");
        $user->setFirstName("Damien");
        $user->setLastName("Rambourg");
        $user->setEmail('d.2019@comic.com');
        $user->setAge(38);
        $user->setDateCreated(new \DateTime("2014-03-18"));
        $user->setCountry("France");
        $user->setCity("Orléans");
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'comic'
        ));
        $this->addReference('user_0', $user);
        $manager->persist($user);

        $faker = Faker\Factory::create('fr_FR');
        for ($nUser = 0; $nUser < self::USER_COUNT; $nUser++) {
            $user = new User();
            $user->setPseudoname($faker->userName);
            if ($faker->boolean) {
                $user->setFirstName($faker->firstName);
                $user->setLastName($faker->lastName);
            }
            $faker->boolean ?: $user->setAge($faker->numberBetween($min = 18, $max = 50));
            $user->setDateCreated($faker->dateTimeBetween($startDate = '-4 years', $endDate = 'now'));
            $user->setCity($faker->city);
            $user->setCountry("France");
            $user->setEmail($faker->email);
            $user->setRoles(['ROLE_AUTHOR']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'comic'
            ));
            $this->addReference('user_' . ($nUser + 1) , $user);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
