<?php

namespace App\Repository;

use App\Entity\ComicPicture;
use App\Service\APIConnect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComicPicture|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComicPicture|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComicPicture[]    findAll()
 * @method ComicPicture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComicPictureRepository extends ServiceEntityRepository
{
    const BASE_URI ='/v1/public/characters';

    private $apiComicPictureConnect;
    private $apiConnect;
    private $comicPictureConverter;


    public function __construct(RegistryInterface $registry, APIConnect $apiConnect,ComicPictureConverter $comicPictureConverter)
    {
        parent::__construct($registry, ComicPicture::class);
        $this->comicPictureConverter = $comicPictureConverter;
        $this->apiConnect = $apiConnect;
        $this->apiComicPictureConnect = $apiConnect->getApiurl() . self::BASE_URI;
    }

}
