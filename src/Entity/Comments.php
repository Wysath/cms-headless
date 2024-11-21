<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Doctrine\Traits\UuidTrait;
use App\Doctrine\Traits\TimestampableTrait;

#[ORM\Entity]
class Comments
{
    use UuidTrait, TimestampableTrait;

    public function __construct()
    {
        $this->defineUuid();
    }

    #[ORM\Column(type: 'text')]
    public ?string $comment;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_uuid', referencedColumnName: 'uuid', nullable: false)]
    public User $author;

    #[ORM\ManyToOne(targetEntity: Content::class)]
    #[ORM\JoinColumn(name: 'content_uuid', referencedColumnName: 'uuid', nullable: false, onDelete: 'CASCADE')]
    public Content $content;
}