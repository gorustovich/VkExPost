<?php

namespace VkExPost\ImageUploader;

use VkExPost\Api;
use VkExPost\ImageUploader;

Class WallImageUploader extends ImageUploader
{   
    protected $postfields;

    public function __construct ($access_token, $owner_id)
    {
        parent::__construct($access_token, $owner_id);
    }

    /**
     * Get url server for uploading files with current data
     * @return stdObject         Upload server
     */
    protected function getUploadServer()
    {
        $method = 'photos.getWallUploadServer';
        $parameters = [
            'access_token'  =>   $this->access_token,
            'group_id'      =>   $this->owner_id,
        ];
        $uploadServer = $this->api->get($method, $parameters);
        return $uploadServer;
    }


    /**
     * Upload images on server
     * @param  stdObject $uploadServer  stdObject with upload server
     * @return stdObject                stdObject with result, after json_decode
     */
    protected function uploadImagesOnServer($uploadServer)
    {
        $uploadUrl = $uploadServer->response->upload_url;
        $resultUpload = $this->api->upload($uploadUrl, $this->postfields, 'file', 1);

        return $resultUpload;
    }

    /**
     * Save photo on server
     * @param  stdClass $resultUpload   Result of upload with photo path, server, hash to save it
     * @return stdClass                 Object with data of uploaded and saved images
     */
    protected function savePhoto($resultUpload)
    {   
        $method = 'photos.saveWallPhoto';
        $parameters = [
            'group_id'     =>   $this->owner_id,
            'access_token' =>   $this->access_token,
            'photo'        =>   $resultUpload->photo,
            'server'       =>   $resultUpload->server,
            'hash'         =>   $resultUpload->hash,
        ];

        $resultOfSavePhoto = $this->api->get($method, $parameters);
        return $resultOfSavePhoto;

    }

    /**
     * Get string with attaches 
     * @param  stdClass $resultOfSavePhoto Object with result of save photo
     * @return string                   String with attaches, format photo<group_id>_<id>
     */
    protected function formAttachString($resultOfSavePhoto)
    {   
      $attachArray = [];
        foreach ($resultOfSavePhoto->response as $photo) {
          $attachArray[] = 'photo' . $photo->owner_id . '_' . $photo->id;
      }
      $attachString = implode(',', $attachArray);

      return $attachString;
    }

    /**
     * Validate upload images with vk rules. Reduce upload photos to 4 items
     * @return bool          
     */
    protected function validateUpload() 
    {
        if(count($this->postfields) > 4) {
           $this->postfields = array_slice($this->postfields, 0, 4); 
        }
        return true;
    }
}

