<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240206160817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commander CHANGE prixretenu prixretenu DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE commandes ADD etat VARCHAR(255) DEFAULT NULL, CHANGE montant_total montant_total DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commander CHANGE prixretenu prixretenu DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE commandes DROP etat, CHANGE montant_total montant_total DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
    }
}
