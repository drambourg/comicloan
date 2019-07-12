<?php


namespace App\Service;


use App\Entity\Creator;

class CreatorConverter
{

    public function ConvertResponseToCreatorEntities(array $apiResponseCreators) : array {
        $creators =[];

        $comicData = $apiResponseCreators['data']['results'];
        foreach ($comicData as $comicDatum) {
            $creator = new Creator();
            $creator->setId($comicDatum['id']);
            $creator->setFirstName($comicDatum['firstName']);
            $creator->setMiddleName($comicDatum['middleName']);
            $creator->setLastName($comicDatum['lastName']);
            $creator->setSuffix($comicDatum['suffix']);
            $creator->setFullName($comicDatum['fullName']);
            $creator->setThumbnailPath($comicDatum['thumbnail']['path']??null);
            $creator->setThumbnailExtension($comicDatum['thumbnail']['extension']??null);

            $creators[] = $creator;
        }
        return $creators;
    }
}
