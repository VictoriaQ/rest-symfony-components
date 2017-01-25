<?php

namespace MyApi;
use Symfony\Component\Validator\Validation;

class ValidatorFactory
{
    public function buildValidator()
    {
        $validatorBuilder = Validation::createValidatorBuilder();
        $validatorBuilder->enableAnnotationMapping();
        return $validatorBuilder->getValidator();
    }
}
