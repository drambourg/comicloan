<?php

namespace App\Controller;

use App\Repository\ComicLoanRepository;
use App\Repository\ComicRepository;
use App\Repository\RequestComicLoanRepository;
use App\Repository\UserLibraryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/requests")
 */
class RequestComicLoanController extends AbstractController
{
    /**
     * @Route("/index", name="loan_request_index")
     */
    public function index(
        PaginatorInterface $paginator,
        RequestComicLoanRepository $requestComicLoanRepository,
        ComicRepository $comicRepository,
        Request $request
    )
    {
        $limitByPage = 10;
        $currentPage = $request->query->getInt('page', 1);

        $loanRequests = $requestComicLoanRepository->findBy([],['dateAt' => 'DESC']);
        $loanRequestsPaginates = $paginator->paginate(
            $loanRequests ?? [],
            $currentPage,
            $limitByPage);

        $comicInfos = [];
        foreach($loanRequestsPaginates as $loanRequest) {
            $comicInfos[] = $comicRepository->findComicById($loanRequest->getComicId())['comics'][0];
        }
        return $this->render('request_comic_loan/index.html.twig', [
            'title_h1' => 'Request Comics',
            'title_h2' => 'Need Help ?!',
            'requests' => $loanRequestsPaginates,
            'comicRequestedInfos' => $comicInfos??[],
            'controller_name' => 'RequestComicLoanController',
        ]);
    }


    /**
     * @Route("/user/calls", name="user_request_calls")
     */
    public function index_calls(
        PaginatorInterface $paginator,
        UserLibraryRepository $userLibraryRepository,
        RequestComicLoanRepository $requestComicLoanRepository,
        ComicLoanRepository $comicLoanRepository,
        ComicRepository $comicRepository,
        Request $request
    )
    {
        $limitByPage = 10;
        $currentPage = $request->query->getInt('page', 1);

        $userComics =[];
        $comicUserIds=[0];

        foreach ( $userLibraryRepository->findBy(['user' => $this->getUser(), 'isLoanable' =>true]) as $comicUser) {
            $comicAvailable = false;
            $comicLoanRepository->findOneBy([
                        'userLibrary'=> $comicUser->getId(),
                        'status'=>false,
                    ])??$comicAvailable=true;
            $userComics[] = [
                'available' => $comicAvailable,
                'comicUser' =>$comicUser,
            ];
            $comicUserIds[]= $comicUser->getComicId();
        }
        $loanRequests = $requestComicLoanRepository->findBy([
            'comicId' => $comicUserIds],['dateAt' => 'DESC']);
        $loanRequestsPaginates = $paginator->paginate(
            $loanRequests ?? [],
            $currentPage,
            $limitByPage);

        $comicInfos = [];
        foreach($loanRequestsPaginates as $loanRequest) {
            $comicInfos[] = $comicRepository->findComicById($loanRequest->getComicId())['comics'][0];
        }
        return $this->render('request_comic_loan/calls_user.html.twig', [
            'title_h1' => 'Request Comics',
            'title_h2' => 'Need Help ?!',
            'requests' => $loanRequestsPaginates,
            'comicRequestedInfos' => $comicInfos??[],
            'userComics'=>$userComics,
            'controller_name' => 'RequestComicLoanController',
        ]);
    }
}
