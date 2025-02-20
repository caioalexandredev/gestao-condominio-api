<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211040859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create usuario table with foreign key to pessoa';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE usuario (
            id INT AUTO_INCREMENT NOT NULL,
            pessoa_id INT NOT NULL,
            ativo TINYINT(1) NOT NULL,
            INDEX IDX_USUARIO_PESSOA_ID (pessoa_id),
            PRIMARY KEY(id),
            CONSTRAINT FK_USUARIO_PESSOA FOREIGN KEY (pessoa_id) REFERENCES pessoa (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE usuario;');
    }
}
