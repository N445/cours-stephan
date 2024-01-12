<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240112115326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item CHANGE sub_modules sub_modules JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE module ADD number INT DEFAULT NULL, ADD color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment CHANGE details details JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE planning CHANGE monday_times monday_times JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE wenesday_times wenesday_times JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE friday_times friday_times JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module DROP number, DROP color');
        $this->addSql('ALTER TABLE payment CHANGE details details JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE planning CHANGE monday_times monday_times JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE wenesday_times wenesday_times JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE friday_times friday_times JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE cart_item CHANGE sub_modules sub_modules JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE `user` CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
