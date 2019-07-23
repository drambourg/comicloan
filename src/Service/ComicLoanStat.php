<?php


namespace App\Service;


use App\Entity\UserLibrary;
use App\Repository\ComicLoanRepository;
use App\Repository\ComicRepository;
use App\Repository\UserLibraryRepository;
use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use Ghunti\HighchartsPHP\Highchart;

class ComicLoanStat
{
    private $userRepository;
    private $userLibraryRepository;
    private $comicLoanRepository;
    private $comicRepository;

    public function __construct(
        UserRepository $userRepository,
        UserLibraryRepository $userLibraryRepository,
        ComicLoanRepository $comicLoanRepository,
        ComicRepository $comicRepository)
    {
        $this->userLibraryRepository = $userLibraryRepository;
        $this->userRepository = $userRepository;
        $this->comicLoanRepository = $comicLoanRepository;
        $this->comicRepository = $comicRepository;
    }

    public function RatioUserHaveTheComic(int $idComic): ?array
    {
        $nUserCountHaveComic = count($this->userLibraryRepository->findByComicId($idComic));
        $nUserCount = count($this->userRepository->findAll());
        $nUserCount == 0 ? $ratio = 0 : $ratio = (int)ceil(($nUserCountHaveComic / $nUserCount) * 100);
        return [
            'ratio' => $ratio,
            'count' => $nUserCountHaveComic,
        ];
    }

    public function RatioUserLoanTheComic(int $idComic): ?array
    {
        $nUserCountHaveComic = count($this->userLibraryRepository->findByComicId($idComic));
        $nUserCountHaveComicLoanable = count($this->userLibraryRepository->findBy(['comicId' => $idComic, 'isLoanable' => true]));
        $nUserCountHaveComic == 0 ? $ratio = 0 : $ratio = (int)ceil(($nUserCountHaveComicLoanable / $nUserCountHaveComic) * 100);
        return [
            'ratio' => $ratio,
            'count' => $nUserCountHaveComicLoanable,
        ];
    }

    public function RatioLoanableTheComic(int $idComic): ?array
    {
        $comicAvailables = $this->comicLoanRepository->findUserLibraryAvailable(0,'DESC');
        $comicAvailables = array_count_values($comicAvailables);
        foreach ($comicAvailables as $key => $topAvailableComic) {
            $topAvailableComics[] = [
                'count'=> $topAvailableComic,
                'comic'=> $this->comicRepository->findComicById($key)['comics'][0],
            ];
        }

        $loanableComics = $this->userLibraryRepository->findBy(['comicId' => $idComic, 'isLoanable' => true]);
        $nUserCountHaveComicLoanable = count($loanableComics);
        $countAvailable = $comicAvailables[$idComic]??0;
        $countAvailable == 0 ?  $ratio = 0 : $ratio = (int)ceil(($countAvailable / $nUserCountHaveComicLoanable) * 100);
        return [
            'ratio' => $ratio,
            'count' => $comicAvailables[$idComic]??0,
        ];
    }

    public function RatioLoanedTheComic(int $idComic): ?array
    {
        $allComicLoans = $this->comicLoanRepository->AllLoansFromComicId($idComic)??[];

        return [
            'loans' => $allComicLoans,
            'count' => count($allComicLoans),
        ];
    }

    public function RatioLoanedLastMonthTheComic(int $idComic): ?array
    {

        $date = new DateTime();
        $interval = new DateInterval('P1M');
        $date->sub($interval);

        $allComicLoans = $this->comicLoanRepository->AllLoansSinceDateFromComicId($idComic, $date)??[];

        return [
            'loans' => $allComicLoans,
            'count' => count($allComicLoans),
        ];
    }

}
