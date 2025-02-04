<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "cidade")]
class Cidade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Estado::class)]
    #[ORM\JoinColumn(name: "estado_id", referencedColumnName: "id")]
    private Estado $estado;

    #[ORM\Column(type:"string")]
    private string $nome;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): Cidade
    {
        $this->nome = $nome;
        return $this;
    }

    public function getEstado(): Estado
    {
        return $this->estado;
    }

    public function setEstado(Estado $estado): Cidade
    {
        $this->estado = $estado;
        return $this;
    }
}