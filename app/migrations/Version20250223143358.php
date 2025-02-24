<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250223143358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação da tabela propriedade';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE propriedade (
            id INT AUTO_INCREMENT NOT NULL,
            tipo INT NOT NULL,
            observacao VARCHAR(255) NOT NULL,
            endereco_id INT NOT NULL,
            pessoa_dados_id INT NOT NULL,
            usuario_id INT NOT NULL,
            dt_inclusao DATETIME NOT NULL,
            ativo TINYINT(1) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('ALTER TABLE propriedade ADD CONSTRAINT FK_PROPRIEDADE_ENDERECO FOREIGN KEY (endereco_id) REFERENCES endereco (id)');
        $this->addSql('ALTER TABLE propriedade ADD CONSTRAINT FK_PROPRIEDADE_PESSOADADOS FOREIGN KEY (pessoa_dados_id) REFERENCES pessoa_dados (id)');
        $this->addSql('ALTER TABLE propriedade ADD CONSTRAINT FK_PROPRIEDADE_USUARIO FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE propriedade;');
    }
}
