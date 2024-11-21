<?php

namespace App\Entity;

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use App\Doctrine\TableEnum;
use App\Doctrine\Traits\TimestampableTrait;
use App\Doctrine\Traits\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Post;
use App\Api\Processor\CreateUserProcessor;
use App\ApiResource\CreateUser;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\UnregistredEmail;

#[ORM\Entity]
#[ORM\Table(name: TableEnum::USER)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[GetCollection]
#[GetCollection(security: 'is_granted("ROLE_ADMIN")')]
#[Get(security: 'is_granted("ROLE_ADMIN")')]
#[Put(security: 'is_granted("ROLE_ADMIN")')]
#[Delete(security: 'is_granted("ROLE_ADMIN")')]
#[Post(security: 'is_granted("ROLE_ADMIN")', input: CreateUser::class, processor: CreateUserProcessor::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UuidTrait;

    #[ORM\Column(length: 180)]
    #[Assert\NotNull]
    #[Assert\Email]
    #[UnregistredEmail]
    public ?string $email = null;

    /**
     * @var array<string>
     */
    #[ORM\Column(type: 'json')]
    public array $roles = [];



    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(string $role): self
    {
        $this->roles = array_filter($this->roles, fn ($r) => $r !== $role);

        return $this;
    }

    #[ORM\Column]
    public ?string $password = null;

    public function __construct()
    {
        $this->defineUuid();
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function eraseCredentials(): void
    {
    }

    public function setEmail(mixed $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $hashPassword): void
    {
        $this->password = $hashPassword;
    }
}
