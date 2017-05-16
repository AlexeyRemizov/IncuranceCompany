<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170304145231 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE letters (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, username LONGTEXT DEFAULT NULL, email LONGTEXT DEFAULT NULL, location LONGTEXT DEFAULT NULL, created DATETIME DEFAULT NULL, weather LONGTEXT DEFAULT NULL, status INT NOT NULL, INDEX IDX_FB5524FBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(60) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', avatar VARCHAR(60) NOT NULL, avatar_thumbnail VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), UNIQUE INDEX UNIQ_1483A5E91677722F (avatar), UNIQUE INDEX UNIQ_1483A5E94C530588 (avatar_thumbnail), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE letters ADD CONSTRAINT FK_FB5524FBA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE letters DROP FOREIGN KEY FK_FB5524FBA76ED395');
        $this->addSql('DROP TABLE letters');
        $this->addSql('DROP TABLE users');
    }
}
