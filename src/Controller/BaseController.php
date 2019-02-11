<?php

namespace TodoApi\Controller;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use RKA\ContentTypeRenderer\Renderer;

use \DateTime;
use TodoApi\Entities\TodoItem;
use TodoApi\Entities\TodoList;
use TodoApi\Repository\TodoItemRepository;

class BaseController
{
    const CACHE_NO_CACHE = 'no-cache';
    const STATUS_OK = 'OK';
    const STATUS_FAILED = 'FAILED';
    
    /** @var EntityManager $em */
    protected $em;
    
    /**
     * Used to consider the Accept header of the client for the response instead of the opinionated $response->withJson()
     * @var Renderer $renderer
     */
    protected $renderer;
    
    /**
     * @var TodoItemRepository $todoItemRepository
     */
    protected $todoItemRepository;
    
    /**
     * @var TodoItemRepository $todoItemRepository
     */
    protected $todoListRepository;
    
    /**
     * Default part of each response payload
     * @return array
     */
    public function prepareData()
    {
        return [
            'metadata' => [
                'last_updated' => date_format(new DateTime(), 'Y-m-d:s'),
                'status'       => self::STATUS_OK
            ]
        ];
    }
    
    public function __construct(ContainerInterface $container)
    {
        $this->em = $container->get(EntityManager::class);
        $this->todoItemRepository = $this->em->getRepository(TodoItem::class);
        $this->todoListRepository = $this->em->getRepository(TodoList::class);
        $this->renderer = (new Renderer(true))
            ->setDefaultMediaType('application/json')
            ->setXmlRootElementName('todo');
    }
}