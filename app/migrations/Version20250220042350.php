<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250220042350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação da tabela contato';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE contato (
            id INT AUTO_INCREMENT NOT NULL,
            contato VARCHAR(255) NOT NULL,
            tipo INT NOT NULL,
            ativo TINYINT(1) NOT NULL,
            dt_inclusao DATETIME NOT NULL,
            usuario_id INT NOT NULL,
            PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE contato;');
    }
}
