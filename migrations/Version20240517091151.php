<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240517091151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE date (id INT AUTO_INCREMENT NOT NULL, evenement_id INT DEFAULT NULL, date DATE NOT NULL, places_restantes INT NOT NULL, INDEX IDX_AA9E377AFD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, lieu VARCHAR(255) DEFAULT NULL, annule TINYINT(1) NOT NULL, raison_annulation VARCHAR(255) DEFAULT NULL, age_requis INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_B26681EC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date_id INT DEFAULT NULL, nombre_personnes INT DEFAULT NULL, certif_age_requis TINYINT(1) DEFAULT NULL, date_inscription DATE NOT NULL, INDEX IDX_5E90F6D6A76ED395 (user_id), INDEX IDX_5E90F6D6B897366B (date_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE date ADD CONSTRAINT FK_AA9E377AFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6B897366B FOREIGN KEY (date_id) REFERENCES date (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE date DROP FOREIGN KEY FK_AA9E377AFD02F13');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EC54C8C93');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6A76ED395');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6B897366B');
        $this->addSql('DROP TABLE date');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE inscription');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
    }
}
