<?php

namespace App\Controller;

use App\Entity\ComicLoan;
use App\Repository\ComicLoanRepository;
use App\Repository\ComicRepository;
use App\Repository\RequestComicLoanRepository;
use App\Repository\UserLibraryRepository;
use Doctrine\Common\Persistence\ObjectManager;
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
        UserLibraryRepository $userLibraryRepository,
        RequestComicLoanRepository $requestComicLoanRepository,
        ComicLoanRepository $comicLoanRepository,
        ComicRepository $comicRepository,
        Request $request
    )
    {
        $limitByPage = 10;
        $currentPage = $request->query->getInt('page', 1);

        $userComics = [];
        $comicUserIds = [0];

        if ($this->getUser()) {
            foreach ($userLibraryRepository->findBy(['user' => $this->getUser(), 'isLoanable' => true]) as $comicUser) {
                $comicAvailable = false;
                $comicLoanRepository->findOneBy([
                    'userLibrary' => $comicUser->getId(),
                    'status' => false,
                ]) ?? $comicAvailable = true;
                $userComics[] = [
                    'available' => $comicAvailable,
                    'comicUser' => $comicUser,
                ];
                $comicUserIds[] = $comicUser->getComicId();
            }
        }
        $loanRequests = $requestComicLoanRepository->findBy(['status' => false], ['dateAt' => 'DESC']);
        $loanRequestsPaginates = $paginator->paginate(
            $loanRequests ?? [],
            $currentPage,
            $limitByPage);

        $comicInfos = [];
        foreach ($loanRequestsPaginates as $loanRequest) {
            $comicInfos[] = $comicRepository->findComicById($loanRequest->getComicId())['comics'][0];
        }
        return $this->render('request_comic_loan/index.html.twig', [
            'title_h1' => 'Request Comics',
            'title_h2' => 'Needs You Help ?!',
            'requests' => $loanRequestsPaginates,
            'comicRequestedInfos' => $comicInfos ?? [],
            'userComics' => $userComics ?? [],
            'requestCount' => count($loanRequests) ?? 0,
            'controller_name' => 'RequestComicLoanController',
        ]);
    }


    /**
     * @Route("/user/calls", name="user_request_calls")
     */
    public function indexCalls(
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

        $userComics = [];
        $comicUserIds = [0];

        foreach ($userLibraryRepository->findBy(['user' => $this->getUser(), 'isLoanable' => true]) as $comicUser) {
            $comicAvailable = false;
            $comicLoanRepository->findOneBy([
                'userLibrary' => $comicUser->getId(),
                'status' => false,
            ]) ?? $comicAvailable = true;
            $userComics[] = [
                'available' => $comicAvailable,
                'comicUser' => $comicUser,
            ];
            $comicUserIds[] = $comicUser->getComicId();
        }
        $loanRequests = $requestComicLoanRepository->findBy([
            'comicId' => $comicUserIds,
            'status' => false
        ], ['dateAt' => 'DESC']);
        $loanRequestsPaginates = $paginator->paginate(
            $loanRequests ?? [],
            $currentPage,
            $limitByPage);

        $comicInfos = [];
        foreach ($loanRequestsPaginates as $loanRequest) {
            $comicInfos[] = $comicRepository->findComicById($loanRequest->getComicId())['comics'][0];
        }
        return $this->render('request_comic_loan/calls_user.html.twig', [
            'title_h1' => 'Request Comics',
            'title_h2' => 'Needs You Help ?!',
            'requests' => $loanRequestsPaginates,
            'comicRequestedInfos' => $comicInfos ?? [],
            'userComics' => $userComics,
            'requestCount' => count($loanRequests) ?? 0,
        ]);
    }

    /**
     * @Route("/user/calls/{id}", name="user_request_call_answer")
     */
    public function indexCallShow(
        int $id,
        RequestComicLoanRepository $requestComicLoanRepository,
        UserLibraryRepository $userLibraryRepository,
        ComicRepository $comicRepository
    )
    {
        $loanRequest = $requestComicLoanRepository->findOneById($id);
        $userLibraryRepository->findAll();
        $countLoans = 0;
        foreach ($userLibraryRepository->findAll() as $userComic) {
            $countLoans += count($userComic->getComicLoans());
        }
        $countRequest = count($requestComicLoanRepository->findByUser($loanRequest->getUser()));

        $comic = $comicRepository->findComicById($loanRequest->getComicId());

        return $this->render('request_comic_loan/calls_user_show.html.twig', [
            'title_h1' => 'Request Comics',
            'title_h2' => 'Rescue him ?!',
            'request' => $loanRequest ?? [],
            'userComic' => $userComic ?? [],
            'userCountRequests' => $countRequest,
            'userCountLoans' => $countLoans,
            'comic' => $comic['comics'][0] ?? [],
        ]);
    }



    /**
     * @Route("/user/helps", name="user_helps")
     */
    public function indexUserHelps(
        RequestComicLoanRepository $requestComicLoanRepository,
        ComicRepository $comicRepository,
        PaginatorInterface $paginator,
        Request $request
    )
    {
        $limitByPage = 10;
        $currentPage = $request->query->getInt('page', 1);
        $offset =  0 + $limitByPage * ($currentPage - 1);
        $helpRequestCount = count($requestComicLoanRepository->findBy(
            ['user' => $this->getUser(),
                'status' => 'false']
        ));
        $helpRequests = $requestComicLoanRepository->findBy(
            ['user' => $this->getUser(),
                'status' => 'false'],
            ['dateAt' => 'DESC'
            ],
            $limitByPage,
            $offset
        );
        $criteria['offset'] = $offset;
        $criteria['limit'] = $limitByPage;

        foreach($helpRequests as $helpRequest) {
            $comics[]= $comicRepository->findComicById($helpRequest->getComicId());
        }

        $helprequestPaginates = $paginator->paginate(
            $helpRequests ?? [],
            1,
            $limitByPage);
        $helprequestPaginates->setTotalItemCount($helpRequestCount ?? 0);
        $helprequestPaginates->setCurrentPageNumber($currentPage);

        return $this->render('request_comic_loan/help_user.html.twig', [
            'title_h1' => 'Help Calls',
            'title_h2' => 'Where are Heroes?!',
            'requests' => $helprequestPaginates ?? [],
            'comics' => $comics ?? [],
        ]);
    }

    /**
     * @Route("/user/loans", name="user_loans")
     */
    public function indexUserLoans(
        ComicLoanRepository $comicLoanRepository,
        ComicRepository $comicRepository,
        PaginatorInterface $paginator,
        Request $request
    )
    {
        $limitByPage = 10;
        $currentPage = $request->query->getInt('page', 1);
        $offset =  0 + $limitByPage * ($currentPage - 1);
        $loanRequestCount = count($comicLoanRepository->findBy(
            ['UserLoaner' => $this->getUser()]
        ));
        $loanRequests = $comicLoanRepository->findBy(
            ['UserLoaner' => $this->getUser()],
            ['dateOut' => 'DESC',
                'status' => 'ASC'
            ],
            $limitByPage,
            $offset
        );
        $criteria['offset'] = $offset;
        $criteria['limit'] = $limitByPage;

        foreach($loanRequests as $loanRequest) {
            $comics[]= $comicRepository->findComicById($loanRequest->getUserLibrary()->getComicId());
        }

        $loanrequestPaginates = $paginator->paginate(
            $loanRequests ?? [],
            1,
            $limitByPage);
        $loanrequestPaginates->setTotalItemCount($loanRequestCount ?? 0);
        $loanrequestPaginates->setCurrentPageNumber($currentPage);

        return $this->render('request_comic_loan/rescues_user.html.twig', [
            'title_h1' => 'Help Calls',
            'title_h2' => 'Who helped you ?!',
            'loanRequests' => $loanrequestPaginates ?? [],
            'comics' => $comics ?? [],
        ]);
    }

    /**
     * @Route("/user/calls/valid/{id}", name="user_request_call_valid")
     * @param int $id
     * @param RequestComicLoanRepository $requestComicLoanRepository
     * @param UserLibraryRepository $userLibraryRepository
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function ValidRequest(
        int $id,
        RequestComicLoanRepository $requestComicLoanRepository,
        UserLibraryRepository $userLibraryRepository,
        ObjectManager $manager
    )
    {
        $loanRequest = $requestComicLoanRepository->findOneById($id);
        $userComic = $userLibraryRepository->findOneBy(['comicId' => $loanRequest->getComicId(), 'user' => $this->getUser()]);
        $comicLoan = new ComicLoan();
        $comicLoan->setStatus(false);
        $comicLoan->setView(false);
        $comicLoan->setUserLoaner($loanRequest->getUser());
        $comicLoan->setDateOut(new \DateTime());
        $comicLoan->setUserLibrary($userComic);

        $manager->persist($comicLoan);
        $manager->flush();

        $loanRequest->setStatus(true);
        $manager->persist($loanRequest);
        $manager->flush();

        $this->addFlash('success', 'You loan comic to ' . $loanRequest->getUser()->getPseudoname() . '! You`re Awesome!');

        return $this->redirectToRoute('loan_manager', ['id' => $userComic->getId()]);

    }
}
