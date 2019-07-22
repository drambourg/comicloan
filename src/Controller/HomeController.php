<?php

namespace App\Controller;

use App\Repository\CharacterRepository;
use App\Repository\ComicLoanRepository;
use App\Repository\RequestComicLoanRepository;
use App\Repository\UserLibraryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
