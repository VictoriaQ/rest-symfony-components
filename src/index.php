<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$content = json_decode($request->getContent(), true);

//print_r($content);

//$input = $request->get('name', 'World');

$response = new JsonResponse(null, 201);
$response->headers->set('Location', '/myapi/recipes/1');

//$response = new JsonResponse(['a' => 1], 201);

$response->send();
