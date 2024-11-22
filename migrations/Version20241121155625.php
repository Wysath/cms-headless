<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121155625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_17BDE61FD17F50A6 ON upload');
        $this->addSql('DROP INDEX `primary` ON upload');
        $this->addSql('ALTER TABLE upload ADD PRIMARY KEY (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `PRIMARY` ON upload');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_17BDE61FD17F50A6 ON upload (uuid)');
        $this->addSql('ALTER TABLE upload ADD PRIMARY KEY (path, uuid)');
    }
}
