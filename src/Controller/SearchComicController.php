<?php

namespace App\Controller;

use App\Repository\CharacterRepository;
use App\Repository\ComicRepository;
use App\Repository\UserLibraryRepository;
use App\Service\ComicConverter;
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
        UserLibraryRepository $userLibraryRepository,
        PaginatorInterface $paginator,
        ComicConverter $comicConverter,
        Request $request)
    {
        $limitByPage = 10;
        $currentPage = $request->query->getInt('page', 1);
        $criteria['orderBy'] = 'title';
        if (isset($_GET['search'])) {
            $criteria['titleStartsWith'] = '%' . ($_GET['search'] . '%');
        }
        $criteria['offset'] = 0 + $limitByPage * ($currentPage - 1);
        $criteria['limit'] = $limitByPage;

        $comics = $comicRepository->findAllComics($criteria);

        $comicPaginates = $paginator->paginate(
            $comics['comics'] ?? [],
            1,
            $limitByPage);
        $comicPaginates->setTotalItemCount($comics['count'] ?? 0);
        $comicPaginates->setCurrentPageNumber($currentPage);

        if ($this->getUser()) {
            $comicsCollection = $userLibraryRepository->findByUser($this->getUser());
            foreach ($comicsCollection as $comic) {
                $comicsCollectionIds[] = $comic->getComicId();
            }
        }

        return $this->render('search/search_comic_by_comic.html.twig', [
            'title_h1' => 'Find Comics',
            'title_h2' => 'By title',
            'comics' => $comicPaginates ?? [],
            'comicsUserLibrary' => $comicsCollection ?? [],
            'comicsUserLibraryIds' => $comicsCollectionIds ?? [],
            'countComics' => $comics['count'] ?? 0,
            'activeloan' => true,
        ]);
    }

    /**
     * @Route("/comics/characters", name="search_by_character")
     */
    public function SearchComicByCharacter(
        ComicRepository $comicRepository,
        UserLibraryRepository $userLibraryRepository,
        CharacterRepository $characterRepository,
        PaginatorInterface $paginator,
        Request $request)
    {
        $limitByPage = 10;
        $maxCharacterResultGeneral = 10;
        $currentPage = $request->query->getInt('page', 1);

        $criteria['orderBy'] = 'title';
        $criteria['offset'] = 0 + $limitByPage * ($currentPage - 1);
        $criteria['limit'] = $limitByPage;


        if (isset($_GET['search'])) {
            $characterCriteria['nameStartsWith'] = '%' . ($_GET['search'] . '%');
        }
        $characterCriteria['orderBy'] = 'name';
        $characterCriteria['limit'] = $maxCharacterResultGeneral;
        $characters = $characterRepository->findAllCharacters($characterCriteria);
        $charactersFound = $characters['count'];

        $characters['count'] > $maxCharacterResultGeneral ?: $characterCriteria['limit'] = $charactersFound;
        $characters = $characterRepository->findAllCharacters($characterCriteria)['characters'] ?? [];

        $characterIds = [];
        foreach ($characters as $character) {
            $characterIds[] = $character->getId();
        }

        $queryCharacter = implode(',', $characterIds);
        !isset($characterIds) ?: $criteria['characters'] = $queryCharacter;
        $comics = $comicRepository->findAllComics($criteria);

        $comicPaginates = $paginator->paginate(
            $comics['comics'] ?? [],
            1,
            $limitByPage);
        $comicPaginates->setTotalItemCount($comics['count'] ?? 0);
        $comicPaginates->setCurrentPageNumber($currentPage);

        if ($this->getUser()) {
            $comicsCollection = $userLibraryRepository->findByUser($this->getUser());
            foreach ($comicsCollection as $comic) {
                $comicsCollectionIds[] = $comic->getComicId();
            }

            return $this->render('search/search_comic_by_character.html.twig', [
                'title_h1' => 'Find Comics',
                'title_h2' => 'By characters',
                'comics' => $comicPaginates ?? [],
                'countComics' => $comics['count'] ?? 0,
                'comicsUserLibrary' => $comicsCollection ?? [],
                'comicsUserLibraryIds' => $comicsCollectionIds ?? [],
                'characters' => $characters,
                'countCharacters' => $charactersFound,
                'maxCharacters' => $maxCharacterResultGeneral,
                'activeloan' => true,
            ]);
        }
    }
}
