<?php

namespace App\Controller;

use App\Repository\ComicLoanRepository;
use App\Repository\ComicRepository;
use App\Service\ComicLoanStat;
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
        $criteria['orderBy']='title';

        $comics = $comicRepository->findAllComics($criteria);
        return $this->render('comic/index.html.twig', [
            'title_h1' => 'Comics',
            'title_h2' => 'Wanna read ?!!',
            'comics' => $comics['comics'],
            'comicCount' => $comics['count'],
            'activeloan' => true,
        ]);
    }

    /**
     * @Route("/show/{id}", name="comic_show")
     * @param int $id
     * @param ComicRepository $comicRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(
        int $id,
        ComicRepository $comicRepository,
        ComicLoanStat $comicLoanStat,
        ComicLoanRepository $comicLoanRepository
    ) {

        $comic = $comicRepository->findComicById($id)['comics'][0]??[];
        $characters = $comic->getCharacters();
        $creators = $comic->getCreators();

        return $this->render('comic/show.html.twig', [
            'title_h1' => 'Comic',
            'title_h2' => 'Are you interested ?!!',
            'comic' => $comic ?? [],
            'characters' => $characters ?? [],
            'creators' => $creators ?? [],
            'activeloan' => true,
            'chartComicUserHaveIt' => $comicLoanStat->RatioUserHaveTheComic($id),
            'chartComicLoanIt' => $comicLoanStat->RatioUserLoanTheComic($id),
            'chartComicLoanAvailable' => $comicLoanStat->RatioLoanableTheComic($id),
            'comicLoans' => $comicLoanRepository->findLastLoanerFromComicId($id),
        ]);
    }
}
