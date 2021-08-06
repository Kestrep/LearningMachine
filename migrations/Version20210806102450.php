<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210806102450 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE card (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, subcategory_id INTEGER DEFAULT NULL, created_at DATETIME NOT NULL, front_main_content CLOB NOT NULL, front_subcontent CLOB DEFAULT NULL, back_main_content CLOB DEFAULT NULL, back_subcontent CLOB DEFAULT NULL, front_clue CLOB DEFAULT NULL, back_clue CLOB DEFAULT NULL, note CLOB DEFAULT NULL, stage INTEGER NOT NULL, play_at DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_161498D35DC6FE57 ON card (subcategory_id)');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_64C19C1A76ED395 ON category (user_id)');
        $this->addSql('CREATE TABLE subcategory (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_DDCA44812469DE2 ON subcategory (category_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, register_at DATETIME NOT NULL, last_connection_at DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE subcategory');
        $this->addSql('DROP TABLE user');
    }
}
