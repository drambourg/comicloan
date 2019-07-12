<?php


namespace App\Service;


class Picture
{
    private $base_path;
    private $extension;

    const PICTURE_SIZE = [
        'small',
        'medium',
        'large',
        'xlarge',
        'fantastic',
        'amazing',
    ];
    const PICTURE_FORMAT = [
        'standard',
        'landscape',
        'portrait',
    ];

    public function __construct(string $base_path, string $extension)
    {
        $this->base_path = $base_path;
        $this->extension = $extension;
    }

    public function getPictureSquareLD()
    {
        return $this->base_path . '/' . self::PICTURE_FORMAT [0] . '_' . self::PICTURE_SIZE [1]  .'.' . $this->extension ;
    }

    public function getPictureSquareSD()
    {
        return $this->base_path . '/' . self::PICTURE_FORMAT [0] . '_' . self::PICTURE_SIZE [3]  .'.' . $this->extension ;
    }

    public function getPictureSquareHD()
    {
        return $this->base_path . '/' . self::PICTURE_FORMAT [0] . '_' . self::PICTURE_SIZE [5]  .'.' . $this->extension ;
    }

    public function getPictureLandscapeLD()
    {
        return $this->base_path . '/' . self::PICTURE_FORMAT [1]  .'_' . self::PICTURE_SIZE [1]  .'.' .  $this->extension ;
    }

    public function getPictureLandscapeSD()
    {
        return $this->base_path . '/' . self::PICTURE_FORMAT [1]  .'_' . self::PICTURE_SIZE [3]  .'.' . $this->extension ;
    }

    public function getPictureLandscapeHD()
    {
        return $this->base_path . '/' . self::PICTURE_FORMAT [1]  .'_' . self::PICTURE_SIZE [5]  .'.' . $this->extension ;
    }

    public function getPicturePortraitLD()
    {
        return $this->base_path . '/' . self::PICTURE_FORMAT [2]  .'_' . self::PICTURE_SIZE [1]  .'.' . $this->extension ;
    }

    public function getPicturePortraitSD()
    {
        return $this->base_path . '/' . self::PICTURE_FORMAT [2]  .'_' . self::PICTURE_SIZE [2]  .'.' . $this->extension ;
    }

    public function getPicturePortraitHD()
    {
        return $this->base_path . '/' . self::PICTURE_FORMAT [2]  .'_' . self::PICTURE_SIZE [5]  .'.' . $this->extension ;
    }

}
