<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170304151515 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_1483A5E91677722F ON users');
        $this->addSql('DROP INDEX UNIQ_1483A5E94C530588 ON users');
        $this->addSql('ALTER TABLE users CHANGE avatar avatar VARCHAR(60) DEFAULT NULL, CHANGE avatar_thumbnail avatar_thumbnail VARCHAR(60) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users CHANGE avatar avatar VARCHAR(60) NOT NULL COLLATE utf8_unicode_ci, CHANGE avatar_thumbnail avatar_thumbnail VARCHAR(60) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E91677722F ON users (avatar)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E94C530588 ON users (avatar_thumbnail)');
    }
}
