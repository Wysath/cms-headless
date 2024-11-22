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
use Symfony\Component\Uid\Uuid;

#[ApiResource(order: ['createdAt' => 'ASC'])]
#[ORM\Entity]
#[ORM\Table(name: TableEnum::UPLOAD)]
#[Get]
#[Post(controller: UploadAction::class, deserialize: false)]
class Upload
{
    use UuidTrait;
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public ?Uuid $uuid = null;

    #[ORM\Column(type: 'string', length: 255)]
    public ?string $path = null;

    public function __construct()
    {
        $this->defineUuid();
        $this->initializeTimestamps();
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    private function initializeTimestamps()
    {
    }
}