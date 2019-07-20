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
        ComicRepository $comicRepository
    ) {
        $criteria['orderBy']='title';
        $comic = $comicRepository->findComicById($id)['comics'][0]??[];
        $characters = $comic->getCharacters();
        $creators = $comic->getCreators();

        dump($characters);
        dump($creators);

        return $this->render('comic/show.html.twig', [
            'title_h1' => 'Comic',
            'title_h2' => 'Are you interested ?!!',
            'comic' => $comic ?? [],
            'characters' => $characters ?? [],
            'creators' => $creators ?? [],
            'activeloan' => true,
        ]);
    }
}
