<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251024165641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ats_project ADD COLUMN active BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__ats_project AS SELECT id, manager_id, name, created_at, updated_at FROM ats_project');
        $this->addSql('DROP TABLE ats_project');
        $this->addSql('CREATE TABLE ats_project (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, manager_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_F288CC1C783E3463 FOREIGN KEY (manager_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ats_project (id, manager_id, name, created_at, updated_at) SELECT id, manager_id, name, created_at, updated_at FROM __temp__ats_project');
        $this->addSql('DROP TABLE __temp__ats_project');
        $this->addSql('CREATE INDEX IDX_F288CC1C783E3463 ON ats_project (manager_id)');
    }
}
