<?php

namespace App\Controller;

use App\Repository\ComicRepository;
use App\Repository\RequestComicLoanRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RequestComicLoanController extends AbstractController
{
    /**
     * @Route("/loan/requests", name="loan_request_index")
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
}
