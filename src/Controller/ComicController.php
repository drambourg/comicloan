<?php

namespace App\Controller;

use App\Form\RequestSubmitType;
use App\Repository\ComicLoanRepository;
use App\Repository\ComicRepository;
use App\Repository\RequestComicLoanRepository;
use App\Repository\UserLibraryRepository;
use App\Service\ComicLoanStat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

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
        $criteria['orderBy'] = 'title';

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
        ComicLoanRepository $comicLoanRepository,
        RequestComicLoanRepository $requestComicLoanRepository,
        UserLibraryRepository $userLibraryRepository,
        Request $request
    )
    {

        $formRequest = $this->createForm(RequestSubmitType::class);
        $formRequest->handleRequest($request);

        if ($formRequest->isSubmitted() && $formRequest->isValid()) {

            $requestComic = $formRequest->getData();
            $requestComic->setUser($this->getUser());
            $requestComic->setComicId($id);
            $requestComic->setDateAt(new \DateTime());
            $requestComic->setStatus(false);
            $requestComic->setResponse(false);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($requestComic);
            $entityManager->flush();

            $this->addFlash('danger', 'Help Call for a comic Launched!!');

            return $this->redirectToRoute('loan_request_index');
        }

        $comic = $comicRepository->findComicById($id)['comics'][0] ?? [];
        $characters = $comic->getCharacters();
        $creators = $comic->getCreators();

        if ($this->getUser()) {
            $comicUser = $userLibraryRepository->findOneBy(
                [
                    'user' => $this->getUser(),
                    'comicId' => $id
                ]
            );
        }


        return $this->render('comic/show.html.twig', [
            'title_h1' => 'Comic',
            'title_h2' => 'Are you interested ?!!',
            'comic' => $comic ?? [],
            'characters' => $characters ?? [],
            'creators' => $creators ?? [],
            'activeloan' => true,
            'comicUser' =>$comicUser?? [],
            'chartComicUserHaveIt' => $comicLoanStat->RatioUserHaveTheComic($id),
            'chartComicLoanIt' => $comicLoanStat->RatioUserLoanTheComic($id),
            'chartComicLoanAvailable' => $comicLoanStat->RatioLoanableTheComic($id),
            'statsComicLoans' => $comicLoanStat->RatioLoanedTheComic($id),
            'statsComicLastMonthLoans' => $comicLoanStat->RatioLoanedLastMonthTheComic($id),
            'comicLoans' => $comicLoanRepository->findLastLoanerFromComicId($id),
            'formRequest' => $formRequest->createView(),
        ]);
    }
}
