<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250223144947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação da tabela propriedade_tipo e alteração do campo tipo na tabela propriedade para referência a propriedade_tipo';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE propriedade_tipo (
            id INT AUTO_INCREMENT NOT NULL,
            descricao VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('ALTER TABLE propriedade DROP COLUMN tipo');

        $this->addSql('ALTER TABLE propriedade ADD COLUMN propriedade_tipo_id INT NOT NULL');

        $this->addSql('ALTER TABLE propriedade ADD CONSTRAINT FK_PROPRIEDADE_TIPO FOREIGN KEY (propriedade_tipo_id) REFERENCES propriedade_tipo (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE propriedade DROP FOREIGN KEY FK_PROPRIEDADE_TIPO');
        $this->addSql('ALTER TABLE propriedade DROP COLUMN propriedade_tipo_id');

        $this->addSql('ALTER TABLE propriedade ADD COLUMN tipo INT NOT NULL');

        $this->addSql('DROP TABLE propriedade_tipo;');
    }
}
