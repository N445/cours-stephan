<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231002115425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address1 VARCHAR(38) NOT NULL, address2 VARCHAR(38) DEFAULT NULL, address3 VARCHAR(38) DEFAULT NULL, post_code VARCHAR(50) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE planning CHANGE monday_times monday_times LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', CHANGE wenesday_times wenesday_times LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', CHANGE friday_times friday_times LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE user ADD address_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F5B7AF75 ON user (address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649F5B7AF75');
        $this->addSql('DROP TABLE address');
        $this->addSql('ALTER TABLE planning CHANGE monday_times monday_times LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE wenesday_times wenesday_times LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE friday_times friday_times LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('DROP INDEX UNIQ_8D93D649F5B7AF75 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP address_id, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
