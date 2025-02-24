<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250224065036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação da tabela veiculo';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE veiculo (
            id INT AUTO_INCREMENT NOT NULL,
            placa VARCHAR(7) NOT NULL,
            modelo VARCHAR(500) NOT NULL,
            ano VARCHAR(4) NOT NULL,
            veiculo_marca_id INT NOT NULL,
            veiculo_cor_id INT NOT NULL,
            veiculo_categoria_id INT NOT NULL,
            propriedade_id INT NOT NULL,
            pessoa_dados_id INT NOT NULL,
            usuario_id INT NOT NULL,
            dt_inclusao DATETIME NOT NULL,
            ativo TINYINT(1) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('ALTER TABLE veiculo ADD CONSTRAINT FK_VEICULO_MARCA FOREIGN KEY (veiculo_marca_id) REFERENCES veiculo_marca (id)');
        $this->addSql('ALTER TABLE veiculo ADD CONSTRAINT FK_VEICULO_COR FOREIGN KEY (veiculo_cor_id) REFERENCES veiculo_cor (id)');
        $this->addSql('ALTER TABLE veiculo ADD CONSTRAINT FK_VEICULO_CATEGORIA FOREIGN KEY (veiculo_categoria_id) REFERENCES veiculo_categoria (id)');
        $this->addSql('ALTER TABLE veiculo ADD CONSTRAINT FK_VEICULO_PROPRIEDADE FOREIGN KEY (propriedade_id) REFERENCES propriedade (id)');
        $this->addSql('ALTER TABLE veiculo ADD CONSTRAINT FK_VEICULO_PESSOADADOS FOREIGN KEY (pessoa_dados_id) REFERENCES pessoa_dados (id)');
        $this->addSql('ALTER TABLE veiculo ADD CONSTRAINT FK_VEICULO_USUARIO FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE veiculo;');
    }
}
