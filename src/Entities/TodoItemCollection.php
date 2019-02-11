<?php

namespace TodoApi\Entities;

use \JsonSerializable;


class TodoItemCollection implements JsonSerializable
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
    
    public function add(TodoItem $item)
    {
        $this->collection[] = $item;
    }
    
    public function filterCompleted()
    {
        $this->collection = array_filter($this->collection, function (TodoItem $item) {
            return $item->isCompleted();
        });
    }
    
    public function filterOverdue()
    {
        $this->collection = array_filter($this->collection, function (TodoItem $item) {
            if($item->getDueDate() === null){
                return false;
            }
            return $item->getDueDate()->getTimestamp() <  (new \DateTime('now'))->getTimestamp();
        });
    }
    
    public function filterByListId($listId)
    {
        if($listId){
            $this->collection = array_filter($this->collection, function (TodoItem $item) use ($listId) {
                return $item->getList() === $listId;
            });
        }
    }
}