<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'usuario')]
class Usuario
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private $id;

    #[ORM\Column(type: 'boolean')]
    private bool $ativo;

    #[ORM\ManyToOne(targetEntity: Pessoa::class)]
    #[ORM\JoinColumn(name: 'pessoa_id', referencedColumnName: 'id')]
    private Pessoa $pessoa;

    public function __construct()
    {
        $this->ativo = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setPessoa(Pessoa $pessoa): Usuario
    {
        $this->pessoa = $pessoa;
        return $this;
    }

    public function getPessoa(): Pessoa
    {
        return $this->pessoa;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): Usuario
    {
        $this->ativo = $ativo;
        return $this;
    }
}
