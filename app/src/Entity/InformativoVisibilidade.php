<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "informativo_visibilidade")]
class InformativoVisibilidade
{
    #[ORM\Id]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string")]
    private string $descricao;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): InformativoVisibilidade
    {
        $this->id = $id;
        return $this;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): InformativoVisibilidade
    {
        $this->descricao = $descricao;
        return $this;
    }
}