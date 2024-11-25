<?php

namespace App\ApiResource;

use App\Entity\Import;
use Symfony\Component\Validator\Constraints as Assert;

class CreateImport
{
    public ?string $path = null;

}
