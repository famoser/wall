<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200623152008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id CHAR(36) NOT NULL --(DC2Type:guid)
        , name CLOB NOT NULL, start_at DATETIME NOT NULL, created_at DATETIME NOT NULL, last_changed_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE product (id CHAR(36) NOT NULL --(DC2Type:guid)
        , name CLOB NOT NULL, category INTEGER NOT NULL, active BOOLEAN NOT NULL, created_at DATETIME NOT NULL, last_changed_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE question (id CHAR(36) NOT NULL --(DC2Type:guid)
        , text CLOB NOT NULL, repetition INTEGER NOT NULL, created_at DATETIME NOT NULL, last_changed_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE setting (id CHAR(36) NOT NULL --(DC2Type:guid)
        , "key" CLOB NOT NULL, value CLOB NOT NULL, created_at DATETIME NOT NULL, last_changed_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE task (id CHAR(36) NOT NULL --(DC2Type:guid)
        , name CLOB NOT NULL, interval_in_days INTEGER NOT NULL, reward INTEGER NOT NULL, last_execution_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, last_changed_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL --(DC2Type:guid)
        , name CLOB NOT NULL, pin INTEGER NOT NULL, karma INTEGER NOT NULL, created_at DATETIME NOT NULL, last_changed_at DATETIME NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE user');
    }
}
