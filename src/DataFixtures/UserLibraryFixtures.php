<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Entity\UserLibrary;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserLibraryFixtures extends Fixture implements DependentFixtureInterface
{
    /*    const NUMBER_MAX_COMIC_API = 44547;*/
    const NUMBER_MAX_COMIC_API = 20;

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($nUser = 0; $nUser <= UserFixtures::USER_COUNT; $nUser++) {
            $user = $this->getReference('user_' . $nUser);
            $collectionComics = $faker->numberBetween($min = 5, $max = 20);
            $comicYetRequested = [];
            for ($nComic = 0; $nComic < $collectionComics; $nComic++) {
                $comicIdRequest = $faker->numberBetween($min = 2, $max = self::NUMBER_MAX_COMIC_API);
                if (!in_array($comicIdRequest, $comicYetRequested)) {
                    $comicYetRequested[] =$comicIdRequest;
                    $userLibrary = new UserLibrary();
                    $userLibrary->setUser($user);
                    $userLibrary->setComicId($comicIdRequest);
                    $userLibrary->setIsLoanable($faker->boolean);
                    $this->addReference('user_' . $nUser . '_comic_' . $comicIdRequest, $userLibrary);
                    $manager->persist($userLibrary);
                }
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
