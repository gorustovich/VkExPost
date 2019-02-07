<?php

namespace VkExPost;

use VkExPost\Exception;

/**
 * Main dispatcher class for find needed builder
 * Основной диспетчер-фасад для поиска и конструктирования необходимого запроса
 */
class VkExPost
{
    const BUILDER_NAMESPACE = 'VkExPost\Builder\\';
    private $token;
    private $object;
    private $method;

	/**
	 * VkExPost constructor.
	 * @param $token Token, that you received from vk.com
	 */
    private function __construct($token)
    {
        $this->token = $token;
    }

	/**
	 * Static method, which return VkExPost-facade instance
	 * Статический метод а-ля конструктор
	 * @param $token string
	 * @return VkExPost
	 */
    public static function create($token)
    {
        return new self($token);
    }

	/**
	 * Public method of the facade, return query in object/method builder
	 * Публичный метод фасада, возвращающий запрошенный билдер
	 * @param $object string
	 * @param $method string
	 * @return mixed Builder
	 */
    public function apiTo($object, $method)
    {
        $this->object = $object;
        $this->method = $method;
        $builder = $this->importBuilder();
        return $builder;
    }

    private function importBuilder()
    {
        $builderName = $this->getBuilderName();

        if (class_exists($builderName)) {
          return new $builderName($this->token, $this->object, $this->method);
        } else {
          throw new Exception('Api for this object or this method is not registered');
        }
    }

    private function getBuilderName()
    {
        $builderName = self::BUILDER_NAMESPACE . $this->object . '\\' . $this->object . $this->method . 'Builder';

        return $builderName;
    }
}

