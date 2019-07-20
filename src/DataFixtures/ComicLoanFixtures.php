<?php

namespace App\DataFixtures;

use App\Entity\ComicLoan;
use Faker;
use App\Entity\UserLibrary;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\Date;

class ComicLoanFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($nUser = 0; $nUser <= UserFixtures::USER_COUNT; $nUser++) {
            $user = $this->getReference('user_' . $nUser);
            foreach ($user->getUserLibraries() as $userLibrary) {
                $numLoaner = $faker->numberBetween(0, 6);
                $dateToNextLoan = $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now');
                for ($nLoan = 0; $nLoan <= $numLoaner; $nLoan++) {
                    $comicLoan = new ComicLoan();
                    $dateStartLoaning = $faker->dateTimeBetween( $dateToNextLoan , $endDate = 'now');
                    $dateToNextLoan = $dateStartLoaning;
                    $comicLoan->setDateOut($dateStartLoaning);
                    $comicLoan->setUserLibrary($userLibrary);
                    $comicLoan->setView($faker->boolean());
                    $userLoaner = $this->getReference('user_' . $nUser);
                    while ($userLoaner == $user) {
                        $userLoaner = $this->getReference('user_' . $faker->numberBetween($min = 0, $max = UserFixtures::USER_COUNT));
                    }
                    $comicLoan->setUserLoaner($userLoaner);
                    $nLoan == $numLoaner? $status = $faker->boolean() : $status = true;
                    $intervalDay = $faker->numberBetween($min = 2, $max = 25);
                    $dateEndLoaning = $faker->dateTimeInInterval($dateStartLoaning, $interval = '+ ' . $intervalDay . ' days', $timezone = null);
                    $dateToNextLoan = $dateEndLoaning;
                    if ($dateEndLoaning < new \DateTime()) {
                        $comicLoan->setStatus(true);
                        $comicLoan->setDateIn($dateEndLoaning);
                    } else {
                        $comicLoan->setStatus(false);
                        $nLoan = $numLoaner;
                    }
                    $manager->persist($comicLoan);
                    $manager->flush();
                    $userLibrary->addComicLoan($comicLoan);
                    $manager->persist($userLibrary);
                    $manager->flush();
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
