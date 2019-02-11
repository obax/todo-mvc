<?php

use Slim\App;
use TodoApi\Controller\TodoItemController;
use TodoApi\Controller\TodoListController;

use Slim\Container;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

require_once __DIR__ . '/../vendor/autoload.php';

$c = new Container(require __DIR__ . '/../settings.php');
$app = new App($c);

$container = $app->getContainer();

$container[EntityManager::class] = function (Container $container): EntityManager {
    $config = Setup::createAnnotationMetadataConfiguration(
        $container['settings']['doctrine']['metadata_dirs'],
        $container['settings']['doctrine']['dev_mode']
    );
    
    $config->setMetadataDriverImpl(
        new AnnotationDriver(
            new AnnotationReader,
            $container['settings']['doctrine']['metadata_dirs']
        )
    );
    
    $config->setMetadataCacheImpl(
        new FilesystemCache(
            $container['settings']['doctrine']['cache_dir']
        )
    );
    
    return EntityManager::create(
        $container['settings']['doctrine']['connection'],
        $config
    );
};

$app->get('/doc', function() {
    return file_get_contents(__DIR__ . '/index.html');
});

$app->get('/v1/lists', TodoListController::class . ':all');
$app->post('/v1/lists', TodoListController::class . ':create');
$app->get('/v1/lists/{id}', TodoListController::class . ':get');
$app->put('/v1/lists/{id}', TodoListController::class . ':update');
$app->delete('/v1/lists/{id}', TodoListController::class . ':delete');

$app->get('/v1/items', TodoItemController::class . ':all');
$app->post('/v1/items', TodoItemController::class . ':create');
$app->get('/v1/items/{id}', TodoItemController::class . ':get');
$app->put('/v1/items/{id}', TodoItemController::class . ':update');
$app->delete('/v1/items/{id}', TodoItemController::class . ':delete');

$app->run();