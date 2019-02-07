<?php

namespace VkExPost\Builder\Market;

use VkExPost\Builder;

Class MarketGetCategoriesBuilder extends Builder
{   
    protected $count;

    public function __construct($token, $object, $method)
    {
        parent::__construct($token, $object, $method);
    }

    /**
     * Set count of category to return
     * @param  string $count Message
     * @return $this
     */
    public function count(int $count)
    {
        $this->count = $count;
        return $this;
    }

}

