<?php

namespace VkExPost\Builder\Wall;

use VkExPost;
use VkExPost\Api;
use VkExPost\Builder;
use VkExPost\ImageUploader;
use VkExPost\ImageUploader\WallImageUploader;

Class WallPostBuilder extends Builder
{   
    protected $message;
    protected $postfields;
    protected $images;
    protected $attachments; 
    protected $friends_only;

    public function __construct($token, $object, $method)
    {
        parent::__construct($token, $object, $method);
    }
    /**
     * Set message for post
     * @param  string $message Message
     * @return $this
     */
    public function message(string $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Set friends_only. If false, only friend may see this post
     * @param  bool $friendsOnly Setting
     * @return $this
     */
    public function friendsOnly(bool $friends_only)
    {
        $this->friends_only = $friends_only;
        return $this;
    }

    /**
     * Only for owner_id < 0 (post to group wall). If this setting true, post will be publish from group name, if false - from user name. By default - from user name (false)
     * @param  bool $fromGroup Setting
     * @return $this
     */
    public function fromGroup(bool $from_group)
    {
        $this->from_group = $from_group;
        return $this;
    }


    /**
     * Datetime in UNIX format, when this post should be publish
     * @param  int $publish_date Setting
     * @return $this
     */
    public function publishDate(int $publish_date)
    {
        $this->publish_date = $publish_date;
        return $this;
    }

     /**
     * Attach link to post. Only 1 link available.
     * 
     * WARNING: if you attach link and photos together in post from group,
     * photo will be posted from user, not from group.
     * The reasons of this are not clear.
     * 
     * @param  string $link Setting
     * @return $this
     */
    public function link(string $link)
    {
        $this->attachments .= $this->withGlue($link, ',');
        return $this;
    }


     /**
     * Attach product to post.
     * @param  int $market Product ID
     * @return $this
     */
    public function market(int $market)
    {
        $product = 'market' . '-' . $this->owner_id . '_' . $market;
        $this->attachments .= $this->withGlue($product, ',');
        return $this;
    }

    /**
     * Set and upload images for post
     * @param  string $message Message
     * @return $this
     */
    public function images(array $images = [])
    {
        $imageAttachString = $this->uploadImages(
                                    new WallImageUploader($this->access_token, $this->owner_id),
                                    $images);
        $this->attachments .= $imageAttachString;
        return $this;
    }
}

