<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/** @var ClassLoader $loader */
$loader = require __DIR__.'/vendor/autoload.php';

// Load Annotations with use and namespaces
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

