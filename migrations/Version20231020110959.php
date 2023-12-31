<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231020110959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, user_id INT DEFAULT NULL, information_id INT DEFAULT NULL, anonymous_token VARCHAR(255) DEFAULT NULL, place VARCHAR(60) NOT NULL, method_payment VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_BA388B7DE12AB56 (created_by), INDEX IDX_BA388B716FE72E1 (updated_by), INDEX IDX_BA388B7A76ED395 (user_id), UNIQUE INDEX UNIQ_BA388B72EF03101 (information_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_item (id INT AUTO_INCREMENT NOT NULL, module_id INT NOT NULL, schedule_id INT NOT NULL, cart_id INT DEFAULT NULL, module_name VARCHAR(255) NOT NULL, occurence_id VARCHAR(255) NOT NULL, module_date_time DATETIME NOT NULL, price INT NOT NULL, quantity INT NOT NULL, location VARCHAR(255) DEFAULT NULL, schedule_name VARCHAR(255) NOT NULL, main_module LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', sub_modules LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_F0FE2527AFC2B591 (module_id), INDEX IDX_F0FE25271AD5CDBF (cart_id), INDEX IDX_F0FE2527A40BC2D5 (schedule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(191) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(191) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), INDEX general_translations_lookup_idx (object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE information (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, address1 VARCHAR(38) NOT NULL, address2 VARCHAR(38) DEFAULT NULL, address3 VARCHAR(38) DEFAULT NULL, post_code VARCHAR(50) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price INT NOT NULL, nb_place_by_schedule INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_sub_module (module_id INT NOT NULL, sub_module_id INT NOT NULL, INDEX IDX_2B9042F9AFC2B591 (module_id), INDEX IDX_2B9042F9BDCFF32F (sub_module_id), PRIMARY KEY(module_id, sub_module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, client_email VARCHAR(255) DEFAULT NULL, client_id VARCHAR(255) DEFAULT NULL, total_amount INT DEFAULT NULL, currency_code VARCHAR(255) DEFAULT NULL, details LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_token (hash VARCHAR(255) NOT NULL, details LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', after_url LONGTEXT DEFAULT NULL, target_url LONGTEXT NOT NULL, gateway_name VARCHAR(255) NOT NULL, PRIMARY KEY(hash)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, module_id INT DEFAULT NULL, schedule_id INT DEFAULT NULL, monday_times LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', wenesday_times LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', friday_times LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_D499BFF6AFC2B591 (module_id), INDEX IDX_D499BFF6A40BC2D5 (schedule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sub_module (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, information_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6492EF03101 (information_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7DE12AB56 FOREIGN KEY (created_by) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B716FE72E1 FOREIGN KEY (updated_by) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B72EF03101 FOREIGN KEY (information_id) REFERENCES information (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
        $this->addSql('ALTER TABLE module_sub_module ADD CONSTRAINT FK_2B9042F9AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_sub_module ADD CONSTRAINT FK_2B9042F9BDCFF32F FOREIGN KEY (sub_module_id) REFERENCES sub_module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6492EF03101 FOREIGN KEY (information_id) REFERENCES information (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7DE12AB56');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B716FE72E1');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B72EF03101');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE2527AFC2B591');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25271AD5CDBF');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE2527A40BC2D5');
        $this->addSql('ALTER TABLE module_sub_module DROP FOREIGN KEY FK_2B9042F9AFC2B591');
        $this->addSql('ALTER TABLE module_sub_module DROP FOREIGN KEY FK_2B9042F9BDCFF32F');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6AFC2B591');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6A40BC2D5');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6492EF03101');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE information');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE module_sub_module');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE payment_token');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE sub_module');
        $this->addSql('DROP TABLE `user`');
    }
}
