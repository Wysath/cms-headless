<?php

namespace App\ApiResource;

use App\Entity\Content;
use Symfony\Component\Validator\Constraints as Assert;

class CreateComment
{
    #[Assert\NotBlank]
    public string $comment;

    #[Assert\NotNull]
    public Content $content;
}
