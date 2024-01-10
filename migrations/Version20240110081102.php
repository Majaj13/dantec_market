<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110081102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_promo (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commander (id INT AUTO_INCREMENT NOT NULL, la_commande_id INT DEFAULT NULL, le_produit_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_42D318BA3743EDD (la_commande_id), INDEX IDX_42D318BA2C340150 (le_produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, le_user_id INT DEFAULT NULL, date_commande DATETIME NOT NULL, montant_total DOUBLE PRECISION NOT NULL, INDEX IDX_35D4282C88A1A5E2 (le_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, le_user_id INT DEFAULT NULL, le_produit_id INT DEFAULT NULL, commentaire VARCHAR(255) NOT NULL, note DOUBLE PRECISION NOT NULL, date_commentaire DATETIME NOT NULL, INDEX IDX_D9BEC0C488A1A5E2 (le_user_id), INDEX IDX_D9BEC0C42C340150 (le_produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, jour DATETIME NOT NULL, heure_début TIME NOT NULL, heure_fin TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, la_categorie_id INT DEFAULT NULL, nom_produit VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, quantite_disponible INT NOT NULL, INDEX IDX_BE2DDF8C281042B9 (la_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits_images (produits_id INT NOT NULL, images_id INT NOT NULL, INDEX IDX_710EA589CD11A2CF (produits_id), INDEX IDX_710EA589D44F05E5 (images_id), PRIMARY KEY(produits_id, images_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo (id INT AUTO_INCREMENT NOT NULL, le_produit_id INT DEFAULT NULL, la_categorie_promo_id INT DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_B0139AFB2C340150 (le_produit_id), INDEX IDX_B0139AFBCDCCDE00 (la_categorie_promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE réserver (id INT AUTO_INCREMENT NOT NULL, le_user_id INT DEFAULT NULL, le_planning_id INT DEFAULT NULL, la_commande_id INT DEFAULT NULL, date DATE NOT NULL, heure TIME NOT NULL, INDEX IDX_F5E95DC188A1A5E2 (le_user_id), INDEX IDX_F5E95DC17CA1290E (le_planning_id), INDEX IDX_F5E95DC13743EDD (la_commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, classe VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commander ADD CONSTRAINT FK_42D318BA3743EDD FOREIGN KEY (la_commande_id) REFERENCES commandes (id)');
        $this->addSql('ALTER TABLE commander ADD CONSTRAINT FK_42D318BA2C340150 FOREIGN KEY (le_produit_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C88A1A5E2 FOREIGN KEY (le_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C488A1A5E2 FOREIGN KEY (le_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C42C340150 FOREIGN KEY (le_produit_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8C281042B9 FOREIGN KEY (la_categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE produits_images ADD CONSTRAINT FK_710EA589CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_images ADD CONSTRAINT FK_710EA589D44F05E5 FOREIGN KEY (images_id) REFERENCES images (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo ADD CONSTRAINT FK_B0139AFB2C340150 FOREIGN KEY (le_produit_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE promo ADD CONSTRAINT FK_B0139AFBCDCCDE00 FOREIGN KEY (la_categorie_promo_id) REFERENCES categorie_promo (id)');
        $this->addSql('ALTER TABLE réserver ADD CONSTRAINT FK_F5E95DC188A1A5E2 FOREIGN KEY (le_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE réserver ADD CONSTRAINT FK_F5E95DC17CA1290E FOREIGN KEY (le_planning_id) REFERENCES planning (id)');
        $this->addSql('ALTER TABLE réserver ADD CONSTRAINT FK_F5E95DC13743EDD FOREIGN KEY (la_commande_id) REFERENCES commandes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commander DROP FOREIGN KEY FK_42D318BA3743EDD');
        $this->addSql('ALTER TABLE commander DROP FOREIGN KEY FK_42D318BA2C340150');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C88A1A5E2');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C488A1A5E2');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C42C340150');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8C281042B9');
        $this->addSql('ALTER TABLE produits_images DROP FOREIGN KEY FK_710EA589CD11A2CF');
        $this->addSql('ALTER TABLE produits_images DROP FOREIGN KEY FK_710EA589D44F05E5');
        $this->addSql('ALTER TABLE promo DROP FOREIGN KEY FK_B0139AFB2C340150');
        $this->addSql('ALTER TABLE promo DROP FOREIGN KEY FK_B0139AFBCDCCDE00');
        $this->addSql('ALTER TABLE réserver DROP FOREIGN KEY FK_F5E95DC188A1A5E2');
        $this->addSql('ALTER TABLE réserver DROP FOREIGN KEY FK_F5E95DC17CA1290E');
        $this->addSql('ALTER TABLE réserver DROP FOREIGN KEY FK_F5E95DC13743EDD');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categorie_promo');
        $this->addSql('DROP TABLE commander');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE produits_images');
        $this->addSql('DROP TABLE promo');
        $this->addSql('DROP TABLE réserver');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
