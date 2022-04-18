<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418100126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dream ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dream ADD CONSTRAINT FK_6A5F004F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6A5F004F12469DE2 ON dream (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE dream DROP CONSTRAINT FK_6A5F004F12469DE2');
        $this->addSql('DROP INDEX IDX_6A5F004F12469DE2');
        $this->addSql('ALTER TABLE dream DROP category_id');
    }
}
