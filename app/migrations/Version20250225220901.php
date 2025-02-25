<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225220901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação da tabela informativo';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE informativo (
            id INT AUTO_INCREMENT NOT NULL,
            assunto VARCHAR(255) NOT NULL,
            informacao VARCHAR(255) NOT NULL,
            informativo_visibilidade_id INT NOT NULL,
            usuario_id INT NOT NULL,
            dt_inclusao DATETIME NOT NULL,
            ativo TINYINT(1) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('ALTER TABLE informativo ADD CONSTRAINT FK_INFORMATIVO_VISIBILIDADE FOREIGN KEY (informativo_visibilidade_id) REFERENCES informativo_visibilidade (id)');
        $this->addSql('ALTER TABLE informativo ADD CONSTRAINT FK_INFORMATIVO_USUARIO FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE informativo');
    }
}
