<?php

namespace MyApi;

require_once __DIR__.'/../bootstrap.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing;

// BD CONFIG
// Do not use simple annotations, now we have an annotation loader registered, so pass false on last argument
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/Entity"), true, null, null, false);

// database configuration parameters
$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/../var/db.sqlite',
);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);

// This is why we use real frameworks. In a real application you should
// provide access to a Dependency Injection Container or something you fancy
$dummyContainer = ['entityManager' => $entityManager];

// ROUTES CONFIG
$routes = new RouteCollection();
$routes->add('recipe_post', new Route('/recipes', ['_controller' => 'MyApi\Controller\RecipeController::post'], [], [], '', [], ['POST']));
$routes->add('recipe_get', new Route('/recipes/{id}', [ '_controller' => 'MyApi\Controller\RecipeController::get' ], [], [], '', [], ['GET']));
$routes->add('login_check', new Route('/login_check', [], [], [], '', [], ['POST']));

$request = Request::createFromGlobals();
$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

$framework = new NanoFramework($matcher, $dummyContainer);
$response = $framework->handle($request);

$response->send();
