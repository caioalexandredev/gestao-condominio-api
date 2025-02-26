<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "conta_receber")]
class ContaReceber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private int $id;
    
    #[ORM\Column(type:"string")]
    private string $descricao;

    #[ORM\Column(type:"float")]
    private float $valor;

    #[ORM\ManyToOne(targetEntity: ContaTipo::class)]
    #[ORM\JoinColumn(name: "conta_tipo_id", referencedColumnName: "id")]
    private ContaTipo $tipo;

    #[ORM\Column(type:"date")]
    private DateTime $vencimento;

    #[ORM\ManyToOne(targetEntity: ContaStatus::class)]
    #[ORM\JoinColumn(name: "conta_status_id", referencedColumnName: "id")]
    private ContaStatus $status;

    #[ORM\ManyToOne(targetEntity: PessoaDados::class)]
    #[ORM\JoinColumn(name: "pessoa_dados_id", referencedColumnName: "id")]
    private PessoaDados $proprietario;

    #[ORM\ManyToOne(targetEntity: Propriedade::class)]
    #[ORM\JoinColumn(name: "propriedade_id", referencedColumnName: "id")]
    private Propriedade $propriedade;

    #[ORM\ManyToOne(targetEntity: ContaReceberCategoria::class)]
    #[ORM\JoinColumn(name: "conta_receber_categoria_id", referencedColumnName: "id")]
    private ContaReceberCategoria $categoria;

    #[ORM\Column(type:"string")]
    private string $observacao;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: "usuario_id", referencedColumnName: "id")]
    private Usuario $usuario;

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

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): ContaReceber
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function setValor(float $valor): ContaReceber
    {
        $this->valor = $valor;
        return $this;
    }

    public function getTipo(): ContaTipo
    {
        return $this->tipo;
    }

    public function setTipo(ContaTipo $tipo): ContaReceber
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function getVencimento(): DateTime
    {
        return $this->vencimento;
    }

    public function setVencimento(DateTime $vencimento): ContaReceber
    {
        $this->vencimento = $vencimento;
        return $this;
    }

    public function getStatus(): ContaStatus
    {
        return $this->status;
    }

    public function setStatus(ContaStatus $status): ContaReceber
    {
        $this->status = $status;
        return $this;
    }

    public function getPropriedade(): Propriedade
    {
        return $this->propriedade;
    }

    public function setPropriedade(Propriedade $propriedade): ContaReceber
    {
        $this->propriedade = $propriedade;
        return $this;
    }

    public function getCategoria(): ContaReceberCategoria
    {
        return $this->categoria;
    }

    public function setCategoria(ContaReceberCategoria $categoria): ContaReceber
    {
        $this->categoria = $categoria;
        return $this;
    }

    public function getObservacao(): string
    {
        return $this->observacao;
    }

    public function setObservacao(string $observacao): ContaReceber
    {
        $this->observacao = $observacao;
        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): ContaReceber
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getProprietario(): PessoaDados
    {
        return $this->proprietario;
    }

    public function setProprietario(PessoaDados $proprietario): ContaReceber
    {
        $this->proprietario = $proprietario;
        return $this;
    }

    public function getDtInclusao(): DateTime
    {
        return $this->dtInclusao;
    }

    public function setDtInclusao(DateTime $dtInclusao): ContaReceber
    {
        $this->dtInclusao = $dtInclusao;
        return $this;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): ContaReceber
    {
        $this->ativo = $ativo;
        return $this;
    }

    public function getDataApi(): array
    {
        return [
            'descricao' => $this->getDescricao(),
            'valor' => $this->getValor(),
            'tipo' => $this->getTipo()->getId(),
            'vencimento' => $this->getVencimento()->format('Y-m-d'),
            'status' => $this->getStatus()->getId(),
            'propriedade' => $this->getPropriedade()->getId(),
            'proprietario' => $this->getProprietario()->getId(),
            'categoria' => $this->getCategoria()->getId(),
            'observacao' => $this->getObservacao()
        ];
    }
}