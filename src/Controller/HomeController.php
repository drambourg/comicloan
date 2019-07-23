<?php

namespace App\Controller;

use App\Repository\CharacterRepository;
use App\Repository\ComicLoanRepository;
use App\Repository\ComicRepository;
use App\Repository\RequestComicLoanRepository;
use App\Repository\UserLibraryRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(
        UserRepository $userRepository,
        UserLibraryRepository $userLibraryRepository,
        ComicLoanRepository $comicLoanRepository,
        RequestComicLoanRepository $requestComicLoanRepository,
        SessionInterface $session, Security $security)
    {
        $session->set('user', $security->getUser());
        $usersCount = count($userRepository->findAll());
        $comicLoanableCount = count($userLibraryRepository->findBy(['isLoanable' => true]));
        $requestsCount = count($requestComicLoanRepository->findAll());
        $comicsUsersHaveCount = count($userLibraryRepository->findAll());
        $comicLoansCount= count($comicLoanRepository->findAll());
        return $this->render('home/index.html.twig', [
            'title_h1' => 'Comic Loan !',
            'title_h2' => 'Share your collection with your league!!',
            'countUser' => $usersCount,
            'comicLoansCount' => $comicLoansCount,
            'comicLoanableCount' => $comicLoanableCount,
            'comicRequestsCount' => $requestsCount,
            'comicsUsersHaveCount' => $comicsUsersHaveCount,
        ]);
    }

    /**
     * @Route("/loantown", name="home_loan")
     */
    public function index_showtop(
        UserRepository $userRepository,
        UserLibraryRepository $userLibraryRepository,
        ComicLoanRepository $comicLoanRepository,
        ComicRepository $comicRepository,
        RequestComicLoanRepository $requestComicLoanRepository
    ) {


        $comicAvailables = $comicLoanRepository->findUserLibraryAvailable(0,'DESC');
        $comicAvailables = array_count_values($comicAvailables);
        arsort($comicAvailables);
        $limitResult = 3;
        $comicAvailables = array_slice ($comicAvailables, 0, $limitResult, true);
        foreach ($comicAvailables as $key => $topAvailableComic) {
            $topAvailableComics[] = [
                'count'=> $topAvailableComic,
                'comic'=> $comicRepository->findComicById($key)['comics'][0],
                ];
        }

        foreach ($comicLoanRepository->findUserLoanerByCountLoan(3,'DESC') as $topUserLoaner) {
            $topUserLoaners[] = [
                'count'=> $topUserLoaner['count'],
                'user'=> $userRepository->findOneById($topUserLoaner['id']),
            ];
        }

        foreach ($comicLoanRepository->findUserLoanerByCountLoan(3,'ASC') as $badUserLoaner) {
            $badUserLoaners[] = [
                'count'=> $badUserLoaner['count'],
                'user'=> $userRepository->findOneById($badUserLoaner['id']),
            ];
        }

        foreach ( $userLibraryRepository->findUserLibraryByCount(3,'DESC') as $topUserCollection) {
            $topUserCollections[] = [
                'count'=> $topUserCollection['count'],
                'user'=> $userRepository->findOneById($topUserCollection['id']),
            ];
        }

        foreach ( $userLibraryRepository->findUserLibraryByCountLoanable(3,'DESC') as $topComicLoanable) {
            $topComicLoanables[] = [
                'count'=> $topComicLoanable['count'],
                'comic'=> $comicRepository->findComicById($topComicLoanable['comicId'])['comics'][0],
            ];
        }

        foreach ( $requestComicLoanRepository->findComicByCountRequested(3,'DESC') as $topRequestedComic) {
            $topRequestedComics[] = [
                'count'=> $topRequestedComic['count'],
                'comic'=> $comicRepository->findComicById($topRequestedComic['comicId'])['comics'][0],
            ];
        }

        return $this->render('home/show_top.html.twig', [
            'title_h1' => 'Loan\'s Town!',
            'title_h2' => 'Town activites',
            'topAvailableComics' => $topAvailableComics,
            'topUserLoaners' => $topUserLoaners,
            'badUserLoaners' => $badUserLoaners,
            'topUserCollections' => $topUserCollections,
            'topComicLoanables' => $topComicLoanables,
            'topRequestedComics' => $topRequestedComics,
        ]);


    }

    /**
     * @Route("/loan/comics", name="home_loan_comics")
     * @param ComicLoanRepository $comicLoanRepository
     * @param ComicRepository $comicRepository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index_available_comic(
        ComicLoanRepository $comicLoanRepository,
        ComicRepository $comicRepository,
        PaginatorInterface $paginator,
        UserLibraryRepository $userLibraryRepository,
        Request $request
    ) {


        $limitByPage = 10;
        $currentPage = $request->query->getInt('page', 1);
        $offset = 0 + $limitByPage * ($currentPage - 1);
        $criteria['orderBy'] = 'title';
        if (isset($_GET['search'])) {
            $criteria['titleStartsWith'] = '%' . ($_GET['search'] . '%');
        }

        $criteria['offset'] = $offset;
        $criteria['limit'] = $limitByPage;

        $comicAvailables = $comicLoanRepository->findUserLibraryAvailable(0,'DESC');
        $comicAvailables = array_count_values($comicAvailables);
        $countAvailableComic = count($comicAvailables);
        arsort($comicAvailables);
        $comicAvailables = array_slice ($comicAvailables, $offset, $limitByPage, true);
        foreach ($comicAvailables as $key => $topAvailableComic) {
            $topAvailableComics[] = $comicRepository->findComicById($key)['comics'][0];
        }

        $comicPaginates = $paginator->paginate(
            $topAvailableComics ?? [],
            1,
            $limitByPage);
        $comicPaginates->setTotalItemCount($countAvailableComic ?? 0);
        $comicPaginates->setCurrentPageNumber($currentPage);

        if ($this->getUser()) {
            $comicsCollection = $userLibraryRepository->findByUser($this->getUser());
            foreach ($comicsCollection as $comic) {
                $comicsCollectionIds[] = $comic->getComicId();
            }
        }

        return $this->render('search/index_comic_available.html.twig', [
            'title_h1' => 'In Town !',
            'title_h2' => 'Loan\'s Heroes',
            'comicsUserLibrary' => $comicsCollection ?? [],
            'comicsUserLibraryIds' => $comicsCollectionIds ?? [],
            'countComics' => $countAvailableComic ?? 0,
            'comics' => $comicPaginates ?? [],
            'activeloan' => true,
        ]);


    }
}
