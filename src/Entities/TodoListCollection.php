<?php

namespace TodoApi\Entities;

use \JsonSerializable;

class TodoListCollection implements JsonSerializable
{
    /**
     * @var array $collection
     */
    private $collection;
    
    public function __construct($items = [])
    {
        $this->collection = $items;
    }
    
    public function jsonSerialize()
    {
        return array_values($this->collection);
    }
    
    public function add(TodoList $item)
    {
        $this->collection[] = $item;
    }
    
}