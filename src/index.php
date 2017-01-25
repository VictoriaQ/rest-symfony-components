<?php

namespace MyApi;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use MyApi\Entity\Recipe;
use MyApi\Serializer;

require_once __DIR__.'/../bootstrap.php';

$request = Request::createFromGlobals();
$content = $request->getContent();

$serializerFactory = new SerializerFactory();
$serializer = $serializerFactory->buildSerializer();

$recipe = $serializer->deserialize($content, Recipe::class, 'json');

$entityManager->persist($recipe);
$entityManager->flush();

//$response = new JsonResponse($serializer->normalize($recipe), 201);
$groups = ['groups' => ['overview']];
$response = new Response($serializer->serialize($recipe, 'json', $groups), 201);

$response->headers->set('Content-Type', 'application/json');
$response->headers->set('Location', '/myapi/recipes/'.$recipe->getId());

$response->send();
