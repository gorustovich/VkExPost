<?php

namespace VkExPost\Builder\Photos;

use VkExPost;
use VkExPost\Api;
use VkExPost\Builder;

Class PhotosCreateAlbumBuilder extends Builder
{   
    protected $group_id;
    protected $title;
    protected $description;
    protected $privacy_view;
    protected $privacy_comment;
    protected $upload_by_admins_only;
    protected $comments_disabled;


    public function __construct($token, $object, $method)
    {
        parent::__construct($token, $object, $method);
    }

    /**
     * @param int $group_id
     * @return self
     */
    public function group(int $group_id)
    {
        $this->group_id = $group_id;
        return $this;
    }

    /**
     * @param string $title
     * @return self
     */
    public function title(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $description
     * @return self
     */
    public function description(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param mixed $privacy_view
     * @return self
     */
    public function privacyView($privacy_view)
    {
        $this->privacy_view = $privacy_view;
        return $this;
    }





    /**
     * @param bool $upload_by_admins_only
     * @return self
     */
    public function uploadByAdminsOnly(bool $upload_by_admins_only)
    {
        $this->upload_by_admins_only = $upload_by_admins_only;
        return $this;
    }

    /**
     * @param bool $comments_disabled
     * @return self
     */
    public function commentsDisabled(bool $comments_disabled)
    {
        $this->comments_disabled = $comments_disabled;
        return $this;
    }

    /**
     * Only for personal albums
     * @param mixed $privacy_comment
     * @return self
     */
    public function setPrivacyComment($privacy_comment)
    {
        $this->privacy_comment = $privacy_comment;
        return $this;
    }
}

