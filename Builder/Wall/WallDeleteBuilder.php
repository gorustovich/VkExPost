<?php

namespace VkExPost\Builder\Wall;

use VkExPost;
use VkExPost\Api;
use VkExPost\Builder;
use VkExPost\ImageUploader;
use VkExPost\ImageUploader\WallImageUploader;

Class WallDeleteBuilder extends Builder
{   
    protected $post_id;

    public function __construct($token, $object, $method)
    {
        parent::__construct($token, $object, $method);
    }

    /**
     * Set post_id to delete
     * @param  string $post_id Message
     * @return $this
     */
    public function postId(int $post_id)
    {
        $this->post_id = $post_id;
        return $this;
    }

}

