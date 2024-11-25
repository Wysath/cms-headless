<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Api\Action\ImportClientAction;
use App\Doctrine\TableEnum;
use App\Doctrine\Traits\TimestampableTrait;
use App\Doctrine\Traits\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    order: ['createdAt' => 'ASC'],
    security: 'is_granted("ROLE_SUBSCRIBER)'
)]
#[Post(
    inputFormats: ['multipart' => ['multipart/form-data']],
    controller: ImportClientAction::class,
    security: 'is_granted("ROLE_ADMIN")',
    securityMessage: 'Vous n’avez pas l’autorisation pour effectuer cette action.',
    deserialize: false
)]
#[Get(
    security: 'is_granted("ROLE_ADMIN") or object.owner == user',
    securityMessage: 'Seuls les administrateurs ou le propriétaire peuvent consulter cet import.'
)]
#[Delete(
    security: 'is_granted("ROLE_ADMIN")',
    securityMessage: 'Seuls les administrateurs peuvent supprimer un import.'
)]
#[ORM\Entity]
#[ORM\Table(name: TableEnum::IMPORT)]
class Import
{
    use UuidTrait;
    use TimestampableTrait;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le chemin de l’image ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Groups(['import:read', 'import:write'])]
    private ?string $imagePath = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'owner_uuid', referencedColumnName: 'uuid', nullable: false)]
    private ?User $owner = null;

    public function __construct()
    {
        $this->defineUuid();
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;
        return $this;
    }
}
