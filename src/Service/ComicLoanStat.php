<?php


namespace App\Service;


use App\Entity\UserLibrary;
use App\Repository\ComicLoanRepository;
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

    public function __construct(
        UserRepository $userRepository,
        UserLibraryRepository $userLibraryRepository,
        ComicLoanRepository $comicLoanRepository)
    {
        $this->userLibraryRepository = $userLibraryRepository;
        $this->userRepository = $userRepository;
        $this->comicLoanRepository = $comicLoanRepository;
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
        $loanableComics = $this->userLibraryRepository->findBy(['comicId' => $idComic, 'isLoanable' => true]);
        $nUserCountHaveComicLoanable = count($loanableComics);
        $nComicLoaning = 0;
        foreach ($loanableComics as $comicUser) {
            if ($this->comicLoanRepository->findBy(['userLibrary' => $comicUser->getId(), 'status' => false])) {
                $nComicLoaning++;
            };
        }
        $nUserCountHaveComicLoanable == 0 ? $ratio = 0 : $ratio = (int)ceil((($nUserCountHaveComicLoanable - $nComicLoaning) / $nUserCountHaveComicLoanable) * 100);
        return [
            'ratio' => $ratio,
            'count' => ($nUserCountHaveComicLoanable - $nComicLoaning)
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
