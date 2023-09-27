<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230927110033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shedule (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module DROP days, DROP hours');
        $this->addSql('ALTER TABLE planning ADD schedule_id INT DEFAULT NULL, ADD module_id INT DEFAULT NULL, ADD monday_times LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', ADD wenesday_times LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', ADD friday_times LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', DROP start_at, DROP end_at, DROP name');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES shedule (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('CREATE INDEX IDX_D499BFF6A40BC2D5 ON planning (schedule_id)');
        $this->addSql('CREATE INDEX IDX_D499BFF6AFC2B591 ON planning (module_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6A40BC2D5');
        $this->addSql('DROP TABLE shedule');
        $this->addSql('ALTER TABLE module ADD days LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD hours LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6AFC2B591');
        $this->addSql('DROP INDEX IDX_D499BFF6A40BC2D5 ON planning');
        $this->addSql('DROP INDEX IDX_D499BFF6AFC2B591 ON planning');
        $this->addSql('ALTER TABLE planning ADD start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD name VARCHAR(255) NOT NULL, DROP schedule_id, DROP module_id, DROP monday_times, DROP wenesday_times, DROP friday_times');
    }
}
