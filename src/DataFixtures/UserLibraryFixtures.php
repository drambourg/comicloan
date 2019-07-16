<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Entity\UserLibrary;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserLibraryFixtures extends Fixture implements DependentFixtureInterface
{
    const NUMBER_MAX_COMIC_API = 44547;

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($nUser = 0; $nUser <= UserFixtures::USER_COUNT; $nUser++) {
            $user = $this->getReference('user_' . $nUser);
            $collectionComics = $faker->numberBetween($min = 5, $max = 25);
            for ($nComic = 0; $nComic < $collectionComics; $nComic++) {
                $userLibrary = new UserLibrary();
                $userLibrary->setUser($user);
                $userLibrary->setComicId($faker->numberBetween($min = 2, $max = self::NUMBER_MAX_COMIC_API));
                $userLibrary->setLoanable($faker->boolean);
                $manager->persist($userLibrary);
            }
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
