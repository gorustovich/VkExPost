<?php

namespace VkExPost\ImageUploader;

use VkExPost\Api;
use VkExPost\ImageUploader;

Class MarketImageUploader extends ImageUploader
{   
    protected $postfields;
    protected $isMain;

    public function __construct ($access_token, $owner_id, $isMain = 0)
    {
        parent::__construct($access_token, $owner_id);
        $this->isMain = $isMain;
    }

    /**
     * Get url server for uploading files with current data
     * @return stdObject         Upload server
     */
    protected function getUploadServer()
    {
        $method = 'photos.getMarketUploadServer';
        $parameters = [
            'group_id'      =>   $this->owner_id,
            'access_token'  =>   $this->access_token,
        ];

        if($this->isMain) $parameters['main_photo'] = 1;
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
       if($this->isMain) {
                $resultUpload = $this->api->upload($uploadUrl,
                                                   $this->postfields,
                                                   'file');
              } else {
                $resultUpload = $this->api->upload($uploadUrl,
                                                   $this->postfields,
                                                   'file',
                                                    1);
              }
        return $resultUpload;
    }

    /**
     * Save photo on server
     * @param  stdClass $resultUpload   Result of upload with photo path, server, hash to save it
     * @return stdClass                 Object with data of uploaded and saved images
     */
    protected function savePhoto($resultUpload)
    {   
        $method = 'photos.saveMarketPhoto';
        $parameters = [
            'group_id'     =>   $this->owner_id,
            'access_token' =>   $this->access_token,
            'photo'        =>   $resultUpload->photo,
            'server'       =>   $resultUpload->server,
            'hash'         =>   $resultUpload->hash,

        ];

        if($this->isMain) {
            $parameters['crop_data'] = $resultUpload->crop_data;
            $parameters['crop_hash'] = $resultUpload->crop_hash;
        }

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
          $attachArray[] = $photo->id;
      }
      $attachString = implode(',', $attachArray);
      return $attachString;
    }

     /**
     * Validate upload images with vk rules 
     * @return bool          
     */
    protected function validateUpload() 
    {
        if($this->isMain) {
            if(count($this->postfields) > 1) 
            {
              throw new \Exception("Для главной картинки - только одна картинка");
            }
        } else {
            if(count($this->postfields) > 10) 
            {
              throw new \Exception("10 картинок для товара - это слишком много");
            }
        }
        return true;
    }
}

