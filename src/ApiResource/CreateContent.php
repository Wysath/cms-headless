<?php

namespace App\ApiResource;

class CreateContent
{
    public ?string $title = null;
    public ?string $coverImage = null;
    public ?string $metaDescription = null;
    public array $tags = [];

}
