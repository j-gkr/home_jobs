<?php


namespace App\Validator\Constraints;


use App\Entity\Group;
use App\Form\Request\GroupRequest;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class UniqueGroupValidator
 *
 * @package App\Validator\Constraints
 */
class UniqueGroupValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UniqueGroupValidator constructor.
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
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     * @throws NonUniqueResultException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueGroup) {
            throw new UnexpectedTypeException($constraint, UniqueUser::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value) {
            return;
        }

        if (!($value instanceof GroupRequest)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, GroupRequest::class);

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        if ($value->getId() === null) {
            $exists = $this->entityManager->getRepository(Group::class)->loadGroupByName($value->getName());
        } else {
            $exists = $this->entityManager->getRepository(Group::class)->loadGroupByNameAndNotId($value->getName(), $value->getId());
        }

        if ($exists !== null) {
            $this->context->buildViolation($constraint->message)
                ->atPath('name')
                ->setParameter('{{ name }}', $value->getName())
                ->addViolation();
        }
    }
}