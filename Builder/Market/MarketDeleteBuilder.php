<?php

namespace VkExPost\Builder\Market;

use VkExPost\Builder;

Class MarketDeleteBuilder extends Builder
{   
    protected $item_id;

    public function __construct($token, $object, $method)
    {
        parent::__construct($token, $object, $method);
    }

    /**
     * Set item_id to delete
     * @param  int $post_id Message
     * @return $this
     */
    public function itemId(int $item_id)
    {
        $this->item_id = $item_id;
        return $this;
    }

}

