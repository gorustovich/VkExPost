<?php

namespace VkExPost;

Class Configurator
{

    const BASE_AUTHORIZE_URL = 'https://oauth.vk.com/authorize';
    const CALLBACK_URL = 'https://api.vk.com/blank.html';

	private function __construct(){}

	 /**
     * Returns authorization link with passed parameters.
     * @param   int $app_id;
     * @param   string $scope
     * @return  string
     */
    public static function getAuthorizeUrl(int $app_id, string $scope)
    {
        $parameters = array(
            'client_id' => $app_id,
            'v' => '5.73',
            'scope' => $scope,
            'redirect_uri' => self::CALLBACK_URL,
            'response_type' => 'token',
        );
        return self::createUrlWithParams(self::BASE_AUTHORIZE_URL, $parameters);
    }

     /**
     * Return access token from token Url.
     * @param   string $tokenUrl
     * @return  array
     */
    public static function getDataFromAuthorizeUrl(string $tokenUrl)
    {
        $tokenArrayParams = [];
        $tokenUrlParams = parse_url($tokenUrl, PHP_URL_FRAGMENT);
        parse_str($tokenUrlParams, $tokenArrayParams);

        return $tokenArrayParams;
    }

        /**
     * Concatenate keys and values to url format and return url.
     * @param   string $url
     * @param   array $parameters
     * @return  string
     */
    private static function createUrlWithParams($url, $parameters)
    {
        $url .= '?' . http_build_query($parameters);
        return $url;
    }
}
