<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200608003220 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE gps_histo (id INT AUTO_INCREMENT NOT NULL, id_android INT NOT NULL, ip_wan VARCHAR(255) NOT NULL, ip_mac VARCHAR(255) NOT NULL, nom_user VARCHAR(255) NOT NULL, nom_machine VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, dte_update DATETIME NOT NULL, date_update VARCHAR(255) NOT NULL, ip_lan VARCHAR(255) NOT NULL, dte_install VARCHAR(255) NOT NULL, latitude_gps DOUBLE PRECISION NOT NULL, longitude_gps DOUBLE PRECISION NOT NULL, altitude_gps DOUBLE PRECISION NOT NULL, accuaracy_gps DOUBLE PRECISION NOT NULL, provider_gps VARCHAR(255) NOT NULL, bearing_gps DOUBLE PRECISION NOT NULL, speed_gps DOUBLE PRECISION NOT NULL, elapsedrealtimeannos_gps VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE gps_histo');
    }
}
