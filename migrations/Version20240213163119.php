<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213163119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actualite CHANGE titre titre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE favoris CHANGE le_user_id le_user_id INT DEFAULT NULL, CHANGE le_produit_id le_produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43288A1A5E2 FOREIGN KEY (le_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C4322C340150 FOREIGN KEY (le_produit_id) REFERENCES produits (id)');
        $this->addSql('CREATE INDEX IDX_8933C43288A1A5E2 ON favoris (le_user_id)');
        $this->addSql('CREATE INDEX IDX_8933C4322C340150 ON favoris (le_produit_id)');
        $this->addSql('ALTER TABLE user CHANGE photo_url photo_url VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE photo_url photo_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43288A1A5E2');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C4322C340150');
        $this->addSql('DROP INDEX IDX_8933C43288A1A5E2 ON favoris');
        $this->addSql('DROP INDEX IDX_8933C4322C340150 ON favoris');
        $this->addSql('ALTER TABLE favoris CHANGE le_user_id le_user_id INT NOT NULL, CHANGE le_produit_id le_produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE actualite CHANGE titre titre LONGTEXT NOT NULL');
    }
}
