<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220423081738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD reseller_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E0991E6A19D FOREIGN KEY (reseller_id) REFERENCES reseller (id)');
        $this->addSql('CREATE INDEX IDX_81398E0991E6A19D ON customer (reseller_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E0991E6A19D');
        $this->addSql('DROP INDEX IDX_81398E0991E6A19D ON customer');
        $this->addSql('ALTER TABLE customer DROP reseller_id');
    }
}
