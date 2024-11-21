<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UnregistredEmail extends Constraint
{
    public string $message = 'L\'email "{{ string }}" est déjà lié à un utilisateur.';

    public function __construct(
        ?string $message = null,
        ?array $groups = null,
                $payload = null
    ) {
        parent::__construct([], $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}
