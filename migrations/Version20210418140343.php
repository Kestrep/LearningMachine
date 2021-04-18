<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210418140343 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__card AS SELECT id, created_at, front_main_content, front_subcontent, back_main_content, back_subcontent, front_clue, back_clue, note, stage FROM card');
        $this->addSql('DROP TABLE card');
        $this->addSql('CREATE TABLE card (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sub_category_id INTEGER DEFAULT NULL, created_at DATETIME NOT NULL, front_main_content CLOB NOT NULL COLLATE BINARY, front_subcontent CLOB DEFAULT NULL COLLATE BINARY, back_main_content CLOB DEFAULT NULL COLLATE BINARY, back_subcontent CLOB DEFAULT NULL COLLATE BINARY, front_clue CLOB DEFAULT NULL COLLATE BINARY, back_clue CLOB DEFAULT NULL COLLATE BINARY, note CLOB DEFAULT NULL COLLATE BINARY, stage INTEGER NOT NULL, CONSTRAINT FK_161498D3F7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO card (id, created_at, front_main_content, front_subcontent, back_main_content, back_subcontent, front_clue, back_clue, note, stage) SELECT id, created_at, front_main_content, front_subcontent, back_main_content, back_subcontent, front_clue, back_clue, note, stage FROM __temp__card');
        $this->addSql('DROP TABLE __temp__card');
        $this->addSql('CREATE INDEX IDX_161498D3F7BFE87C ON card (sub_category_id)');
        $this->addSql('DROP INDEX IDX_64C19C1A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category AS SELECT id, user_id, name FROM category');
        $this->addSql('DROP TABLE category');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_64C19C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO category (id, user_id, name) SELECT id, user_id, name FROM __temp__category');
        $this->addSql('DROP TABLE __temp__category');
        $this->addSql('CREATE INDEX IDX_64C19C1A76ED395 ON category (user_id)');
        $this->addSql('DROP INDEX IDX_BCE3F79812469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sub_category AS SELECT id, category_id, name FROM sub_category');
        $this->addSql('DROP TABLE sub_category');
        $this->addSql('CREATE TABLE sub_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_BCE3F79812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sub_category (id, category_id, name) SELECT id, category_id, name FROM __temp__sub_category');
        $this->addSql('DROP TABLE __temp__sub_category');
        $this->addSql('CREATE INDEX IDX_BCE3F79812469DE2 ON sub_category (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_161498D3F7BFE87C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__card AS SELECT id, created_at, front_main_content, front_subcontent, back_main_content, back_subcontent, front_clue, back_clue, note, stage FROM card');
        $this->addSql('DROP TABLE card');
        $this->addSql('CREATE TABLE card (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL, front_main_content CLOB NOT NULL, front_subcontent CLOB DEFAULT NULL, back_main_content CLOB DEFAULT NULL, back_subcontent CLOB DEFAULT NULL, front_clue CLOB DEFAULT NULL, back_clue CLOB DEFAULT NULL, note CLOB DEFAULT NULL, stage INTEGER NOT NULL)');
        $this->addSql('INSERT INTO card (id, created_at, front_main_content, front_subcontent, back_main_content, back_subcontent, front_clue, back_clue, note, stage) SELECT id, created_at, front_main_content, front_subcontent, back_main_content, back_subcontent, front_clue, back_clue, note, stage FROM __temp__card');
        $this->addSql('DROP TABLE __temp__card');
        $this->addSql('DROP INDEX IDX_64C19C1A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category AS SELECT id, user_id, name FROM category');
        $this->addSql('DROP TABLE category');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO category (id, user_id, name) SELECT id, user_id, name FROM __temp__category');
        $this->addSql('DROP TABLE __temp__category');
        $this->addSql('CREATE INDEX IDX_64C19C1A76ED395 ON category (user_id)');
        $this->addSql('DROP INDEX IDX_BCE3F79812469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sub_category AS SELECT id, category_id, name FROM sub_category');
        $this->addSql('DROP TABLE sub_category');
        $this->addSql('CREATE TABLE sub_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO sub_category (id, category_id, name) SELECT id, category_id, name FROM __temp__sub_category');
        $this->addSql('DROP TABLE __temp__sub_category');
        $this->addSql('CREATE INDEX IDX_BCE3F79812469DE2 ON sub_category (category_id)');
    }
}
