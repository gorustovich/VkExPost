<?php
/**
 * General class for autoposting and remote posting to groups and albums vk.com
 * @author Gorustovich <gorustovich.sv@yandex.ru>
 * As base I get VK Class by Vlad Pronsy, thank him for his job
 * @author Vlad Pronsky <vladkens@yandex.ru>
 * @license https://raw.github.com/vladkens/VK/master/LICENSE MIT
 */

namespace VkExPost;

use VkExPost\ApiException;

class Api
{
    /**     * 
     * API version
     * @var int
     */
    protected $api_version;

    /**
     * Instance curl.
     * @var Resource
     */
    protected $ch;

    const BASE_AUTHORIZE_URL = 'https://oauth.vk.com/authorize';
    const ACCESS_TOKEN_URL = 'https://oauth.vk.com/access_token';
    const BASE_API_URL = 'https://api.vk.com/method/';

    /**
     * Constructor.
     * @param   string $api_secret
     * @param   string $access_token
     */
    public function __construct($api_version = '5.73')
    {
        $this->api_version = $api_version;
        $this->ch = curl_init();
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        curl_close($this->ch);
    }

     /**
     * Execute get method with parameters and return result.
     * @param   string $method
     * @param   array $parameters
     * @return  mixed
     */
    public function get(string $method, array $parameters)
    {
        $parameters['v'] = '5.73';
        ksort($parameters);

        $url = $this->createUrlWithParams($this->createApiUrl($method), $parameters);

        $jsonResponse = $this->request($url);
        $result = json_decode($jsonResponse);

        return $result;
    }

     /**
     * Execute post method to upload URL with parameters and return result.
     * @param   string $uploadUrl     Url, which we get from vk
     * @param   array $postfields     Array with path to photos
     * @param   string $postfieldName Name of post field - file, photo etc
     * @param   $hasMany              Is method can have few photos
     * @return  mixed
     */
    public function upload(string $uploadUrl,
                           array $postfields,
                           string $postfieldName,
                           bool $hasMany = false)
    {
        $postArray = $this->toPostArray($postfields, $postfieldName, $hasMany);
        $jsonResponse = $this->request($uploadUrl, 'POST', $postArray);
        $result = json_decode($jsonResponse);
        return $result;
    }

    /**
     * Executes request on link.
     * @param   string $url
     * @param   string $method
     * @param   array $postArray
     * @return  string
     */
    public function request($url, $method = 'GET', $postArray = [])
    {
        curl_setopt_array($this->ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => ($method == 'POST'),
            CURLOPT_URL => $url
        ));
        if ($method == 'POST' && !empty($postArray)) {
               curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postArray);
        }
        return curl_exec($this->ch);
    }

    /**
     * Create "curlness" array from files path. If you php version 5.5+, you need CURLFiles Array to CURLOPT_POSTFIELDS, else - array with @/filename. Also, Vk api requires that the file's array keys be prefixed (exp, with 'file', 'photo' etc).
     * @param  Array $postfields      Array with path to files
     * @param  String $postfieldName Name of post file
     * @return Array                 Array of CURLFiles
     */
    public function toPostArray(array $postfields, string $postfieldName, bool $hasMany)
    {
                $count = 1;
                foreach ($postfields as $postfield) {
                    if ($hasMany)
                        $filename = $postfieldName . $count;
                    else
                        $filename = $postfieldName;
                    if (class_exists('\CURLFile')) 
                        $postArray[$filename] = curl_file_create($postfield,'multipart/form-data');
                    else
                         $postArray[$filename] = '@/' . $postfield;
                    $count++;
                    } 
        return $postArray;
    }


    /**
     * Concatenate keys and values to url format and return url.
     * @param   string $url
     * @param   array $parameters
     * @return  string
     */
    private function createUrlWithParams($url, $parameters)
    {
        $url .= '?' . http_build_query($parameters);
        return $url;
    }


    /**
     * Returns base API url.
     * @param   string $method
     * @param   array $parameters
     * @return  string
     */
    private function createApiUrl($method)
    {
       return self::BASE_API_URL . $method;
    }
}
