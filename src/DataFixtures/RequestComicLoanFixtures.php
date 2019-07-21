<?php

namespace App\DataFixtures;

use App\Entity\RequestComicLoan;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RequestComicLoanFixtures extends Fixture implements DependentFixtureInterface
{
    const NUMBER_MAX_COMIC_API = 20;
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($nUser = 0; $nUser <= UserFixtures::USER_COUNT; $nUser++) {

            $user = $this->getReference('user_' . $nUser);
            $comicRequests = $faker->numberBetween($min = 10, $max = 30);
            $comicYetRequested =[];
            for ($nComic = 0; $nComic < $comicRequests; $nComic++) {
                $comicIdRequest = $faker->numberBetween($min = 2, $max = self::NUMBER_MAX_COMIC_API);
                if (!in_array($comicIdRequest, $comicYetRequested) &&
                    !$this->hasReference('user_'.$nUser . '_comic_' . $comicIdRequest)) {
                    $comicYetRequested[] = $comicIdRequest;
                    $comicRequest = new RequestComicLoan();
                    $comicRequest->setUser($user);
                    $comicRequest->setComicId($comicIdRequest);
                    $comicRequest->setDateAt($faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'));
                    if($faker->boolean(70)){
                        $comicRequest->setMessage($faker->realText($maxNbChars = 200, $indexSize = 2));
                    }
                    $comicRequest->setStatus($faker->boolean());
                    $manager->persist($comicRequest);
                    $manager->flush();
                };
            }
        }
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            UserLibraryFixtures::class,
        );
    }
}
