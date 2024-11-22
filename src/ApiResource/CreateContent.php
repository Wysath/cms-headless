<?php

namespace App\ApiResource;

use Symfony\Component\Validator\Constraints as Assert;

class CreateContent
{
    public ?string $title = null;
    public ?string $coverImage = null;
    public ?string $metaDescription = null;

    /**
     * @var string[]
     */
    public array $tags = [];

}
