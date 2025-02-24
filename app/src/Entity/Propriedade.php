<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "propriedade")]
class Propriedade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: PropriedadeTipo::class)]
    #[ORM\JoinColumn(name: "propriedade_tipo_id", referencedColumnName: "id")]
    private PropriedadeTipo $tipo;

    #[ORM\Column(type:"string")]
    private string $observacao;

    #[ORM\ManyToOne(targetEntity: Endereco::class)]
    #[ORM\JoinColumn(name: "endereco_id", referencedColumnName: "id")]
    private Endereco $endereco;

    #[ORM\ManyToOne(targetEntity: PessoaDados::class)]
    #[ORM\JoinColumn(name: "pessoa_dados_id", referencedColumnName: "id")]
    private PessoaDados $proprietario;

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

    public function getObservacao(): string
    {
        return $this->observacao;
    }

    public function setObservacao(string $observacao): Propriedade
    {
        $this->observacao = $observacao;
        return $this;
    }

    public function getTipo(): PropriedadeTipo
    {
        return $this->tipo;
    }

    public function setTipo(PropriedadeTipo $tipo): Propriedade
    {
        $this->tipo = $tipo;
        return $this;
    }
    
    public function getEndereco(): Endereco
    {
        return $this->endereco;
    }

    public function setEndereco(Endereco $endereco): Propriedade
    {
        $this->endereco = $endereco;
        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): Propriedade
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getProprietario(): PessoaDados
    {
        return $this->proprietario;
    }

    public function setProprietario(PessoaDados $proprietario): Propriedade
    {
        $this->proprietario = $proprietario;
        return $this;
    }

    public function getDtInclusao(): DateTime
    {
        return $this->dtInclusao;
    }

    public function setDtInclusao(DateTime $dtInclusao): Propriedade
    {
        $this->dtInclusao = $dtInclusao;
        return $this;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): Propriedade
    {
        $this->ativo = $ativo;
        return $this;
    }

    public function getDataApi(): array
    {
        return [
            'tipo' => $this->getTipo()->getId(),
            'proprietario' => $this->getProprietario()->getId(),
            'observacao' => $this->getObservacao(),
            'cep' => $this->getEndereco()->getCep(),
            'cidade' => $this->getEndereco()->getCidade()->getId(),
            'logradouro' => $this->getEndereco()->getLogradouro(),
            'bairro' => $this->getEndereco()->getBairro(),
            'numero' => $this->getEndereco()->getNumero(),
            'complemento' => $this->getEndereco()->getComplemento(),
            'uf' => $this->getEndereco()->getCidade()->getEstado()->getUf()
        ];
    }
}