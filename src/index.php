<?php


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__.'/../bootstrap.php';

$request = Request::createFromGlobals();
$content = json_decode($request->getContent(), true);

$entityManager->persist($product);
$entityManager->flush();

// Create our resource

$response = new JsonResponse(null, 201);
$response->headers->set('Location', '/myapi/recipes/1');

$response->send();
