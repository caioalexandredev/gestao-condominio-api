<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "endereco")]
class Endereco
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string")]
    private string $cep;

    #[ORM\ManyToOne(targetEntity: Cidade::class)]
    #[ORM\JoinColumn(name: "cidade_id", referencedColumnName: "id")]
    private Cidade $cidade;

    #[ORM\Column(type:"string")]
    private string $logradouro;

    #[ORM\Column(type:"string")]
    private string $bairro;

    #[ORM\Column(type:"string")]
    private string $numero;

    #[ORM\Column(type:"string", nullable:true)]
    private ?string $complemento;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: "usuario_id", referencedColumnName: "id")]
    private Usuario $usuario;

    #[ORM\Column(type:"datetime", name: "dt_inclusao")]
    private \Datetime $dtInclusao;

    public function __construct()
    {
        $this->setComplemento(null);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCep(): string
    {
        return $this->cep;
    }

    public function setCep(string $cep): Endereco
    {
        $this->cep = $cep;
        return $this;
    }

    public function getCidade(): Cidade
    {
        return $this->cidade;
    }

    public function setCidade(Cidade $cidade): Endereco
    {
        $this->cidade = $cidade;
        return $this;
    }

    public function getLogradouro(): string
    {
        return $this->logradouro;
    }

    public function setLogradouro(string $logradouro): Endereco
    {
        $this->logradouro = $logradouro;
        return $this;
    }

    public function getBairro(): string
    {
        return $this->bairro;
    }

    public function setBairro(string $bairro): Endereco
    {
        $this->bairro = $bairro;
        return $this;
    }

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): Endereco
    {
        $this->numero = $numero;
        return $this;
    }

    public function getComplemento(): ?string
    {
        return $this->complemento;
    }

    public function setComplemento(?string $complemento): Endereco
    {
        $this->complemento = $complemento;
        return $this;
    }

    public function getDtInclusao(): DateTime
    {
        return $this->dtInclusao;
    }

    public function setDtInclusao(DateTime $dtInclusao): Endereco
    {
        $this->dtInclusao = $dtInclusao;
        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): Endereco
    {
        $this->usuario = $usuario;
        return $this;
    }
}