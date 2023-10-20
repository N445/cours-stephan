<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231020111950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE information CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE phone_number phone_number VARCHAR(255) DEFAULT NULL, CHANGE address1 address1 VARCHAR(38) DEFAULT NULL, CHANGE post_code post_code VARCHAR(50) DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE country country VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE information CHANGE name name VARCHAR(255) NOT NULL, CHANGE phone_number phone_number VARCHAR(255) NOT NULL, CHANGE address1 address1 VARCHAR(38) NOT NULL, CHANGE post_code post_code VARCHAR(50) NOT NULL, CHANGE city city VARCHAR(255) NOT NULL, CHANGE country country VARCHAR(255) NOT NULL');
    }
}
