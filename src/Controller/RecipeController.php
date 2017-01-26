<?php

namespace MyApi\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;


use MyApi\Entity\Recipe;
use MyApi\Form\Type\RecipeType;
use MyApi\Serializer\SerializerFactory;
use MyApi\ValidatorFactory;


class RecipeController
{
    public $dummyContainer;

    public function post(Request $request)
    {
        $serializerFactory = new SerializerFactory();
        $serializer = $serializerFactory->buildSerializer();

        $validatorFactory = new ValidatorFactory();
        $validator = $validatorFactory->buildValidator();

        $formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new ValidatorExtension($validator))
            ->getFormFactory();

        $data = json_decode($request->getContent(), true);
        $recipe = new Recipe();
        $form = $formFactory->create(RecipeType::class, $recipe);
        $form->submit($data);

        if (!$form->isValid()) {
            $response = new Response($serializer->serialize($form, 'json'), 400);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $entityManager = $this->dummyContainer['entityManager'];
        $entityManager->persist($recipe);
        $entityManager->flush();

        //$response = new JsonResponse($serializer->normalize($recipe), 201);
        $groups = ['groups' => ['overview']];
        $response = new Response($serializer->serialize($recipe, 'json', $groups), 201);

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Location', '/myapi/recipes/'.$recipe->getId());

        return $response;
    }

    public function get(Request $request, $id)
    {
        $serializerFactory = new SerializerFactory();
        $serializer = $serializerFactory->buildSerializer();

        $entityManager = $this->dummyContainer['entityManager'];
        $recipes = $entityManager->getRepository('MyApi\Entity\Recipe')->find($id);

        $groups = ['groups' => ['detail']];
        $response = new Response($serializer->serialize($recipes, 'json', $groups), 201);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
