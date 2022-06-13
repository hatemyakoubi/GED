<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220416125054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acteur (id INT AUTO_INCREMENT NOT NULL, identifiant VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE acteur_contrat (acteur_id INT NOT NULL, contrat_id INT NOT NULL, INDEX IDX_CAF423F1DA6F574A (acteur_id), INDEX IDX_CAF423F11823061F (contrat_id), PRIMARY KEY(acteur_id, contrat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE acteur_contrat ADD CONSTRAINT FK_CAF423F1DA6F574A FOREIGN KEY (acteur_id) REFERENCES acteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE acteur_contrat ADD CONSTRAINT FK_CAF423F11823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acteur_contrat DROP FOREIGN KEY FK_CAF423F1DA6F574A');
        $this->addSql('DROP TABLE acteur');
        $this->addSql('DROP TABLE acteur_contrat');
    }
}
