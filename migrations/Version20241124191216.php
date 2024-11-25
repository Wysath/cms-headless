<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241124191216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `primary` ON import');
        $this->addSql('ALTER TABLE import ADD id INT NOT NULL, CHANGE image_path image_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE import ADD PRIMARY KEY (id, uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `PRIMARY` ON import');
        $this->addSql('ALTER TABLE import DROP id, CHANGE image_path image_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE import ADD PRIMARY KEY (image_path, uuid)');
    }
}
