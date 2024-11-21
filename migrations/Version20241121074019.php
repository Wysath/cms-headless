<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121074019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments (uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', author_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', content_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', comment LONGTEXT NOT NULL, INDEX IDX_5F9E962A3590D879 (author_uuid), INDEX IDX_5F9E962A1C1DBD63 (content_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content (uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', author_uuid BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, cover_image VARCHAR(255) DEFAULT NULL, meta_description LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, tags JSON NOT NULL, UNIQUE INDEX UNIQ_FEC530A9989D9B62 (slug), INDEX IDX_FEC530A93590D879 (author_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A3590D879 FOREIGN KEY (author_uuid) REFERENCES user (uuid)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A1C1DBD63 FOREIGN KEY (content_uuid) REFERENCES content (uuid) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A93590D879 FOREIGN KEY (author_uuid) REFERENCES user (uuid) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A3590D879');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A1C1DBD63');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A93590D879');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE content');
    }
}
