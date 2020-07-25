<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200725092458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE embed (id CHAR(36) NOT NULL --(DC2Type:guid)
        , content CLOB NOT NULL, type INTEGER NOT NULL, created_at DATETIME NOT NULL, last_changed_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP INDEX IDX_DADD4A25A76ED395');
        $this->addSql('DROP INDEX IDX_DADD4A251E27F6BF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__answer AS SELECT id, question_id, user_id, value, given_at, created_at, last_changed_at FROM answer');
        $this->addSql('DROP TABLE answer');
        $this->addSql('CREATE TABLE answer (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , question_id CHAR(36) DEFAULT NULL COLLATE BINARY --(DC2Type:guid)
        , user_id CHAR(36) DEFAULT NULL COLLATE BINARY --(DC2Type:guid)
        , value INTEGER NOT NULL, given_at DATETIME NOT NULL, created_at DATETIME NOT NULL, last_changed_at DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DADD4A25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO answer (id, question_id, user_id, value, given_at, created_at, last_changed_at) SELECT id, question_id, user_id, value, given_at, created_at, last_changed_at FROM __temp__answer');
        $this->addSql('DROP TABLE __temp__answer');
        $this->addSql('CREATE INDEX IDX_DADD4A25A76ED395 ON answer (user_id)');
        $this->addSql('CREATE INDEX IDX_DADD4A251E27F6BF ON answer (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE embed');
        $this->addSql('DROP INDEX IDX_DADD4A251E27F6BF');
        $this->addSql('DROP INDEX IDX_DADD4A25A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__answer AS SELECT id, question_id, user_id, value, given_at, created_at, last_changed_at FROM answer');
        $this->addSql('DROP TABLE answer');
        $this->addSql('CREATE TABLE answer (id CHAR(36) NOT NULL --(DC2Type:guid)
        , question_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , user_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , value INTEGER NOT NULL, given_at DATETIME NOT NULL, created_at DATETIME NOT NULL, last_changed_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO answer (id, question_id, user_id, value, given_at, created_at, last_changed_at) SELECT id, question_id, user_id, value, given_at, created_at, last_changed_at FROM __temp__answer');
        $this->addSql('DROP TABLE __temp__answer');
        $this->addSql('CREATE INDEX IDX_DADD4A251E27F6BF ON answer (question_id)');
        $this->addSql('CREATE INDEX IDX_DADD4A25A76ED395 ON answer (user_id)');
    }
}
