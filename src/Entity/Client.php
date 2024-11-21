<?php

namespace App\Entity;

use App\Doctrine\TableEnum;
use App\Doctrine\Traits\TimestampableTrait;
use App\Doctrine\Traits\UuidTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: TableEnum::CLIENT)]
class Client
{
    use UuidTrait;
    use TimestampableTrait;

    public function __construct()
    {
        $this->defineUuid();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imagePath = null;

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
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
}
