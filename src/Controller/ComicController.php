<?php

namespace App\Controller;

use App\Repository\ComicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/comics")
 */

class ComicController extends AbstractController
{
    /**
     * @Route("/", name="comic_index")
     */
    public function index(ComicRepository $comicRepository)
    {
        $comics = $comicRepository->findAllComics();
        return $this->render('comic/index.html.twig', [
            'controller_name' => 'ComicController',
            'comics' => $comics,
        ]);
    }
}
