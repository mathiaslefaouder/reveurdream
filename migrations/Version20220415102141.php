<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415102141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dream_user (dream_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(dream_id, user_id))');
        $this->addSql('CREATE INDEX IDX_F2E7836AE65343C2 ON dream_user (dream_id)');
        $this->addSql('CREATE INDEX IDX_F2E7836AA76ED395 ON dream_user (user_id)');
        $this->addSql('ALTER TABLE dream_user ADD CONSTRAINT FK_F2E7836AE65343C2 FOREIGN KEY (dream_id) REFERENCES dream (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dream_user ADD CONSTRAINT FK_F2E7836AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE dream_user');
    }
}
