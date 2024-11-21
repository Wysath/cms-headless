<?php

namespace App\Doctrine\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

trait UuidTrait
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: "NONE")]

    public ?Uuid $uuid = null;

    public function defineUuid(): void
    {
        $this->uuid ??= Uuid::v4();
    }
}
