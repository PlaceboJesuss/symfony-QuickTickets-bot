<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260429185657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE place_messenger_user DROP FOREIGN KEY FK_16E31C451A9A7125676C7AF5');

        // 2. меняем chat_id на BIGINT
        $this->addSql('ALTER TABLE messenger_user MODIFY chat_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE place_messenger_user MODIFY chat_id BIGINT NOT NULL');

        // 3. делаем username nullable
        $this->addSql('ALTER TABLE messenger_user MODIFY username VARCHAR(255) DEFAULT NULL');

        // 4. возвращаем FK обратно
        $this->addSql(
            'ALTER TABLE place_messenger_user ADD CONSTRAINT FK_16E31C451A9A7125676C7AF5 FOREIGN KEY (chat_id, messenger_id) REFERENCES messenger_user (chat_id, messenger_id)'
        );
    }

    public function down(Schema $schema): void
    {
        // 1. убираем FK (как в up)
        $this->addSql('ALTER TABLE place_messenger_user DROP FOREIGN KEY FK_16E31C451A9A7125676C7AF5');

        // 2. откатываем chat_id обратно в INT
        $this->addSql('ALTER TABLE place_messenger_user MODIFY chat_id INT NOT NULL');
        $this->addSql('ALTER TABLE messenger_user MODIFY chat_id INT NOT NULL');

        // 3. username снова NOT NULL
        $this->addSql('ALTER TABLE messenger_user MODIFY username VARCHAR(255) NOT NULL');

        // 4. возвращаем FK обратно
        $this->addSql(
            'ALTER TABLE place_messenger_user ADD CONSTRAINT FK_16E31C451A9A7125676C7AF5 FOREIGN KEY (chat_id, messenger_id) REFERENCES messenger_user (chat_id, messenger_id)'
        );
    }
}
