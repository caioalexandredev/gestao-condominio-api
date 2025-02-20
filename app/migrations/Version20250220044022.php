<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220044022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação das tabelas endereco e senha';
    }

    public function up(Schema $schema): void
    {
        // Criação da tabela senha
        $this->addSql('CREATE TABLE senha (
            id INT AUTO_INCREMENT NOT NULL,
            hash VARCHAR(255) NOT NULL,
            ativo TINYINT(1) NOT NULL,
            dt_cadastro DATETIME NOT NULL,
            usuario_id INT NOT NULL,
            PRIMARY KEY(id),
            CONSTRAINT FK_USUARIO_SENHA FOREIGN KEY (usuario_id) REFERENCES usuario (id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE senha;');
    }
}
