<?php


namespace App\Service;


use App\Entity\UserLibrary;
use App\Repository\ComicLoanRepository;
use App\Repository\UserLibraryRepository;
use App\Repository\UserRepository;
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

    public function RatioUserHaveTheComic(int $idComic) : ?array {
        $nUserCountHaveComic = count($this->userLibraryRepository->findByComicId($idComic));
        $nUserCount = count($this->userRepository->findAll());

        return [
            'ratio' => (int) ceil(($nUserCountHaveComic / $nUserCount) * 100),
            'count' => $nUserCount]
            ;
    }

    public function RatioUserLoanTheComic(int $idComic) : ?array {
        $nUserCountHaveComic = count($this->userLibraryRepository->findByComicId($idComic));
        $nUserCountHaveComicLoanable = count($this->userLibraryRepository->findBy(['comicId'=>$idComic, 'isLoanable'=> true]));

        return [
            'ratio' => (int) ceil(($nUserCountHaveComicLoanable / $nUserCountHaveComic) * 100),
            'count' => $nUserCountHaveComicLoanable]
            ;
    }

    public function RatioLoanableTheComic(int $idComic) : ?array {
        $loanableComics = $this->userLibraryRepository->findBy(['comicId'=>$idComic, 'isLoanable'=> true]);
        $nUserCountHaveComicLoanable = count($loanableComics);
        $nComicLoaning=0;
        foreach ( $loanableComics as $comicUser) {
            if ($this->comicLoanRepository->findBy(['userLibrary' => $comicUser->getId(), 'status' =>false]) ){
                $nComicLoaning++;
            };
        }

        return [
            'ratio' => (int) ceil((($nUserCountHaveComicLoanable-$nComicLoaning) / $nUserCountHaveComicLoanable) * 100),
            'count' => ($nUserCountHaveComicLoanable-$nComicLoaning)]
            ;
    }

}
