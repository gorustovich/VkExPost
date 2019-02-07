<?php

namespace VkExPost\Builder\Market;

use VkExPost\Builder;

Class MarketGetAlbumsBuilder extends Builder
{   
    protected $count;
    protected $offset;

    public function __construct($token, $object, $method)
    {
        parent::__construct($token, $object, $method);
    }

    /**
     * Limit of getting market albums
     * @param int $count
     * @return self
     */
    public function count(int $count)
    {
        $this->count = $count;
        return $this;
    }

    /**
     * Offset (смещение) selection relative of the first position
     * @param int $offset
     * @return self
     */
    public function setOffset(int $offset)
    {
        $this->offset = $offset;
        return $this;
    }
}

