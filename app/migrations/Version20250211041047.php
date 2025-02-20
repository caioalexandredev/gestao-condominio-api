<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211041047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação da tabela endereco';
    }

    public function up(Schema $schema): void
    {
        // Criação da tabela endereco
        $this->addSql('CREATE TABLE endereco (
            id INT AUTO_INCREMENT NOT NULL,
            cep VARCHAR(20) NOT NULL,
            cidade_id INT NOT NULL,
            logradouro VARCHAR(255) NOT NULL,
            bairro VARCHAR(255) NOT NULL,
            numero VARCHAR(50) NOT NULL,
            complemento VARCHAR(255) DEFAULT NULL,
            usuario_id INT NOT NULL,
            dt_inclusao DATE NOT NULL,
            PRIMARY KEY(id),
            CONSTRAINT FK_CIDADE FOREIGN KEY (cidade_id) REFERENCES cidade (id),
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE endereco;');
    }
}
