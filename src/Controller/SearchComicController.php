<?php

namespace App\Controller;

use App\Repository\ComicRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/search")
 */
class SearchComicController extends AbstractController
{
    /**
     * @Route("/comics", name="search_by_comic")
     */
    public function SearchByComic(
        ComicRepository $comicRepository,
        PaginatorInterface $paginator,
        Request $request)
    {
        $limitByPage = 10;
        $currentPage = $request->query->getInt('page', 1);
        $criteria['orderBy']='title';
        if (isset($_GET['search'])) {
            $criteria['titleStartsWith']='%' . ($_GET['search'] . '%');
        }
        $criteria['offset']= 0 + $limitByPage * ($currentPage -1);
        $criteria['limit']= $limitByPage;

        $comics = $comicRepository->findAllComics($criteria);

        $comicPaginates = $paginator->paginate(
            $comics['comics'],
            1,
            $limitByPage);
        $comicPaginates->setTotalItemCount($comics['count']);
        $comicPaginates->setCurrentPageNumber($currentPage);

        return $this->render('search/search_by_comic.html.twig', [
            'comics' => $comicPaginates,
            'countComics' =>$comics['count'],
            /*'comics' => $comics['comics'],*/
        ]);
    }
}
