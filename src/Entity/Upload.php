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


#[ApiResource(order: ['createdAt' => 'ASC'])]
#[ORM\Entity]
#[ORM\Table(name: TableEnum::UPLOAD)]
#[Get]
#[Post(controller: UploadAction::class, deserialize: false)]
class Upload
{
    use UuidTrait, TimestampableTrait;

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