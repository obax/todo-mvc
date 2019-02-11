<?php

namespace TodoApi\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use TodoApi\Entities\TodoItem;
use TodoApi\Entities\TodoItemCollection;

use Exception;
use InvalidArgumentException;


class TodoItemController extends BaseController implements BaseCrudInterface
{
    /**
     * @inheritdoc
     */
    public function get(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $data = $this->prepareData();

        /** @var TodoItem[] $items $items */
            $item = $this->todoItemRepository->find($id);
            $data['data'] = $item ?? '';

        return $this->renderer->render($request, $response, $data)
            ->withAddedHeader('Cache-Control', 'max-age=60');
    }
    
    /**
     * @inheritdoc
     */
    public function create(Request $request, Response $response)
    {
        $data = $this->prepareData();

        $content = json_decode($request->getBody()->getContents());

        try {
            $name = $content->name;
            $description = $content->description;
            $dueDate = $content->due_date;
            $isCompleted = filter_var($content->is_completed, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            
            $task = new TodoItem();
    
            if (!$name) {
                throw new InvalidArgumentException('You need to provide a name for your task');
            }
            
            $task->setName($name);
    
            if ($description) {
                $task->setDescription($description);
            }
    
            if ($dueDate) {
                $task->setDueDate($dueDate);
            }
            if ($isCompleted !== null) {
                $task->setIsCompleted($isCompleted);
            }
            $this->em->persist($task);
    
            /** @var TodoItem $task */
            $this->em->flush();
        } catch (Exception $e) {
            $data['data'] = ['status' => self::STATUS_FAILED];
            $data['error'] = [
                'message' => $e->getMessage(),
                'type' => get_class($e)
            ];
        }
        return $this->renderer->render($request, $response, $data)
            ->withAddedHeader('Cache-Control', self::CACHE_NO_CACHE);
    }
    
    /**
     * @inheritdoc
     */
    public function update(Request $request, Response $response, $args)
    {
        $data = $this->prepareData();
        $id = $args['id'];
        
        $content = json_decode($request->getBody()->getContents());
        
        try {
            if (!$id) {
                throw new InvalidArgumentException('You need to provide the identifier of the task to delete');
            }
    
            /** @var TodoItem $task */
            $task = $this->todoItemRepository->find($id);
    
            if (!$task) {
                throw new InvalidArgumentException('No item with that id');
            }
            
            $name = $content->name;
            $description = $content->description;
            $dueDate = $content->due_date;
            $isCompleted = filter_var($content->is_completed, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            
            if ($name) {
                $task->setName($name);
            }
            
            if ($description) {
                $task->setDescription($description);
            }
            
            if ($dueDate) {
                $task->setDueDate($dueDate);
            }
            if ($isCompleted !== null) {
                $task->setIsCompleted($isCompleted);
            }

            $this->em->flush();
            
        } catch (Exception $e) {
            $data['metadata']['status'] =  self::STATUS_FAILED;
            $data['error'] = [
                'message' => $e->getMessage(),
                'type' => get_class($e)
            ];
        }
        return $this->renderer->render($request, $response, $data)
            ->withAddedHeader('Cache-Control', self::CACHE_NO_CACHE);
    }
    
    /**
     * @inheritdoc
     */
    public function delete(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $data = $this->prepareData();
        
        try {
            if (!$id) {
                throw new InvalidArgumentException('You need to provide the identifier of the task to delete');
            }
            $task = $this->todoItemRepository->find($id);
            $this->em->remove($task);
            $this->em->flush();
            
        } catch (Exception $e) {
            $data['metadata']['status'] =  self::STATUS_FAILED;
            $data['error'] = [
                'message' => $e->getMessage(),
                'type' => get_class($e)
            ];
        }
        return $this->renderer->render($request, $response, $data)
            ->withAddedHeader('Cache-Control', self::CACHE_NO_CACHE);
    }
    
    /**
     * @inheritdoc
     */
    public function all(Request $request, Response $response)
    {
        $data = $this->prepareData();
        
        /** @var TodoItem[] $items $items */
        $items = $this->todoItemRepository->findAll();
        $collection = new TodoItemCollection($items);
    
        $filter = $request->getQueryParam('filter', false);
        $listId = $request->getQueryParam('listId', false);
        
        if($filter){
            switch ($filter){
                case 'overdue':
                    $collection->filterOverdue();
                    break;
                case 'completed':
                    $collection->filterCompleted();
                    break;
                case 'list':
                    $collection->filterByListId($listId);
            }
        }
        
        $data['data'] = $collection;
        return $this->renderer->render($request, $response, $data)
            ->withAddedHeader('Cache-Control', 'public, max-age=60');
    }
}
