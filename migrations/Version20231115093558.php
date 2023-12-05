<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115093558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, duration TIME DEFAULT NULL, done TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE taches ADD session_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE taches ADD CONSTRAINT FK_3BF2CD98613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('CREATE INDEX IDX_3BF2CD98613FECDF ON taches (session_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taches DROP FOREIGN KEY FK_3BF2CD98613FECDF');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP INDEX IDX_3BF2CD98613FECDF ON taches');
        $this->addSql('ALTER TABLE taches DROP session_id');
    }
}
