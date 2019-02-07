<?php

namespace VkExPost\Builder\Photos;

use VkExPost;
use VkExPost\Api;
use VkExPost\Builder;

Class PhotosDeleteAlbumBuilder extends Builder
{   
    protected $group_id;
    protected $album_id;

    public function __construct($token, $object, $method)
    {
        parent::__construct($token, $object, $method);
    }

    /**
     * @param int $group_id
     * @return self
     */
    public function groupId(int $group_id)
    {
        $this->group_id = $group_id;
        return $this;
    }

    /**
     * @param int $album_id
     * @return self
     */
    public function albumId(int $album_id)
    {
        $this->album_id = $album_id;
        return $this;
    }
}

