<?php

namespace App\Controller;

use App\Entity\Comic;
use App\Entity\UserLibrary;
use App\Repository\ComicRepository;
use App\Repository\UserLibraryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
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
     * Liste l'ensemble des articles triés par date de publication pour une page donnée.
     *
     * @Route("/articles/{page}", requirements={"page" = "\d+"}, name="front_articles_index")
     * @Method("GET")
     * @Template("XxxYyyBundle:Front/Article:index.html.twig")
     *
     * @param int $page Le numéro de la page
     *
     * @return array
     */
    /**
     * @Route("/library",
     *     name="user_library")
     */
    public function showLibrary(
        SessionInterface $session,
        UserLibraryRepository $userLibraryRepository,
        PaginatorInterface $paginator,
        ComicRepository $comicRepository,
        Request $request
    ) {

        $comics = [];
        $userComics= [];

        if ($session->get('user')) {
            $libraryComics = $userLibraryRepository->findByUser($session->get('user'));
            foreach ($libraryComics as $libraryComic) {
                $comic = $comicRepository->findComicById($libraryComic->getComicId());
                if ($comic!==[]) {
                    $comics = array_merge($comics, $comic);
                    $userComics[]= $libraryComic;
                }
            }
        }
        $comicPaginates = $paginator->paginate(
            $comics,
            $request->query->getInt('page', 1),
            10);

        return $this->render('user/library.html.twig', [
            'comics' => $comicPaginates,
            'userComics' => $userComics,
        ]);

    }

    /**
     * @Route("/{id}/favorite", name="article_favorite", methods={"GET","POST"})
     * @param Request $request
     * @param Article $article
     * @param ObjectManager $manager
     * @return Response
     */
  /*  public function favorite(Request $request, int $idComic, ObjectManager $manager): Response
    {
        if ($this->getUser()->get->getI()->contains($article)) {
            $this->getUser()->removeFavorite($article)   ;
        }
        else {
            $this->getUser()->addFavorite($article);
        }
        $manager->flush();

        return $this->json([
            'isFavorite' => $this->getUser()->isFavorite($article)
        ]);*/
}
