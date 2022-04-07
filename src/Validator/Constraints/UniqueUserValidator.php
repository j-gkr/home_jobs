<?php


namespace App\Validator\Constraints;


use App\Entity\User;
use App\Form\Request\UserRequest;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class UniqueUserValidator
 *
 * @package App\Validator\Constraints
 */
class UniqueUserValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UniqueUserValidator constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param UserRequest $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @throws NonUniqueResultException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueUser) {
            throw new UnexpectedTypeException($constraint, UniqueUser::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value) {
            return;
        }

        if (!($value instanceof UserRequest)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, UserRequest::class);

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        if ($value->getId() === null) {
            $exists = $this->entityManager->getRepository(User::class)->loadUserByUsername($value->getUsername());
        } else {
            $exists = $this->entityManager->getRepository(User::class)->loadByUsernameAndNotId($value->getUsername(), $value->getId());
        }


        if ($exists !== null) {
            $this->context->buildViolation($constraint->message)
                ->atPath('username')
                ->setParameter('{{ email }}', $value->getUsername())
                ->addViolation();
        }
    }
}