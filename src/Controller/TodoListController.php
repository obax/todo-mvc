<?php

namespace TodoApi\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use TodoApi\Entities\TodoList;
use TodoApi\Entities\TodoListCollection;

use InvalidArgumentException;
use Exception;

class TodoListController extends BaseController implements BaseCrudInterface
{
    /**
     * @inheritdoc
     */
    public function all(Request $request, Response $response)
    {
        $data = $this->prepareData();
    
        /** @var TodoList[] $items $items */
        $items = $this->todoListRepository->findAll();
        $data['data'] = new TodoListCollection($items);
    
        return $this->renderer->render($request, $response, $data)
            ->withAddedHeader('Cache-Control', 'public, max-age=60');
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
            $taskList = new TodoList();
        
            if (!$name) {
                throw new InvalidArgumentException('You need to provide a name for your task list');
            }

            $taskList->setName($name);
            $this->em->persist($taskList);
        
            /** @var TodoList $taskList */
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
    
        /** @var TodoList $task */
        try {
            if (!$id) {
                throw new InvalidArgumentException('You need to provide the identifier of the task to delete');
            }
    
            $task = $this->todoListRepository->find($id);
    
            if (!$task) {
                throw new InvalidArgumentException('No item list with that id');
            }
            
            $name = $content->name;
        
            if ($name) {
                $task->setName($name);
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
            $taskList = $this->todoListRepository->find($id);
    
            if (!$taskList) {
                throw new InvalidArgumentException('No item list with that id');
            }
            
            $this->em->remove($taskList);
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
    public function get(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $data = $this->prepareData();
    
        /** @var TodoList[] $items $items */
        $item = $this->todoListRepository->find($id);
        $data['data'] = $item ?? '';
    
        return $this->renderer->render($request, $response, $data)
            ->withAddedHeader('Cache-Control', 'max-age=60');
    }
    
}