<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415101655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE dream_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE theme_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(255) NOT NULL, ico TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE dream (id INT NOT NULL, author_id INT DEFAULT NULL, category_id INT NOT NULL, theme_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, gps TEXT DEFAULT NULL, is_draft BOOLEAN NOT NULL, number_view INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A5F004FF675F31B ON dream (author_id)');
        $this->addSql('CREATE INDEX IDX_6A5F004F12469DE2 ON dream (category_id)');
        $this->addSql('CREATE INDEX IDX_6A5F004F59027487 ON dream (theme_id)');
        $this->addSql('COMMENT ON COLUMN dream.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN dream.gps IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE theme (id INT NOT NULL, name VARCHAR(255) NOT NULL, ico VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE dream ADD CONSTRAINT FK_6A5F004FF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dream ADD CONSTRAINT FK_6A5F004F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dream ADD CONSTRAINT FK_6A5F004F59027487 FOREIGN KEY (theme_id) REFERENCES theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE dream DROP CONSTRAINT FK_6A5F004F12469DE2');
        $this->addSql('ALTER TABLE dream DROP CONSTRAINT FK_6A5F004F59027487');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE dream_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE theme_id_seq CASCADE');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE dream');
        $this->addSql('DROP TABLE theme');
    }
}
