<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209083032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actualite (id INT AUTO_INCREMENT NOT NULL, titre LONGTEXT NOT NULL, texte LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C4322C340150');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43288A1A5E2');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE partenaires');
        $this->addSql('ALTER TABLE commander DROP prixretenu');
        $this->addSql('ALTER TABLE commandes DROP etat');
        $this->addSql('ALTER TABLE images ADD la_actualite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A366195E7 FOREIGN KEY (la_actualite_id) REFERENCES actualite (id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6A366195E7 ON images (la_actualite_id)');
        $this->addSql('ALTER TABLE planning CHANGE heure_debut heure_début TIME NOT NULL');
        $this->addSql('ALTER TABLE produits DROP disponible');
        $this->addSql('ALTER TABLE user DROP fidelite, DROP photo_url');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A366195E7');
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, le_user_id INT DEFAULT NULL, le_produit_id INT DEFAULT NULL, INDEX IDX_8933C43288A1A5E2 (le_user_id), INDEX IDX_8933C4322C340150 (le_produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE partenaires (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, logo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C4322C340150 FOREIGN KEY (le_produit_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43288A1A5E2 FOREIGN KEY (le_user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE actualite');
        $this->addSql('DROP INDEX IDX_E01FBE6A366195E7 ON images');
        $this->addSql('ALTER TABLE images DROP la_actualite_id');
        $this->addSql('ALTER TABLE commander ADD prixretenu DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE user ADD fidelite INT NOT NULL, ADD photo_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE commandes ADD etat VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produits ADD disponible VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE planning CHANGE heure_début heure_debut TIME NOT NULL');
    }
}
