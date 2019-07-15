<?php


namespace App\Service;

use App\Entity\Creator;

class CreatorConverter
{
    private $apiConnect;

    public function __construct(APIConnect $apiConnect)
    {
        $this->apiConnect = $apiConnect;
    }

    public function ConvertResponseToCreatorEntities(array $apiResponseCreators) : array {
        $creators =[];

        $creatorData = $apiResponseCreators['data']['results'];
        foreach ($creatorData as $creatorDatum) {
            $creator = new Creator($this->apiConnect);
            $creator->setId($creatorDatum['id']);
            $creator->setFirstName($creatorDatum['firstName']);
            $creator->setMiddleName($creatorDatum['middleName']);
            $creator->setLastName($creatorDatum['lastName']);
            $creator->setSuffix($creatorDatum['suffix']);
            $creator->setFullName($creatorDatum['fullName']);
            $creator->setThumbnailPath($creatorDatum['thumbnail']['path']??null);
            $creator->setThumbnailExtension($creatorDatum['thumbnail']['extension']??null);
            $creators[] = $creator;
        }
        return $creators;
    }
}
