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
        $criteria['orderBy']='title';
        if (isset($_GET['search'])) {
            $criteria['titleStartsWith']='%' . ($_GET['search'] . '%');
        }

        $comics = $comicRepository->findAllComics($criteria);

        return $this->render('search/search_by_comic.html.twig', [
            'countComics' =>$comics['count'],
            'comics' => $comics['comics'],
        ]);
    }
}
