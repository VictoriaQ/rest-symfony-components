<?php

namespace MyApi;

require_once __DIR__.'/../bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing;

$routes = new RouteCollection();
$routes->add('recipe_post', new Route('/recipes', [
    '_controller' => [new Controller\RecipeController(), 'post']
], [], [], '', [], ['POST']));
$routes->add('recipe_get', new Route('/recipes/{id}', [], [], [], '', [], ['GET']));

$request = Request::createFromGlobals();
$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);
try {
    $request->attributes->add($matcher->match($request->getPathInfo()));
    $response = call_user_func($request->attributes->get('_controller'), $request, $entityManager);
} catch ( Routing\Exception\ResourceNotFoundException $e ) {
    $response = new Response('Not Found', 404);
} catch (Exception $e) {
    $response = new Response('An error occurred', 500);
}
$response->send();
