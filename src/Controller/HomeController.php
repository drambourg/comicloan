<?php

namespace App\Controller;

use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index( CharacterRepository $characterRepository)
    {
/*        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'http://gateway.marvel.com/v1/public/characters?ts=1&apikey=85ece34f8c90772089957a5181827057&hash=f79ec6031ce76572ec96e17f5e821fe7');
dd($response->toArray());*/

        $characters = $characterRepository->findallCharactersByLimit();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'characters' => $characters,
        ]);
    }
}
