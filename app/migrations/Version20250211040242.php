<?php


declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211040242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação das tabelas estado e cidade com relação ManyToOne para estado';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE estado (
            id INT AUTO_INCREMENT NOT NULL,
            uf VARCHAR(255) NOT NULL,
            nome VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');

        $this->addSql('CREATE TABLE cidade (
            id INT AUTO_INCREMENT NOT NULL,
            estado_id INT NOT NULL,
            nome VARCHAR(255) NOT NULL,
            INDEX IDX_CIDADE_ESTADO_ID (estado_id),
            PRIMARY KEY(id),
            CONSTRAINT FK_CIDADE_ESTADO FOREIGN KEY (estado_id) REFERENCES estado (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE cidade;');
        $this->addSql('DROP TABLE estado;');
    }
}