<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191209100732 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE tbl_category_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE tbl_anwser_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tbl_anwser (id INT NOT NULL, text TEXT NOT NULL, correct TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE tbl_category');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tbl_anwser_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE tbl_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tbl_category (id INT NOT NULL, text TEXT NOT NULL, correct TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE tbl_anwser');
    }
}
