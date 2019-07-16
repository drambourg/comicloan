<?php

namespace App\Controller;

use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index( CharacterRepository $characterRepository, SessionInterface $session, Security $security)
    {
        $session->set('user',$security->getUser());

        $characters = $characterRepository->findallCharacters();
        return $this->render('home/index.html.twig', [
            'characters' => $characters,
        ]);
    }
}
