<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "informativo")]
class Informativo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string")]
    private string $assunto;

    #[ORM\Column(type:"string")]
    private string $informacao;

    #[ORM\ManyToOne(targetEntity: InformativoVisibilidade::class)]
    #[ORM\JoinColumn(name: "informativo_visibilidade_id", referencedColumnName: "id")]
    private InformativoVisibilidade $visibilidade;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: "usuario_id", referencedColumnName: "id")]
    private Usuario $usuario;

    #[ORM\Column(type:"datetime", name: "dt_inclusao")]
    private \Datetime $dtInclusao;

    #[ORM\Column(type: 'boolean')]
    private bool $ativo;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Informativo
    {
        $this->id = $id;
        return $this;
    }

    public function getVisibilidade(): InformativoVisibilidade
    {
        return $this->visibilidade;
    }

    public function setVisibilidade(InformativoVisibilidade $visibilidade): Informativo
    {
        $this->visibilidade = $visibilidade;
        return $this;
    }

    public function getInformacao(): string
    {
        return $this->informacao;
    }

    public function setInformacao(string $informacao): Informativo
    {
        $this->informacao = $informacao;
        return $this;
    }

    public function getAssunto(): string
    {
        return $this->assunto;
    }

    public function setAssunto(string $assunto): Informativo
    {
        $this->assunto = $assunto;
        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): Informativo
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getDtInclusao(): DateTime
    {
        return $this->dtInclusao;
    }

    public function setDtInclusao(DateTime $dtInclusao): Informativo
    {
        $this->dtInclusao = $dtInclusao;
        return $this;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): Informativo
    {
        $this->ativo = $ativo;
        return $this;
    }

    public function getDataApi(): array
    {
        return [
            'assunto' => $this->getAssunto(),
            'visibilidade' => $this->getVisibilidade(),
            'informacao' => $this->getInformacao()
        ];
    }
}