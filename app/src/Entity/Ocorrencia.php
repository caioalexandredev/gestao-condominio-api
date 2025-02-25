<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "ocorrencia")]
class Ocorrencia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string")]
    private string $assunto;

    #[ORM\Column(name: "dt_ocorrencia", type:"datetime")]
    private DateTime $dtOcorrencia;

    #[ORM\ManyToOne(targetEntity: OcorrenciaTipo::class)]
    #[ORM\JoinColumn(name: "ocorrencia_tipo_id", referencedColumnName: "id")]
    private OcorrenciaTipo $tipo;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: "usuario_id", referencedColumnName: "id")]
    private Usuario $usuario;

    #[ORM\Column(type:"string")]
    private string $descricao;

    #[ORM\Column(type:"datetime", name: "dt_inclusao")]
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

    public function getAssunto(): string
    {
        return $this->assunto;
    }

    public function setAssunto(string $assunto): Ocorrencia
    {
        $this->assunto = $assunto;
        return $this;
    }

    public function getTipo(): OcorrenciaTipo
    {
        return $this->tipo;
    }

    public function setTipo(PropriedadeTipo $tipo): Ocorrencia
    {
        $this->tipo = $tipo;
        return $this;
    }
    
    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): Ocorrencia
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): Ocorrencia
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getDtOcorrencia(): DateTime
    {
        return $this->dtOcorrencia;
    }

    public function setDtOcorrencia(DateTime $dtOcorrencia): Ocorrencia
    {
        $this->dtOcorrencia = $dtOcorrencia;
        return $this;
    }

    public function getDtInclusao(): DateTime
    {
        return $this->dtInclusao;
    }

    public function setDtInclusao(DateTime $dtInclusao): Ocorrencia
    {
        $this->dtInclusao = $dtInclusao;
        return $this;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): Ocorrencia
    {
        $this->ativo = $ativo;
        return $this;
    }

    public function getDataApi(): array
    {
        return [
            'tipo' => $this->getTipo()->getId(),
            'assunto' => $this->getAssunto(),
            'observacao' => $this->getDtOcorrencia()->format('Y-m-d'),
            'tipo' => $this->getTipo()->getId()
        ];
    }
}