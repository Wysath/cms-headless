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

#[ApiResource(
    order: ['createdAt' => 'ASC'],
    security: 'is_granted("ROLE_SUBSCRIBER)'
)]
#[Get(security: 'is_granted("ROLE_ADMIN") or object.owner == user',
    securityMessage: 'Vous ne pouvez consulter que vos propres fichiers ou être administrateur.')]
#[Post(
    controller: UploadAction::class,
    security: 'is_granted("ROLE_SUBSCRIBER")',
    securityMessage: 'Seuls les utilisateurs connectés peuvent télécharger des fichiers.',
    deserialize: false
)]
#[ORM\Entity]
#[ORM\Table(name: TableEnum::UPLOAD)]
#[Get]
#[Post(controller: UploadAction::class, deserialize: false)]
class Upload
{
    use UuidTrait;
    use TimestampableTrait;

    public function __construct()
    {
        $this->defineUuid();

    }


    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    public ?string $path = null;

    public function getUuid(): ?string
    {
        return $this->uuid;
    }
}
