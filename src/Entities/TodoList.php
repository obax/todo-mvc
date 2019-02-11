<?php

namespace TodoApi\Entities;
use DateTime;
use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\TodoApi\Repository\TodoListRepository")
 * @ORM\Table(name="todo_list")
 */
class TodoList implements JsonSerializable
{
    /**
     * @var $id int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @var string $name
     * @ORM\Column(name="name", type="string")
     */
    private $name;
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'id' => $this->id
        ];
    }
}