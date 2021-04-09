<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402162836 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE support DROP FOREIGN KEY FK_8004EBA59E225B24');
        $this->addSql('DROP INDEX IDX_8004EBA59E225B24 ON support');
        $this->addSql('ALTER TABLE support DROP classes_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE support ADD classes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE support ADD CONSTRAINT FK_8004EBA59E225B24 FOREIGN KEY (classes_id) REFERENCES classes (id)');
        $this->addSql('CREATE INDEX IDX_8004EBA59E225B24 ON support (classes_id)');
    }
}
