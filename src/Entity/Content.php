<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Doctrine\Traits\UuidTrait;
use App\Doctrine\Traits\TimestampableTrait;
use App\Doctrine\TableEnum;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Api\Processor\CreateContentProcessor;
use App\ApiResource\CreateContent;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Cocur\Slugify\Slugify;
use ApiPlatform\Metadata\ApiFilter;

#[ApiResource(order: ['createdAt' => 'ASC'])]
#[Get]
#[GetCollection]
#[Post(security: 'is_granted("ROLE_ADMIN")', input: CreateContent::class, processor: CreateContentProcessor::class)]
#[Put(security: 'is_granted("ROLE_ADMIN") and object.author == user')]
#[Delete(security: 'is_granted("ROLE_ADMIN")')]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial'])]
#[ORM\Entity]
#[ORM\Table(name: TableEnum::CONTENT)]
class Content
{
    use UuidTrait;
    use TimestampableTrait;

    public function __construct()
    {
        $this->defineUuid();
    }

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public ?string $title = null;

    #[ORM\Column(nullable: true)]
    public ?string $coverImage = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    public ?string $metaDescription = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    public ?string $slug;

    public function setTitle(string $title): self
    {
        $this->title = $title;
        $this->setSlug($title);
        return $this;
    }

    public function setSlug(string $title): self
    {
        $slugify = new Slugify();
        $slug = $slugify->slugify($title);

        // Ensure the slug is unique
        $uniqueSlug = $this->ensureUniqueSlug($slug);
        $this->slug = $uniqueSlug;

        return $this;
    }

    private function ensureUniqueSlug(string $slug): string
    {
        $uniqueSlug = $slug;
        $counter = 1;

        while ($this->slugExists($uniqueSlug)) {
            $uniqueSlug = $slug . '-' . $counter;
            $counter++;
        }

        return $uniqueSlug;
    }

    private function slugExists(string $slug): bool
    {
        return false;
    }

    #[ORM\Column(type: 'json')]
    public array $tags = [];

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_uuid', referencedColumnName: 'uuid', onDelete: 'SET NULL')]
    #[ApiProperty(writable: false)]
    public ?User $author = null;

}
