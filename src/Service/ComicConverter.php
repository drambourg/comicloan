<?php


namespace App\Service;


use App\Entity\Comic;

class ComicConverter
{
    private $apiConnect;

    public function __construct(APIConnect $apiConnect)
    {
        $this->apiConnect = $apiConnect;
    }

    public function ConvertResponseToComicEntities(array $apiResponseComics): array
    {
        $comics = [];

        $comicData = $apiResponseComics['data']['results'];
        foreach ($comicData as $comicDatum) {
            $comic = new Comic($this->apiConnect);
            $comic->setId($comicDatum['id']);
            $comic->setDigitalId($comicDatum['digitalId']);
            $comic->setTitle($comicDatum['title']);
            $comic->setIssueNumber($comicDatum['issueNumber']);
            $comic->setVariantDescription($comicDatum['variantDescription']);
            $comic->setDescription($comicDatum['description']);
            $comic->setModified(new \DateTime($comicDatum['modified']));
            $comic->setIsbn($comicDatum['isbn']);
            $comic->setFormat($comicDatum['format']);
            $comic->setPageCount($comicDatum['pageCount']);
            $comic->setDetailUrl($comicDatum['urls'][0]['url'] ?? null);
            $comic->setPurchaseURL($comicDatum['urls'][1]['url'] ?? null);
            $comic->setOnsaleDate(new \DateTime($comicDatum['dates'][0]['date']) ?? null);
            $comic->setPrintPrice($comicDatum['prices'][0]['price'] ?? null);
            $comic->setDigitalPurchasePrice($comicDatum['prices'][1]['price'] ?? null);
            $comic->setThumbnailPath($comicDatum['thumbnail']['path'] ?? null);
            $comic->setThumbnailExtension($comicDatum['thumbnail']['extension'] ?? null);
            $comics[] = $comic;
        }
        return $comics;
    }

    public function ConvertArrayComicsToIdArray(array $comics): array
    {
        foreach ($comics as $comic) {
            $results[] = $comic->getId();
        }
        return $results ?? [];
    }
}
