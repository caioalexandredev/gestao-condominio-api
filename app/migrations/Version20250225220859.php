<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225220859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação da tabela conta_receber';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE conta_receber (
            id INT AUTO_INCREMENT NOT NULL,
            descricao VARCHAR(255) NOT NULL,
            valor DOUBLE NOT NULL,
            conta_tipo_id INT NOT NULL,
            vencimento DATE NOT NULL,
            conta_status_id INT NOT NULL,
            pessoa_dados_id INT NOT NULL,
            propriedade_id INT NOT NULL,
            conta_receber_categoria_id INT NOT NULL,
            observacao VARCHAR(255) NOT NULL,
            usuario_id INT NOT NULL,
            dt_inclusao DATETIME NOT NULL,
            ativo TINYINT(1) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('ALTER TABLE conta_receber ADD CONSTRAINT FK_CONTA_RECEBER_TIPO FOREIGN KEY (conta_tipo_id) REFERENCES conta_tipo (id)');
        $this->addSql('ALTER TABLE conta_receber ADD CONSTRAINT FK_CONTA_RECEBER_STATUS FOREIGN KEY (conta_status_id) REFERENCES conta_status (id)');
        $this->addSql('ALTER TABLE conta_receber ADD CONSTRAINT FK_CONTA_RECEBER_PROPRIETARIO FOREIGN KEY (pessoa_dados_id) REFERENCES pessoa_dados (id)');
        $this->addSql('ALTER TABLE conta_receber ADD CONSTRAINT FK_CONTA_RECEBER_PROPRIEDADE FOREIGN KEY (propriedade_id) REFERENCES propriedade (id)');
        $this->addSql('ALTER TABLE conta_receber ADD CONSTRAINT FK_CONTA_RECEBER_CATEGORIA FOREIGN KEY (conta_receber_categoria_id) REFERENCES conta_receber_categoria (id)');
        $this->addSql('ALTER TABLE conta_receber ADD CONSTRAINT FK_CONTA_RECEBER_USUARIO FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE conta_receber');
    }
}
