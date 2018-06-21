<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Exists extends Constraint
{
    public $entity;

    public $key = 'id';

    public $message = '{{ entity }} with {{ key }} = {{ value }} not exists.';


    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}
