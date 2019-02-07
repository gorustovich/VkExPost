<?php

namespace VkExPost;
 
class ApiException extends \Exception 
{
	private $vkError;

	public function setVkError($vkError)
	{
		$this->vkError = $vkError;
	}

    /**
     * @return mixed
     */
    public function getVkError()
    {
        return $this->vkError;
    }
}