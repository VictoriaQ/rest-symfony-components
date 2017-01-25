<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/** @var ClassLoader $loader */
$loader = require __DIR__.'/vendor/autoload.php';

// Load Annotations with use and namespaces
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

// Do not use simple annotations, now we have an annotation loader registered, so pass false on last argument
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src/Entity"), true, null, null, false);

// database configuration parameters
$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/var/db.sqlite',
);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
