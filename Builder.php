<?php

namespace VkExPost;

use VkExPost;
use VkExPost\Api;
use VkExPost\ImageUploader;

abstract class Builder
{   
    protected $access_token; 
    protected $object; 
    protected $method; 
    protected $owner; 
    protected $owner_id; 
    protected $errors; 
    protected $buildParams;

    public function __construct($access_token, $object, $method)
    {
        $this->access_token = $access_token;
        $this->object = $object;
        $this->method = $method;
        $this->errors = new \stdClass;
    }

    /**
     * Set type of owner of wall 
     * @param  string $owner     Type of owner - 'personal' or 'group'
     * @param  int    $owner_id  Id of owner the wall
     * @return $this
     */
    public function owner(string $owner, int $owner_id) 
    {
        $this->owner = $owner;
        $this->owner_id = $owner_id;

        return $this;
    }

    /**
     * Main function, start after building object with fields. It finish final preparation, create api object and call api's method
     * @return mixed   Result of execution
     */
    public function execute()
    {
    $this->isOwnerGroup();
     $api = new Api();

     if($this->errors->errors) 
       return $this->errors;
    $result = $api->get($this->getApiMethodName(), $this->getAllParams());

    if(isset($result->error->error_msg)) {
           $this->errors->errors = ['Request error: ' . $result->error->error_msg];
            return $this->errors;
        }
    return $result;
    }

      /**
     * Uploads images on server and save it
     * @param  array  $postfields Array with file paths
     * @return mixed   In success case, return formed string with images
     */
    protected function uploadImages(ImageUploader $imageUploader,                        
                                 array $postfields = [])
    {
        if(empty($postfields)) { 
            // $this->errors->errors = ['No images to upload - an empty array is provided'];
             return $this;
         }


        if (!$this->validatePhotoResourse($postfields)) {
            $this->errors->errors = ['One or more images do not exist. Check the path and name files'];
            return false;
        } 
       $imageAttachString = $imageUploader->upload($postfields);

       if(is_object($imageAttachString) && isset($imageAttachString->error->error_msg)) {
                $this->errors->errors = ['Errors with upload files: ' . $imageAttachString->error->error_msg];
                return false; 
        }

        return $imageAttachString;

    }    

    /**
     * Get all params of object, return only with value and exclude object and method fields
     * @return [type] [description]
     */
    protected function getAllParams()
    {
        $params = get_object_vars($this);
        $paramsWithValue = array_filter($params, function ($param) {
            if(!empty($param))
                if($param !== $this->object && $param !== $this->method)
                return $param;
        });
        return $paramsWithValue;
    }

    /**
     * Get formApiMethodName to execute 
     * @return void
     */
    protected function getApiMethodName() 
    {
      $ApiMethodName = $this->object . '.' . $this->method;
      return $ApiMethodName;
    }

    /**
     * Checks existing file
     * @return void
     */
    protected function validatePhotoResourse($postfields)
    {
        foreach ($postfields as $file) {
          if(!is_file($file))
            return false;
        }
            return true;
    }

    /**
     * Form owner id in accordance with the requirements: group_id should be an negative number
     * @return void
     */
    protected function isOwnerGroup()
    {
        if ($this->owner === 'group') {
           $this->owner_id = 0 - $this->owner_id;
        }
    }

    /**
     * Helper to concat attachment string
     * @param void
     */
    protected function withGlue(string $attachable, $glue)
    {
        return $glue . $attachable . $glue;
    } 
}

