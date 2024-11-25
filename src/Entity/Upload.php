<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Api\Action\UploadAction;
use App\Doctrine\TableEnum;
use App\Doctrine\Traits\TimestampableTrait;
use App\Doctrine\Traits\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    order: ['createdAt' => 'ASC'],
    security: 'is_granted("ROLE_USER")'
)]
#[Get(security: 'is_granted("ROLE_ADMIN") or object.owner == user',
    securityMessage: 'Vous ne pouvez consulter que vos propres fichiers ou être administrateur.')]
#[Post(
    controller: UploadAction::class,
    security: 'is_granted("ROLE_USER")',
    securityMessage: 'Seuls les utilisateurs connectés peuvent télécharger des fichiers.',
    deserialize: false
)]
#[ORM\Entity]
#[ORM\Table(name: TableEnum::UPLOAD)]
class Upload
{
    use UuidTrait;
    use TimestampableTrait;

    public function __construct()
    {
        $this->defineUuid();
    }

    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Le chemin du fichier est obligatoire.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le chemin du fichier ne peut pas dépasser {{ limit }} caractères.'
    )]
    public ?string $path = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'owner_uuid', referencedColumnName: 'uuid', nullable: false)]
    public User $owner;

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}
