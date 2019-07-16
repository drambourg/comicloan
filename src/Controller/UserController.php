<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/library", name="user_library")
     */
    public function showLibrary(UserLibraryRepository $userLibraryRepository)
    {
        $comics = $userLibraryRepository->findByUser();
        return $this->render('user/library.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
