<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200608133735 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gps_histo DROP FOREIGN KEY FK_7D868BF0A76ED395');
        $this->addSql('DROP INDEX IDX_7D868BF0A76ED395 ON gps_histo');
        $this->addSql('ALTER TABLE gps_histo ADD id_user INT DEFAULT NULL, DROP user_id, DROP dte_install, CHANGE date_update date_update DATETIME NOT NULL, CHANGE dte_update date_install DATETIME NOT NULL');
        $this->addSql('ALTER TABLE gps_histo ADD CONSTRAINT FK_7D868BF06B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7D868BF06B3CA4B ON gps_histo (id_user)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gps_histo DROP FOREIGN KEY FK_7D868BF06B3CA4B');
        $this->addSql('DROP INDEX IDX_7D868BF06B3CA4B ON gps_histo');
        $this->addSql('ALTER TABLE gps_histo ADD user_id INT NOT NULL, ADD dte_install VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP id_user, CHANGE date_update date_update VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE date_install dte_update DATETIME NOT NULL');
        $this->addSql('ALTER TABLE gps_histo ADD CONSTRAINT FK_7D868BF0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7D868BF0A76ED395 ON gps_histo (user_id)');
    }
}
