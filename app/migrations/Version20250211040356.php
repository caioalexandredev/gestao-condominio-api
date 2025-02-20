<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211040356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação da tabela pessoa';
    }

    public function up(Schema $schema): void
    {
        // Criação da tabela pessoa
        $this->addSql('CREATE TABLE pessoa (
            id INT AUTO_INCREMENT NOT NULL,
            nome VARCHAR(255) NOT NULL,
            cpf VARCHAR(255) NOT NULL,
            ativo TINYINT(1) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE pessoa;');
    }
}
