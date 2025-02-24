<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "veiculo")]
class Veiculo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string")]
    private string $placa;
    
    #[ORM\Column(type:"string")]
    private string $modelo;

    #[ORM\Column(type:"string")]
    private string $ano;

    #[ORM\ManyToOne(targetEntity: VeiculoMarca::class)]
    #[ORM\JoinColumn(name: "veiculo_marca_id", referencedColumnName: "id")]
    private VeiculoMarca $marca;

    #[ORM\ManyToOne(targetEntity: VeiculoCor::class)]
    #[ORM\JoinColumn(name: "veiculo_cor_id", referencedColumnName: "id")]
    private VeiculoCor $cor;

    #[ORM\ManyToOne(targetEntity: VeiculoCategoria::class)]
    #[ORM\JoinColumn(name: "veiculo_categoria_id", referencedColumnName: "id")]
    private VeiculoCategoria $categoria;

    #[ORM\ManyToOne(targetEntity: Propriedade::class)]
    #[ORM\JoinColumn(name: "propriedade_id", referencedColumnName: "id")]
    private Propriedade $propriedade;

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

    public function getPlaca(): string
    {
        return $this->placa;
    }

    public function setPlaca(string $placa): Veiculo
    {
        $this->placa = $placa;
        return $this;
    }

    public function getModelo(): string
    {
        return $this->modelo;
    }

    public function setModelo(string $modelo): Veiculo
    {
        $this->modelo = $modelo;
        return $this;
    }

    public function getAno(): string
    {
        return $this->ano;
    }

    public function setAno(string $ano): Veiculo
    {
        $this->ano = $ano;
        return $this;
    }

    public function getMarca(): VeiculoMarca
    {
        return $this->marca;
    }

    public function setMarca(VeiculoMarca $marca): Veiculo
    {
        $this->marca = $marca;
        return $this;
    }

    public function getCategoria(): VeiculoCategoria
    {
        return $this->categoria;
    }

    public function setCategoria(VeiculoCategoria $categoria): Veiculo
    {
        $this->categoria = $categoria;
        return $this;
    }

    public function getCor(): VeiculoCor
    {
        return $this->cor;
    }

    public function setCor(VeiculoCor $cor): Veiculo
    {
        $this->cor = $cor;
        return $this;
    }
    
    public function getPropriedade(): Propriedade
    {
        return $this->propriedade;
    }

    public function setPropriedade(Propriedade $propriedade): Veiculo
    {
        $this->propriedade = $propriedade;
        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): Veiculo
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getProprietario(): PessoaDados
    {
        return $this->proprietario;
    }

    public function setProprietario(PessoaDados $proprietario): Veiculo
    {
        $this->proprietario = $proprietario;
        return $this;
    }

    public function getDtInclusao(): DateTime
    {
        return $this->dtInclusao;
    }

    public function setDtInclusao(DateTime $dtInclusao): Veiculo
    {
        $this->dtInclusao = $dtInclusao;
        return $this;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): Veiculo
    {
        $this->ativo = $ativo;
        return $this;
    }

    public function getDataApi(): array
    {
        return [
            'marca' => $this->getMarca()->getId(),
            'categoria' => $this->getCategoria()->getId(),
            'cor' => $this->getCor()->getId(),
            'placa' => $this->getPlaca(),
            'modelo' => $this->getModelo(),
            'ano' => $this->getAno(),
            'proprietario' => $this->getProprietario()->getId(),
            'propriedade' => $this->getPropriedade()->getId()
        ];
    }
}