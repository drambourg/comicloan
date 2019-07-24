<?php

namespace App\DataFixtures;

use App\Entity\UserRate;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserRateFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($nUser = 0; $nUser <= UserFixtures::USER_COUNT; $nUser++) {
            $user = $this->getReference('user_' . $nUser);

            for ($nUserRater = 0; $nUserRater <= UserFixtures::USER_COUNT; $nUserRater++) {
                $userRater = $this->getReference('user_' . $nUserRater);
                if ($nUserRater != $nUser && $faker->boolean(70)) {
                    $userRate = new UserRate();
                    $userRate->setUser($user);
                    $userRate->setAuthor($userRater);
                    $userRate->setComment($faker->realText($maxNbChars = 200, $indexSize = 2));
                    $userRate->setRate($faker->numberBetween($min = 1, $max = 5));
                    $userRate->setDateAt($faker->dateTimeBetween($userRater->getDateCreated(), $endDate = 'now'));
                    $manager->persist($userRate);
                    $manager->flush();
                }
            }
        }
    }


    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }

}
