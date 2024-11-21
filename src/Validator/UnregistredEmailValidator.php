<?php

namespace App\Validator;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UnregistredEmailValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UnregistredEmail) {
            throw new UnexpectedTypeException($constraint, UnregistredEmail::class);
        }

        if (null === $value || '' === $value) {
            return; // Pas de validation nécessaire pour une valeur vide
        }

        // Vérifier si la valeur peut être convertie en chaîne
        if (!is_scalar($value) && !($value instanceof \Stringable)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        // Vérifier si l'email existe dans la base
        if ($this->entityManager->getRepository(User::class)->findOneBy(['email' => (string) $value])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', (string) $value)
                ->addViolation();
        }
    }
}
