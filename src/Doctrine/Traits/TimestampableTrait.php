<?php declare(strict_types=1);

namespace App\Doctrine\Traits;

use ApiPlatform\Metadata\ApiProperty;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait TimestampableTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false, insertable: false, updatable: false, options: ['default' => 'CURRENT_TIMESTAMP', 'onUpdate' => 'CURRENT_TIMESTAMP'], generated: 'INSERT')]
    #[ApiProperty(readable: true, writable: false)]
    public ?DateTime $createdAt = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false, insertable: false, updatable: false, options: ['default' => 'CURRENT_TIMESTAMP', 'onUpdate' => 'CURRENT_TIMESTAMP'], generated: 'ALWAYS')]
    #[ApiProperty(readable: true, writable: false)]
    public ?DateTime $updatedAt = null;
}