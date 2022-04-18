<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418100028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ALTER ico SET NOT NULL');
        $this->addSql('ALTER TABLE dream DROP CONSTRAINT fk_6a5f004f12469de2');
        $this->addSql('DROP INDEX idx_6a5f004f12469de2');
        $this->addSql('ALTER TABLE dream DROP category_id');
        $this->addSql('ALTER TABLE theme DROP ico');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE theme ADD ico VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dream ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE dream ADD CONSTRAINT fk_6a5f004f12469de2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6a5f004f12469de2 ON dream (category_id)');
        $this->addSql('ALTER TABLE category ALTER ico DROP NOT NULL');
    }
}
