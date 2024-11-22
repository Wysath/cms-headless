<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Api\Processor\CreateCommentProcessor;
use App\ApiResource\CreateContent;
use App\Doctrine\TableEnum;
use Doctrine\ORM\Mapping as ORM;
use App\Doctrine\Traits\UuidTrait;
use App\Doctrine\Traits\TimestampableTrait;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Api\Processor\CreateContentProcessor;
use App\ApiResource\CreateComment;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
#[Get]
#[GetCollection]
#[Post(security: 'is_granted("ROLE_USER")', input: CreateComment::class, processor: CreateCommentProcessor::class)]
#[Put(security: 'is_granted("ROLE_ADMIN") and object.author == user')]
#[Delete(security: 'is_granted("ROLE_USER") and object.author == user')]
#[ORM\Entity]
#[ORM\Table(name: TableEnum::COMMENTS)]
class Comments
{
    use UuidTrait;
    use TimestampableTrait;
    public function __construct()
    {
        $this->defineUuid();
    }

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    public string $comment;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_uuid', referencedColumnName: 'uuid', nullable: false)]
    public User $author;

    #[ORM\ManyToOne(targetEntity: Content::class)]
    #[ORM\JoinColumn(name: 'content_uuid', referencedColumnName: 'uuid', nullable: false, onDelete: 'CASCADE')]
    public Content $content;



}
