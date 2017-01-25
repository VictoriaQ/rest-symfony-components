<?php

namespace MyApi;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use MyApi\Entity\Recipe;

require_once __DIR__.'/../bootstrap.php';

$request = Request::createFromGlobals();
$content = json_decode($request->getContent(), true)['recipe'];

$recipe = new Recipe();
$recipe->setName($content['name']);
$recipe->setEnergy($content['energy']);
$recipe->setServings($content['servings']);

$entityManager->persist($recipe);
$entityManager->flush();

$response = new JsonResponse(null, 201);
$response->headers->set('Location', '/myapi/recipes/'.$recipe->getId());

$response->send();
