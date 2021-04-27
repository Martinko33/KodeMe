<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210427122931 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classes ADD themes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE classes ADD CONSTRAINT FK_2ED7EC594F4A9D2 FOREIGN KEY (themes_id) REFERENCES theme (id)');
        $this->addSql('CREATE INDEX IDX_2ED7EC594F4A9D2 ON classes (themes_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classes DROP FOREIGN KEY FK_2ED7EC594F4A9D2');
        $this->addSql('DROP INDEX IDX_2ED7EC594F4A9D2 ON classes');
        $this->addSql('ALTER TABLE classes DROP themes_id');
    }
}
