<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'senha')]
class Senha
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private $id;

    #[ORM\Column(type: 'string')]
    private string $hash;

    #[ORM\Column(type: 'boolean')]
    private bool $ativo;

    #[ORM\Column(name: 'dt_cadastro', type: 'datetime')]
    private DateTime $dtCadastro;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id')]
    private Usuario $usuario;

    public function __construct()
    {
        $this->ativo = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): Senha
    {
        $this->hash = $hash;
        return $this;
    }

    public function setUsuario(Usuario $usuario): Senha
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): Senha
    {
        $this->ativo = $ativo;
        return $this;
    }

    public function getDtCadastro(): ?DateTime
    {
        return $this->dtCadastro;
    }

    public function setDtCadastro(DateTime $dtCadastro): Senha
    {
        $this->dtCadastro = $dtCadastro;
        return $this;
    }
}
