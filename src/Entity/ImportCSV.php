<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Api\Processor\CreateContentProcessor;
use App\ApiResource\CreateContent;
use App\Doctrine\TableEnum;
use App\Doctrine\Traits\TimestampableTrait;
use App\Doctrine\Traits\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
#[ApiResource(order: ['createdAt' => 'ASC'])]
#[Get]
#[GetCollection]
#[Post(security: 'is_granted("ROLE_ADMIN")', input: CreateContent::class, processor: CreateContentProcessor::class)]
#[Delete(security: 'is_granted("ROLE_ADMIN")')]
#[ORM\Entity]
#[ORM\Table(name: TableEnum::IMPORT_CSV)]
class ImportCSV
{
    use UuidTrait;
    use TimestampableTrait;

    public function __construct()
    {
        $this->defineUuid();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imagePath = null;


    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }
}
