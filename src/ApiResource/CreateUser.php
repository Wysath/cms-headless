<?php

namespace App\ApiResource;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUser
{
    #[Assert\NotBlank]
    public ?string $email = null;

    #[Assert\NotBlank]
    public ?string $password = null;
}
