<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "contato")]
class Contato
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string")]
    private string $contato;

    #[ORM\Column(type:"integer")]
    private int $tipo;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: "usuario_id", referencedColumnName: "id")]
    private Usuario $usuario;

    #[ORM\Column(type:"date", name: "dt_inclusao")]
    private \Datetime $dtInclusao;

    #[ORM\Column(type: 'boolean')]
    private bool $ativo;

    public function __construct()
    {
        $this->setAtivo(true);
        $this->setDtInclusao(new \DateTime());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getContato(): string
    {
        return $this->contato;
    }

    public function setContato(string $contato): Contato
    {
        $this->contato = $contato;
        return $this;
    }

    public function getTipo(): int
    {
        return $this->tipo;
    }

    public function setTipo(int $tipo): Contato
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function getDtInclusao(): DateTime
    {
        return $this->dtInclusao;
    }

    public function setDtInclusao(DateTime $dtInclusao): Contato
    {
        $this->dtInclusao = $dtInclusao;
        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): Contato
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): Contato
    {
        $this->ativo = $ativo;
        return $this;
    }
}