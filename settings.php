<?php

define('APP_ROOT', __DIR__);

$doctrineConnection = require_once APP_ROOT . '/config/doctrine-conf.php';

return [
    'settings' => [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => false,
        
        'doctrine' => [
            // if true, metadata caching is forcefully disabled
            'dev_mode' => getenv('APP_ENVIRONMENT') === 'dev',
            
            // path where the compiled metadata info will be cached
            // make sure the path exists and it is writable
            'cache_dir' => APP_ROOT . '/var/doctrine',
            
            'metadata_dirs' => [APP_ROOT . '/src/Entities'],
            
            'connection' => $doctrineConnection
        ]
    ]
];

