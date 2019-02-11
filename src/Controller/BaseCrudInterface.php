<?php

namespace TodoApi\Controller;
    

use Slim\Http\Response;
use Slim\Http\Request;
use Psr\Http\Message\ResponseInterface;

interface BaseCrudInterface
{
    /**
     * Idempotent so no Exception expected or needed
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function all(Request $request, Response $response);
    
    /**
     * @param $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function create(Request $request, Response $response);
    
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return ResponseInterface
     */
    public function update(Request $request, Response $response, $args);
    
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return ResponseInterface
     */
    public function delete(Request $request, Response $response, $args);
    
    /**
     * Idempotent so no Exception expected or needed
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return ResponseInterface
     */
    public function get(Request $request, Response $response, $args);
    
}