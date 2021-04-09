<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401080612 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin ADD name VARCHAR(50) DEFAULT NULL, ADD firstname VARCHAR(50) DEFAULT NULL, ADD date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD name VARCHAR(50) DEFAULT NULL, ADD firstname VARCHAR(50) DEFAULT NULL, ADD date DATE DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin DROP name, DROP firstname, DROP date');
        $this->addSql('ALTER TABLE customer DROP name, DROP firstname, DROP date');
    }
}
