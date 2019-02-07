<?php

namespace VkExPost\Builder\Market;

use VkExPost\Builder;
use VkExPost\ImageUploader\MarketAlbumUploader;

Class MarketAddAlbumBuilder extends Builder
{   
    protected $title;
    protected $photo_id;
    protected $main_album;

    public function __construct($token, $object, $method)
    {
        parent::__construct($token, $object, $method);
    }

    /**
     * Set title of Market Album
     * @param  string $post_id Message
     * @return $this
     */
    public function title(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set photo id of market photo cover. Only one photo
     * @param array $photo_id
     * @return self
     */
    public function photo(array $photo)
    {
        $photoId = $this->uploadImages(new MarketAlbumUploader($this->access_token, $this->owner_id), $photo);
        $this->photo_id = $photoId;        
        return $this;
    }
}

