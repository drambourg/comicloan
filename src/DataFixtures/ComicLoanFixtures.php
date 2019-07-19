<?php

namespace App\DataFixtures;

use App\Entity\ComicLoan;
use Faker;
use App\Entity\UserLibrary;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ComicLoanFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($nUser = 0; $nUser <= UserFixtures::USER_COUNT; $nUser++) {
            $user = $this->getReference('user_' . $nUser);
            foreach ($user->getUserLibraries() as $userLibrary) {
                for ($nLoan = 0; $nLoan <= $faker->numberBetween(0, 3); $nLoan++) {
/*                    if ($faker->boolean()) {*/
                        $comicLoan = new ComicLoan();
                        $dateStartLoaning = $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now');
                        $comicLoan->setDateOut($dateStartLoaning);
                        $status = $faker->boolean();
                        $comicLoan->setStatus($status);
                        $comicLoan->setUserLibrary($userLibrary);
                        $comicLoan->setView($faker->boolean());
                        $userLoaner = $this->getReference('user_' . $nUser);
                        while ($userLoaner != $user) {
                            $userLoaner = $this->getReference('user_' . $faker->numberBetween($min = 0, $max = UserFixtures::USER_COUNT));
                        }
                        $comicLoan->setUserLoaner($userLoaner);
                        if ($status) {
                            $intervalDay = $faker->numberBetween($min = 2, $max = 25);
                            $dateEndLoaning = $faker->dateTimeInInterval($dateStartLoaning, $interval = '+ ' . $intervalDay . ' days', $timezone = null);
                            $comicLoan->setDateIn($dateEndLoaning);
                        };
                        $manager->persist($comicLoan);
                        $manager->flush();
                        $userLibrary->addComicLoan($comicLoan);
                        $manager->persist($userLibrary);
                        $manager->flush();
/*                    }*/
                }
            }
        }

    }

    public function getDependencies()
    {
        return array(
            UserLibraryFixtures::class,
        );
    }
}
