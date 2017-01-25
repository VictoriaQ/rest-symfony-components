<?php

namespace MyApi;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
// For annotations
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

class SerializerFactory
{
    public function buildSerializer()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $encoders = [new JsonEncoder(), new XmlEncoder()];
        $normalizers = [new ObjectNormalizer($classMetadataFactory), new ArrayDenormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        return $serializer;
    }
}


