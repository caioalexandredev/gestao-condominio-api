<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250224060713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação de Dados de Codigo da Entidade Veiculo';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE veiculo_categoria (
            id INT AUTO_INCREMENT NOT NULL,
            descricao VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE veiculo_cor (
            id INT AUTO_INCREMENT NOT NULL,
            descricao VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE veiculo_marca (
            id INT AUTO_INCREMENT NOT NULL,
            descricao VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE veiculo_categoria;');
        $this->addSql('DROP TABLE veiculo_cor;');
        $this->addSql('DROP TABLE veiculo_marca;');
    }
}
