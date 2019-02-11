<?php

namespace TodoApi\Entities;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;
use \JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="\TodoApi\Repository\TodoItemRepository")
 * @ORM\Table(name="todo_item")
 */
class TodoItem implements JsonSerializable
{
    /**
     * @var $id int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="name", type="string")
     * @var string $name
     */
    protected $name;
    
    /**
     * @var DateTime $dueDate
     * @ORM\Column(name="due_date", type="date", nullable=true)
     */
    protected $dueDate;
    
    /**
     * @var DateTime $creationDate
     * @ORM\Column(name="creation_date", type="datetime")
     */
    protected $creationDate;
    
    /**
     * @var DateTime $dueDate
     * @ORM\Column(name="is_completed", type="boolean")
     */
    protected $isCompleted = false;
    
    /**
     * @var string $description
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\Column(name="list", nullable=true)
     * @ORM\OneToOne(targetEntity="TodoApi\Entities\TodoList", inversedBy="id")
     */
    protected $list;
    
    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->list;
    }
    
    /**
     * @param mixed $list
     */
    public function setList($list)
    {
        $this->list = $list;
    }
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    
    public function __construct()
    {
        $this->creationDate = new DateTime();
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
        preg_match('/[a-zA-Z]*/', $name, $match);
        $this->name = $match[0];
    }
    
    /**
     * @return DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }
    
    /**
     * @param DateTime $dueDate
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = new DateTime($dueDate);
    }
    
    /**
     * @return DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }
    
    /**
     * @param DateTime $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }
    
    /**
     * @return DateTime
     */
    public function isCompleted()
    {
        return $this->isCompleted;
    }
    
    /**
     * @param DateTime $isCompleted
     */
    public function setIsCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;
    }
    
    
    public function jsonSerialize()
    {
        return [
            'created_on' => date_format($this->creationDate, 'Y-m-d:s'),
            'due_on'   => $this->dueDate ? date_format($this->dueDate, 'Y-m-d:s') : '',
            'name'       => $this->name,
            'description' => $this->description,
            'completed' => $this->isCompleted ? 'yes' : 'no'
        ];
    }
}