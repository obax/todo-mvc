<?php

// cli-config.php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Slim\Container;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

/** @var Container $container */
$settings = require_once __DIR__ . '/settings.php';

$config = Setup::createAnnotationMetadataConfiguration(
    $settings['settings']['doctrine']['metadata_dirs'],
    $settings['settings']['doctrine']['dev_mode']
);

$config->setMetadataDriverImpl(
    new AnnotationDriver(
        new AnnotationReader,
        $settings['settings']['doctrine']['metadata_dirs']
    )
);

$config->setMetadataCacheImpl(
    new FilesystemCache(
        $settings['settings']['doctrine']['cache_dir']
    )
);

$em = EntityManager::create(
    $settings['settings']['doctrine']['connection'],
    $config
);


ConsoleRunner::run(
    ConsoleRunner::createHelperSet($em)
);

