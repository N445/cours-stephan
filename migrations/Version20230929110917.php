<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230929110917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7DE12AB56 FOREIGN KEY (created_by) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B716FE72E1 FOREIGN KEY (updated_by) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_BA388B7DE12AB56 ON cart (created_by)');
        $this->addSql('CREATE INDEX IDX_BA388B716FE72E1 ON cart (updated_by)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7DE12AB56');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B716FE72E1');
        $this->addSql('DROP INDEX IDX_BA388B7DE12AB56 ON cart');
        $this->addSql('DROP INDEX IDX_BA388B716FE72E1 ON cart');
        $this->addSql('ALTER TABLE cart CHANGE created_by created_by VARCHAR(255) DEFAULT NULL, CHANGE updated_by updated_by VARCHAR(255) DEFAULT NULL');
    }
}
