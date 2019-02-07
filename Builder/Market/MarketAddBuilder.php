<?php

namespace VkExPost\Builder\Market;

use VkExPost\Builder;
use VkExPost\ImageUploader\MarketImageUploader;

Class MarketAddBuilder extends Builder
{   
    protected $name;
    protected $description;
    protected $category_id;
    protected $price;
    protected $deleted;
    protected $main_photo_id;
    protected $photo_ids;

    public function __construct($token, $object, $method)
    {
        parent::__construct($token, $object, $method);
    }

    public function name(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function description(string $description)
    {
        $this->description = $description;
        return $this;
    }

    public function categoryId(int $category_id)
    {
        $this->category_id = $category_id;
        return $this;
    }

    public function price(float $price)
    {
        $this->price = $price;
        return $this;
    }

    public function deleted(bool $deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function mainImage(array $mainImage)
    {
        $mainImageInt = $this->uploadImages(new MarketImageUploader($this->access_token, $this->owner_id, 1), $mainImage);
        $this->main_photo_id = $mainImageInt;
        return $this;
    }

    public function addImages(array $addImages)
    {
        $addImagesInt = $this->uploadImages(new MarketImageUploader($this->access_token, $this->owner_id), $addImages);
        $this->photo_ids = $addImagesInt;
        return $this;
    }

}

