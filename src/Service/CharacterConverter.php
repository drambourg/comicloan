<?php


namespace App\Service;


use App\Entity\Character;
use App\Repository\ComicRepository;

class CharacterConverter
{
    private $apiConnect;

    public function __construct(APIConnect $apiConnect)
    {
        $this->apiConnect = $apiConnect;
    }

    public function ConvertResponseToCharacterEntities(array $apiResponseCharacters) : array
    {
        $characters =[];

        $charactersData = $apiResponseCharacters['data']['results'];
        foreach ($charactersData as $characterDatum) {
            $character = new Character($this->apiConnect);
            $character->setId($characterDatum['id']);
            $character->setName($characterDatum['name']);
            $character->setDescription($characterDatum['description']);
            $character->setModified(new \DateTimeImmutable($characterDatum['modified']));
            $character->setThumbnailPath($characterDatum['thumbnail']['path']);
            $character->setThumbnailExtension($characterDatum['thumbnail']['extension']);
            $character->setResourceURI($characterDatum['resourceURI']);
            $characters[] = $character;
        }
        return $characters;
    }
}
