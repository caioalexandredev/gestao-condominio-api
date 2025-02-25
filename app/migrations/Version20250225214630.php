<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225214630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação de Dados de Codigo de Entidades Gerais';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE conta_pagar_categoria (
            id INT AUTO_INCREMENT NOT NULL,
            descricao VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE conta_receber_categoria (
            id INT AUTO_INCREMENT NOT NULL,
            descricao VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE conta_status (
            id INT AUTO_INCREMENT NOT NULL,
            descricao VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE conta_tipo (
            id INT AUTO_INCREMENT NOT NULL,
            descricao VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE informativo_visibilidade (
            id INT AUTO_INCREMENT NOT NULL,
            descricao VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE ocorrencia_tipo (
            id INT AUTO_INCREMENT NOT NULL,
            descricao VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE conta_pagar_categoria;');
        $this->addSql('DROP TABLE conta_receber_categoria;');
        $this->addSql('DROP TABLE conta_status;');
        $this->addSql('DROP TABLE conta_tipo;');
        $this->addSql('DROP TABLE informativo_visibilidade;');
        $this->addSql('DROP TABLE ocorrencia_tipo;');
    }
}
