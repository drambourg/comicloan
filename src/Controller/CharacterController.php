<?php

namespace App\Controller;

use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/characters")
 */

class CharacterController extends AbstractController
{
    /**
     * @Route("/", name="character_index")
     */
    public function index(CharacterRepository $characterRepository)
    {        $characters = $characterRepository->findallCharacters();

        return $this->render('character/index.html.twig', [
            'controller_name' => 'HomeController',
            'characters' => $characters,
        ]);
    }
}
