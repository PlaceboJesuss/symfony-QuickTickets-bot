<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260419194757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, type VARCHAR(50) NOT NULL, token VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_user (chat_id INT NOT NULL, username VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, messenger_id INT NOT NULL, INDEX IDX_3DE52A69676C7AF5 (messenger_id), PRIMARY KEY (chat_id, messenger_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE performance (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, place_id INT NOT NULL, INDEX IDX_82D79681DA6A219 (place_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE place_messenger_user (place_id INT NOT NULL, chat_id INT NOT NULL, messenger_id INT NOT NULL, INDEX IDX_16E31C45DA6A219 (place_id), INDEX IDX_16E31C451A9A7125676C7AF5 (chat_id, messenger_id), PRIMARY KEY (place_id, chat_id, messenger_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, time DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_sold_out TINYINT NOT NULL, performance_id INT NOT NULL, INDEX IDX_D044D5D4B91ADEEE (performance_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_user ADD CONSTRAINT FK_3DE52A69676C7AF5 FOREIGN KEY (messenger_id) REFERENCES messenger (id)');
        $this->addSql('ALTER TABLE performance ADD CONSTRAINT FK_82D79681DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE place_messenger_user ADD CONSTRAINT FK_16E31C45DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE place_messenger_user ADD CONSTRAINT FK_16E31C451A9A7125676C7AF5 FOREIGN KEY (chat_id, messenger_id) REFERENCES messenger_user (chat_id, messenger_id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4B91ADEEE FOREIGN KEY (performance_id) REFERENCES performance (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messenger_user DROP FOREIGN KEY FK_3DE52A69676C7AF5');
        $this->addSql('ALTER TABLE performance DROP FOREIGN KEY FK_82D79681DA6A219');
        $this->addSql('ALTER TABLE place_messenger_user DROP FOREIGN KEY FK_16E31C45DA6A219');
        $this->addSql('ALTER TABLE place_messenger_user DROP FOREIGN KEY FK_16E31C451A9A7125676C7AF5');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4B91ADEEE');
        $this->addSql('DROP TABLE messenger');
        $this->addSql('DROP TABLE messenger_user');
        $this->addSql('DROP TABLE performance');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE place_messenger_user');
        $this->addSql('DROP TABLE session');
    }
}
