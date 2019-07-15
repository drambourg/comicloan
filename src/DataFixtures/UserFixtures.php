<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Date;

class UserFixtures extends Fixture
{
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
        $user->setAge(38);
        $user->setAvatarPicture("https://i.pinimg.com/originals/7c/93/3d/7c933d493c16a06ad2110aca6caec4b4.gif");
        $user->setDateCreated(new \DateTime("2014-03-18"));
        $user->setCity("Orléans");
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'comic'
        ));
        $manager->persist($user);

        $faker = Faker\Factory::create('fr_FR');
        for ($nUser = 0; $nUser < 10; $nUser++) {
            $user = new User();
            $user->setPseudoname($faker->userName);
            if ($faker->boolean) {
                $user->setFirstName($faker->firstName);
                $user->setLastName($faker->lastName);
            }
            $faker->boolean ?: $user->setAge($faker->numberBetween($min = 18, $max = 50));
            $faker->boolean ?: $user->setAvatarPicture($faker->imageUrl($width = 200, $height = 200));
            $user->setDateCreated($faker->dateTimeBetween($startDate = '-4 years', $endDate = 'now'));
            $user->setCity($faker->city);
            $user->setRoles(['ROLE_AUTHOR']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'comic'
            ));
            $manager->persist($user);
        }
        $manager->flush();
    }
}
