<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230927111417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE module_sub_module (module_id INT NOT NULL, sub_module_id INT NOT NULL, INDEX IDX_2B9042F9AFC2B591 (module_id), INDEX IDX_2B9042F9BDCFF32F (sub_module_id), PRIMARY KEY(module_id, sub_module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module_sub_module ADD CONSTRAINT FK_2B9042F9AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_sub_module ADD CONSTRAINT FK_2B9042F9BDCFF32F FOREIGN KEY (sub_module_id) REFERENCES sub_module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6A40BC2D5');
        $this->addSql('DROP INDEX IDX_D499BFF6A40BC2D5 ON planning');
        $this->addSql('ALTER TABLE planning CHANGE schedule_id shedule_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF66C68AE55 FOREIGN KEY (shedule_id) REFERENCES shedule (id)');
        $this->addSql('CREATE INDEX IDX_D499BFF66C68AE55 ON planning (shedule_id)');
        $this->addSql('ALTER TABLE sub_module DROP FOREIGN KEY FK_BE756964AFC2B591');
        $this->addSql('DROP INDEX IDX_BE756964AFC2B591 ON sub_module');
        $this->addSql('ALTER TABLE sub_module DROP module_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module_sub_module DROP FOREIGN KEY FK_2B9042F9AFC2B591');
        $this->addSql('ALTER TABLE module_sub_module DROP FOREIGN KEY FK_2B9042F9BDCFF32F');
        $this->addSql('DROP TABLE module_sub_module');
        $this->addSql('ALTER TABLE sub_module ADD module_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sub_module ADD CONSTRAINT FK_BE756964AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('CREATE INDEX IDX_BE756964AFC2B591 ON sub_module (module_id)');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF66C68AE55');
        $this->addSql('DROP INDEX IDX_D499BFF66C68AE55 ON planning');
        $this->addSql('ALTER TABLE planning CHANGE shedule_id schedule_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES shedule (id)');
        $this->addSql('CREATE INDEX IDX_D499BFF6A40BC2D5 ON planning (schedule_id)');
    }
}
