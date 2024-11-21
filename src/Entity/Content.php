<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Doctrine\Traits\UuidTrait;
use App\Doctrine\Traits\TimestampableTrait;

#[ORM\Entity]

class Content
{
    use UuidTrait, TimestampableTrait;

    public function __construct()
    {
        $this->defineUuid();
    }

    #[ORM\Column(type: 'string', length: 255)]
    public ?string $title = null;

    #[ORM\Column(type: 'text')]
    public ?string $content = null;

    #[ORM\Column(type: 'string', length: 255)]
    public ?string $metaTitle = null;

    #[ORM\Column(nullable: true)]
    public ?string $coverImage = null;

    #[ORM\Column(type: 'text')]
    public ?string $metaDescription = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    public ?string $slug;

    #[ORM\Column(type: 'json')]
    public array $tags = [];

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_uuid', referencedColumnName: 'uuid', onDelete: 'SET NULL')]
    public ?User $author = null;
}