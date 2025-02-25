<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "conta_status")]
class ContaStatus
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

    public function setId(int $id): ContaStatus
    {
        $this->id = $id;
        return $this;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): ContaStatus
    {
        $this->descricao = $descricao;
        return $this;
    }
}