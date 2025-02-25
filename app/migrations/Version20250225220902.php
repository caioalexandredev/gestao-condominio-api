<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225220902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação da tabela ocorrencia';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE ocorrencia (
            id INT AUTO_INCREMENT NOT NULL,
            assunto VARCHAR(255) NOT NULL,
            dt_ocorrencia DATETIME NOT NULL,
            ocorrencia_tipo_id INT NOT NULL,
            usuario_id INT NOT NULL,
            descricao VARCHAR(255) NOT NULL,
            dt_inclusao DATETIME NOT NULL,
            ativo TINYINT(1) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('ALTER TABLE ocorrencia ADD CONSTRAINT FK_OCORRENCIA_TIPO FOREIGN KEY (ocorrencia_tipo_id) REFERENCES ocorrencia_tipo (id)');
        $this->addSql('ALTER TABLE ocorrencia ADD CONSTRAINT FK_OCORRENCIA_USUARIO FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE ocorrencia');
    }
}
