<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220042352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação da tabela pessoa_dados com seus respectivos campos e chaves estrangeiras.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE pessoa_dados (
            id INT AUTO_INCREMENT NOT NULL,
            endereco_id INT DEFAULT NULL,
            pessoa_id INT DEFAULT NULL,
            usuario_id INT DEFAULT NULL,
            telefone_contato_id INT DEFAULT NULL,
            celular_contato_id INT DEFAULT NULL,
            email_contato_id INT DEFAULT NULL,
            nome VARCHAR(255) NOT NULL,
            sobrenome VARCHAR(255) NOT NULL,
            data_nascimento DATE NOT NULL,
            sexo VARCHAR(50) NOT NULL,
            cpf VARCHAR(14) NOT NULL,
            naturalidade VARCHAR(255) NOT NULL,
            rg VARCHAR(20) NOT NULL,
            data_emissao_rg DATE NOT NULL,
            orgao_emissor_rg VARCHAR(50) NOT NULL,
            ativo TINYINT(1) NOT NULL,
            dt_inclusao DATETIME NOT NULL,
            PRIMARY KEY(id),
            CONSTRAINT FK_pessoa_dados_endereco FOREIGN KEY (endereco_id) REFERENCES endereco (id),
            CONSTRAINT FK_pessoa_dados_pessoa FOREIGN KEY (pessoa_id) REFERENCES pessoa (id),
            CONSTRAINT FK_pessoa_dados_telefone_contato FOREIGN KEY (telefone_contato_id) REFERENCES contato (id),
            CONSTRAINT FK_pessoa_dados_celular_contato FOREIGN KEY (celular_contato_id) REFERENCES contato (id),
            CONSTRAINT FK_pessoa_dados_email_contato FOREIGN KEY (email_contato_id) REFERENCES contato (id)
        );');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE pessoa_dados');
    }
}
