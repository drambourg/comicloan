<?php

namespace App\Controller;

use App\Entity\UserLibrary;

use App\Form\UserInformationType;
use App\Entity\UserRate;
use App\Form\UserRateType;
use App\Repository\ComicLoanRepository;
use App\Repository\ComicRepository;
use App\Repository\RequestComicLoanRepository;
use App\Repository\UserLibraryRepository;
use App\Repository\UserRateRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/edit", name="user_edit")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function editUser(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserInformationType::class, $user);
        $form->handleRequest($request);
        $oldPassword = $form->get('oldPassword')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($oldPassword)) {
                $this->getDoctrine()->getManager()->flush();
                $user->setAvatarPicture(null);

                return $this->redirectToRoute('user_edit');
            }
            if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {
                $newEncodedPassword = $passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData());
                $user->setPassword($newEncodedPassword);
                $this->getDoctrine()->getManager()->flush();
                $user->setAvatarPicture(null);

                return $this->redirectToRoute('user_edit');
            }

            $form->addError(new FormError('Wrong old password'));
        }

        $user->setAvatarPicture(null);

        return $this->render('user/edit_user.html.twig', [
            'title_h1' => 'Your Hero Profile',
            'title_h2' => 'New Identity ?',
            'user' => $user,
            'userInformationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="user_show")
     */
    public function show(
        int $id,
        UserRepository $userRepository,
        UserRateRepository $userRateRepository,
        RequestComicLoanRepository $requestComicLoanRepository,
        UserLibraryRepository $userLibraryRepository,
        Request $request
    )
    {
        $userToRate = $userRepository->findOneById($id);
        $rateAllow = true;
        if (!is_null($userRateRepository->findOneBy(['author' => $this->getUser(), 'user' => $userRepository->findOneById($id)]))) {
            $rateAllow = false;
        };

        $formRateUser = $this->createForm(UserRateType::class);
        $formRateUser->handleRequest($request);

        if ($formRateUser->isSubmitted() && $formRateUser->isValid()) {
            $rateUser = new UserRate();

            $rateUserData = $formRateUser->getData();
            $rateUser->setUser($userRepository->findOneById($id));
            $rateUser->setAuthor($this->getUser());
            $rateUser->setDateAt(new \DateTime());
            $rateUser->setComment($rateUserData->getComment());
            $rateUser->setRate($rateUserData->getRate());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rateUser);
            $entityManager->flush();

            $this->addFlash('success', 'You Rate Hero ! ');

            return $this->redirectToRoute('user_show', [
                'id' => $id,
            ]);
        }

        $userLibraryRepository->findAll();
        $countLoans = 0;
        foreach ($userLibraryRepository->findAll() as $userComic) {
            $countLoans += count($userComic->getComicLoans());
        }
        $countRequest = count($requestComicLoanRepository->findByUser($this->getUser()));


        return $this->render('user/show.html.twig', [
            'user' => $userRepository->findOneById($id),
            'userCountRequests' => $countRequest,
            'userCountLoans' => $countLoans,
            'formRateUser' => $formRateUser->createView(),
            'rateAllow' => $rateAllow,
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
    )
    {

        $comics = [];
        $userComics = [];

        if ($session->get('user')) {
            $libraryComics = $userLibraryRepository->findByUser($session->get('user'));
            foreach ($libraryComics as $libraryComic) {
                $comic = $comicRepository->findComicById($libraryComic->getComicId());
                if ($comic !== []) {
                    $comics = array_merge($comics, $comic['comics']);
                    $userComics[] = $libraryComic;
                }
            }
        }
        $comicPaginates = $paginator->paginate(
            $comics,
            $request->query->getInt('page', 1),
            10);

        return $this->render('user/library.html.twig', [
            'title_h1' => 'Loan Manager',
            'title_h2' => 'My collection',
            'comics' => $comicPaginates,
            'userComics' => $userComics,
            'countComics' => count($userComics),
        ]);

    }

    /**
     * @Route("/library/loanmanager/{id}", name="loan_manager")
     */
    public function ManageLoanComics(
        int $id,
        UserLibraryRepository $userLibraryRepository,
        ComicRepository $comicRepository,
        ComicLoanRepository $comicLoanRepository
    )
    {
        $libraryComic = $userLibraryRepository->findOneById($id);
        $comicLoans = $comicLoanRepository->findBy(
            [
                'userLibrary' => $libraryComic
            ],
            [
                'dateOut' => 'DESC'
            ]);
        return $this->render('user/loan_manager.html.twig', [
            'title_h1' => 'Loan Manager',
            'title_h2' => 'Where is it ?!',
            'comic' => $comicRepository->findComicById($libraryComic->getComicId())['comics'][0] ?? [],
            'userComic' => $libraryComic ?? [],
            'comicLoans' => $comicLoans ?? [],
            'activeloan' => false,
        ]);
    }

    /**
     * @Route("/library/comic_loan_back/{id}", name="comic_back_loan", methods={"GET","POST"})
     */
    public function comicLoanBack(int $id, ComicLoanRepository $comicLoanRepository, ObjectManager $manager)
    {
        $comicLoan = $comicLoanRepository->findOneBy(['id' => $id]);
        $comicLoan->setStatus(!$comicLoan->getStatus());
        $comicLoan->setDateIn(new \DateTime());
        $manager->persist($comicLoan);
        $manager->flush();
        return $this->json([
            'status' => $comicLoan->getStatus(),
            'dateBack' => $comicLoan->getDateIn()->format('Y-m-d'),
        ]);
    }

    /**
     * @Route("/library/comic_loanable/{id}", name="comic_loanabale", methods={"GET","POST"})
     */
    public function comicLoanable(int $id, UserLibraryRepository $userLibraryRepository, ObjectManager $manager)
    {
        $libraryComic = $userLibraryRepository->findOneBy(['id' => $id]);
        $libraryComic->setIsLoanable(!$libraryComic->getIsLoanable());
        $manager->persist($libraryComic);
        $manager->flush();
        return $this->json(['isLoanable' => $libraryComic->getIsLoanable()]);
    }

    /**
     * @Route("/library/add_comic/{id}", name="library_comic_add", methods={"GET","POST"})
     * @param int $id
     * @param UserLibraryRepository $userLibraryRepository
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addComicToLibrary(int $id, ObjectManager $manager)
    {
        $libraryComic = new UserLibrary();
        $libraryComic->setUser($this->getUser());
        $libraryComic->setComicId($id);
        $libraryComic->setIsLoanable(true);

        $manager->persist($libraryComic);
        $manager->flush();

        return $this->json(['ownComic' => true]);
    }

    /**
     * @Route("/library/remove_comic/{id}", name="library_comic_remove", methods={"GET","POST"})
     * @param int $id
     * @param UserLibraryRepository $userLibraryRepository
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeComicToLibrary(int $id, UserLibraryRepository $userLibraryRepository, ObjectManager $manager)
    {
        $comics = $userLibraryRepository->findBy(['comicId' => $id, 'user' => $this->getUser()]);
        foreach ($comics as $comic) {
            $manager->remove($comic);
            $manager->flush();
        }

        return $this->json(['ownComic' => false]);
    }
}
