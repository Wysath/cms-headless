<?php

namespace App\Entity;

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use App\Doctrine\TableEnum;
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
#[Get]
/**#[Get(security: 'is_granted("ROLE_USER") or object == user')]**/
#[Put(security: 'is_granted("ROLE_ADMIN")')]
#[Delete(security: 'is_granted("ROLE_ADMIN")')]
#[Post(input: CreateUser::class, processor: CreateUserProcessor::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UuidTrait;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotNull]
    #[Assert\Email]
    #[UnregistredEmail]
    public ?string $email = null;

    /**
     * @var array<string>
     */
    #[ORM\Column(type: 'json')]
    public array $roles = [];

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 255)]
    public ?string $password = null;

    public function __construct()
    {
        $this->defineUuid();
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
        return $this;
    }

    public function removeRole(string $role): self
    {
        $this->roles = array_filter($this->roles, fn($r) => $r !== $role);
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function eraseCredentials(): void
    {
    }

    public function isAdmin(): bool
    {
        return in_array('ROLE_ADMIN', $this->roles, true);
    }

    public function isSubscriber(): bool
    {
        return in_array('ROLE_SUBSCRIBER', $this->roles, true);
    }

    public function isVisitor(): bool
    {
        return empty($this->roles) || $this->roles === ['ROLE_USER'];
    }
}

