<?php


namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueGroup
 * @package App\Validator\Constraints
 *
 * @Annotation
 */
class UniqueGroup extends Constraint
{
    public $message = 'Der Gruppenname "{{ name }}" wird bereits verwendet.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}