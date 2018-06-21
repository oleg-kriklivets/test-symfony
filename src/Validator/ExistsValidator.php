<?php

namespace App\Validator;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ExistsValidator extends ConstraintValidator
{
    /**
     * @var ManagerRegistry
     */
    protected $manager;

    /**
     * ExistsValidator constructor.
     * @param ManagerRegistry $manager
     */
    public function __construct(ManagerRegistry $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint->entity) {
            throw new \Exception('entity is not defined');
        }
        if ($value) {
            $row = $this->manager->getRepository($constraint->entity)->findOneBy([$constraint->key => $value]);
            if (!$row) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $value)
                    ->setParameter('{{ entity }}', $constraint->entity)
                    ->setParameter('{{ key }}', $constraint->key)
                    ->addViolation();
                return;
            }
        }
    }
}
