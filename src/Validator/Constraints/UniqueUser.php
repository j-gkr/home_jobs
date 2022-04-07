<?php


namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * Class UniqueUser
 * @package App\Validator
 */
class UniqueUser extends Constraint
{
    public $message = 'Die E-Mail "{{ email }}" wird bereits verwendet.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}