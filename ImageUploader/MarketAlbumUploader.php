<?php

namespace VkExPost\ImageUploader;

use VkExPost\Api;
use VkExPost\ImageUploader;

Class MarketAlbumUploader extends ImageUploader
{   

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
        $method = 'photos.getMarketAlbumUploadServer';
        $parameters = [
            'group_id'      =>   $this->owner_id,
            'access_token'  =>   $this->access_token,
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
        $resultUpload = $this->api->upload($uploadUrl, $this->postfields, 'file');
        return $resultUpload;
    }

    /**
     * Save photo on server
     * @param  stdClass $resultUpload   Result of upload with photo path, server, hash to save it
     * @return stdClass                 Object with data of uploaded and saved images
     */
    protected function savePhoto($resultUpload)
    {   
        $method = 'photos.saveMarketAlbumPhoto';
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
      foreach ($resultOfSavePhoto->response as $photo) {
            $photoId = $photo->id;
      }
      return $photoId;
    }

     /**
     * Validate upload images with vk rules.
     * Minimal size of market album photo cover - 1280x720.
     * @return bool          
     */
    protected function validateUpload() 
    {
        foreach ($this->postfields as $image) {
            $imageSize = getimagesize($image);

            if($imageSize[0] < 1280 || $imageSize[1] < 720) {
                throw new \Exception("Обложка для подборки должно быть размерами не менее 1280 пикселей по ширине и 720 пикселей по высоте. Ваши размеры: $imageSize[0] ширина и $imageSize[1] высота");
            }
        }
        return true;
    }
}

