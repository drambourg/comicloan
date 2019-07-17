<?php

namespace App\Controller;

use App\Repository\ComicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/search")
 */
class SearchComicController extends AbstractController
{
    /**
     * @Route("/comics", name="search_by_comic")
     */
    public function SearchByComic(ComicRepository $comicRepository)
    {
        return $this->render('search/search_by_comic.html.twig', [
            'controller_name' => 'SearchComicController',
        ]);
    }
}
