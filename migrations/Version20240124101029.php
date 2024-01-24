<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124101029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE change_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE import_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE change_log (id INT NOT NULL, time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, item_code VARCHAR(40) NOT NULL, action VARCHAR(255) NOT NULL, details TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE import_log (id INT NOT NULL, time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status TEXT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE change_log_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE import_log_id_seq CASCADE');
        $this->addSql('DROP TABLE change_log');
        $this->addSql('DROP TABLE import_log');
    }
}
