<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Api\Processor\CreateCommentProcessor;
use App\ApiResource\CreateComment;
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
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
#[Get]
#[GetCollection]
#[Post(
    security: 'is_granted("ROLE_SUBSCRIBER") or is_granted("ROLE_ADMIN")',
    input: CreateComment::class,
    processor: CreateCommentProcessor::class
)]
#[Put(
    security: '(is_granted("ROLE_SUBSCRIBER") and object.author == user) or is_granted("ROLE_ADMIN")',
    securityMessage: 'Vous devez être abonné et l auteur du commentaire, ou administrateur.'
)]
#[Delete(
    security: 'is_granted("ROLE_SUBSCRIBER") and object.author == user',
    securityMessage: 'Vous ne pouvez supprimer que vos propres commentaires.'
)]
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
    #[Assert\NotBlank(message: 'Le commentaire ne peut pas être vide.')]
    #[Groups(['comment:read', 'comment:write'])]
    public string $comment;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_uuid', referencedColumnName: 'uuid', nullable: false)]
    #[Groups(['comment:read', 'comment:write'])]
    public User $author;

    #[ORM\ManyToOne(targetEntity: Content::class)]
    #[ORM\JoinColumn(name: 'content_uuid', referencedColumnName: 'uuid', nullable: false, onDelete: 'CASCADE')]
    #[Groups(['comment:read', 'comment:write'])]
    public Content $content;
}
