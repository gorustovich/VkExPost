<?php

namespace VkExPost;

abstract class ImageUploader
{   
    protected $access_token;
    protected $owner_id;
    protected $api;

    public function __construct ($access_token, $owner_id) 
    {
        $this->access_token = $access_token;
        $this->owner_id = $owner_id;
        $this->api = new Api();
    }

    /**
     * Upload files and return array with vk.com-compatible link on files
     * @param array $postfields    Array with path to file
     * @param string $object        Type of upload - to wall, to album,
     *                              to market, wich we use to create right Object.Method API
     * @return string               String with attached files in
     *                              format photo<group_id>_<id>
     */
    public function upload(array $postfields)
    {
        $this->postfields = $postfields;     
            if(!$this->validateUpload()) return false;
        $uploadServer = $this->getUploadServer();
            if(isset($uploadServer->error)) return $uploadServer;
        $resultUpload = $this->uploadImagesOnServer($uploadServer);
            if(isset($resultUpload->error)) return $resultUpload;
        $resultOfSavePhoto = $this->savePhoto($resultUpload);
            if(isset($resultOfSavePhoto->error)) return $resultOfSavePhoto;
        $attachString = $this->formAttachString($resultOfSavePhoto);

        return $attachString;
    }

    /**
     * Get url server for uploading files with current data
     * @return string         Upload url
     */
    abstract protected function getUploadServer();

    /**
     * Upload images on server
     * @param  stdObject $uploadServer  stdObject with upload server
     * @return stdObject                 stdObject with result, after json_decode
     */
    abstract protected function uploadImagesOnServer($uploadServer);

    /**
     * Save photo on server
     * @param  stdClass $resultUpload   Result of upload with photo path, server, hash to save it
     * @return stdClass                 Object with data of uploaded and saved images
     */

    abstract protected function savePhoto($resultUpload);

    /**
     * Get string with attaches 
     * @param  stdClass $resultOfSavePhoto Object with result of save photo
     * @return string                   String with attaches, format photo<group_id>_<id>
     */
    
    abstract protected function formAttachString($resultOfSavePhoto);


    /**
     * Validate upload images with vk rules 
     * @return bool          
     */
    
    abstract protected function validateUpload();
}

