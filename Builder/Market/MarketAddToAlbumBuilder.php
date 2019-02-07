<?php

namespace VkExPost\Builder\Market;

use VkExPost\Builder;


Class MarketAddToAlbumBuilder extends Builder
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

}

