<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211211231303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE appartement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bien_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contrat_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE facture_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE localisation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE maison_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE operation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE proprietaire_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reglement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE studio_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE terrain_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE appartement (id INT NOT NULL, bien_id INT NOT NULL, type_bail VARCHAR(255) NOT NULL, montant_caution NUMERIC(10, 0) NOT NULL, loyer_mensuel NUMERIC(10, 0) NOT NULL, nbr_chambre INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_71A6BD8DBD95B80F ON appartement (bien_id)');
        $this->addSql('CREATE TABLE bien (id INT NOT NULL, contrat_id INT DEFAULT NULL, agent_id INT NOT NULL, localisation_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, numero VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_45EDC3861823061F ON bien (contrat_id)');
        $this->addSql('CREATE INDEX IDX_45EDC3863414710B ON bien (agent_id)');
        $this->addSql('CREATE INDEX IDX_45EDC386C68BE09C ON bien (localisation_id)');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE contrat (id INT NOT NULL, operation_id INT DEFAULT NULL, reference VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6034999344AC3583 ON contrat (operation_id)');
        $this->addSql('CREATE TABLE facture (id INT NOT NULL, operation_id INT DEFAULT NULL, agent_id INT DEFAULT NULL, num VARCHAR(255) NOT NULL, date DATE NOT NULL, montant NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE86641044AC3583 ON facture (operation_id)');
        $this->addSql('CREATE INDEX IDX_FE8664103414710B ON facture (agent_id)');
        $this->addSql('CREATE TABLE localisation (id INT NOT NULL, rue VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL, localiste VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE maison (id INT NOT NULL, bien_id INT DEFAULT NULL, prix VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F90CB66DBD95B80F ON maison (bien_id)');
        $this->addSql('CREATE TABLE operation (id INT NOT NULL, client_id INT DEFAULT NULL, bien_id INT DEFAULT NULL, date DATE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1981A66D19EB6921 ON operation (client_id)');
        $this->addSql('CREATE INDEX IDX_1981A66DBD95B80F ON operation (bien_id)');
        $this->addSql('CREATE TABLE proprietaire (id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, numero VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reglement (id INT NOT NULL, facture_id INT DEFAULT NULL, agent_id INT NOT NULL, num VARCHAR(255) NOT NULL, date DATE NOT NULL, montant NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EBE4C14C7F2DEE08 ON reglement (facture_id)');
        $this->addSql('CREATE INDEX IDX_EBE4C14C3414710B ON reglement (agent_id)');
        $this->addSql('CREATE TABLE studio (id INT NOT NULL, bien_id INT NOT NULL, montant_caution NUMERIC(10, 0) NOT NULL, loyer_mensuel NUMERIC(10, 0) NOT NULL, superficie NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A2B07B6BD95B80F ON studio (bien_id)');
        $this->addSql('CREATE TABLE terrain (id INT NOT NULL, bien_id INT NOT NULL, prix NUMERIC(10, 0) NOT NULL, superficie NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C87653B1BD95B80F ON terrain (bien_id)');
        $this->addSql('ALTER TABLE appartement ADD CONSTRAINT FK_71A6BD8DBD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC3861823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC3863414710B FOREIGN KEY (agent_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC386C68BE09C FOREIGN KEY (localisation_id) REFERENCES localisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_6034999344AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641044AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664103414710B FOREIGN KEY (agent_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE maison ADD CONSTRAINT FK_F90CB66DBD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DBD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reglement ADD CONSTRAINT FK_EBE4C14C7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reglement ADD CONSTRAINT FK_EBE4C14C3414710B FOREIGN KEY (agent_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE studio ADD CONSTRAINT FK_4A2B07B6BD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B1BD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE appartement DROP CONSTRAINT FK_71A6BD8DBD95B80F');
        $this->addSql('ALTER TABLE maison DROP CONSTRAINT FK_F90CB66DBD95B80F');
        $this->addSql('ALTER TABLE operation DROP CONSTRAINT FK_1981A66DBD95B80F');
        $this->addSql('ALTER TABLE studio DROP CONSTRAINT FK_4A2B07B6BD95B80F');
        $this->addSql('ALTER TABLE terrain DROP CONSTRAINT FK_C87653B1BD95B80F');
        $this->addSql('ALTER TABLE operation DROP CONSTRAINT FK_1981A66D19EB6921');
        $this->addSql('ALTER TABLE bien DROP CONSTRAINT FK_45EDC3861823061F');
        $this->addSql('ALTER TABLE reglement DROP CONSTRAINT FK_EBE4C14C7F2DEE08');
        $this->addSql('ALTER TABLE bien DROP CONSTRAINT FK_45EDC386C68BE09C');
        $this->addSql('ALTER TABLE contrat DROP CONSTRAINT FK_6034999344AC3583');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT FK_FE86641044AC3583');
        $this->addSql('DROP SEQUENCE appartement_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bien_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contrat_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE facture_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE localisation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE maison_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE operation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE proprietaire_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reglement_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE studio_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE terrain_id_seq CASCADE');
        $this->addSql('DROP TABLE appartement');
        $this->addSql('DROP TABLE bien');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE contrat');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE localisation');
        $this->addSql('DROP TABLE maison');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE proprietaire');
        $this->addSql('DROP TABLE reglement');
        $this->addSql('DROP TABLE studio');
        $this->addSql('DROP TABLE terrain');
    }
}
