<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223070808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires CHANGE commentaire commentaire VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE images ADD image_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reserver RENAME INDEX idx_f5e95dc188a1a5e2 TO IDX_B9765E9388A1A5E2');
        $this->addSql('ALTER TABLE reserver RENAME INDEX idx_f5e95dc17ca1290e TO IDX_B9765E937CA1290E');
        $this->addSql('ALTER TABLE reserver RENAME INDEX idx_f5e95dc13743edd TO IDX_B9765E933743EDD');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserver RENAME INDEX idx_b9765e937ca1290e TO IDX_F5E95DC17CA1290E');
        $this->addSql('ALTER TABLE reserver RENAME INDEX idx_b9765e933743edd TO IDX_F5E95DC13743EDD');
        $this->addSql('ALTER TABLE reserver RENAME INDEX idx_b9765e9388a1a5e2 TO IDX_F5E95DC188A1A5E2');
        $this->addSql('ALTER TABLE images DROP image_name');
        $this->addSql('ALTER TABLE commentaires CHANGE commentaire commentaire TEXT NOT NULL');
    }
}
