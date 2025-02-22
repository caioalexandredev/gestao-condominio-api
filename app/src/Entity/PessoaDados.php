<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "pessoa_dados")]
class PessoaDados
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string")]
    private string $nome;

    #[ORM\Column(type:"string")]
    private string $sobrenome;

    #[ORM\Column(name: "data_nascimento", type:"date")]
    private \Datetime $dataNascimento;

    #[ORM\Column(type:"string")]
    private string $sexo;

    #[ORM\Column(type:"string")]
    private string $cpf;

    #[ORM\Column(type:"string")]
    private string $naturalidade;

    #[ORM\Column(type:"string")]
    private string $rg;

    #[ORM\Column(name: "data_emissao_rg", type:"date")]
    private \Datetime $dataEmissaoRg;

    #[ORM\Column(name: "orgao_emissor_rg", type:"string")]
    private string $orgaoEmissorRg;

    #[ORM\ManyToOne(targetEntity: Endereco::class)]
    #[ORM\JoinColumn(name: "endereco_id", referencedColumnName: "id")]
    private Endereco $endereco;

    #[ORM\ManyToOne(targetEntity: Pessoa::class)]
    #[ORM\JoinColumn(name: "pessoa_id", referencedColumnName: "id")]
    private Pessoa $pessoa;

    #[ORM\ManyToOne(targetEntity: Contato::class)]
    #[ORM\JoinColumn(name: "telefone_contato_id", referencedColumnName: "id")]
    private Contato $telefone;

    #[ORM\ManyToOne(targetEntity: Contato::class)]
    #[ORM\JoinColumn(name: "celular_contato_id", referencedColumnName: "id")]
    private Contato $celular;

    #[ORM\ManyToOne(targetEntity: Contato::class)]
    #[ORM\JoinColumn(name: "email_contato_id", referencedColumnName: "id")]
    private Contato $email;

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

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): PessoaDados
    {
        $this->nome = $nome;
        return $this;
    }

    public function getSobrenome(): string
    {
        return $this->sobrenome;
    }

    public function setSobrenome(string $sobrenome): PessoaDados
    {
        $this->sobrenome = $sobrenome;
        return $this;
    }

    public function getDataNascimento(): \DateTimeInterface
    {
        return $this->dataNascimento;
    }

    public function setDataNascimento(\DateTimeInterface $dataNascimento): PessoaDados
    {
        $this->dataNascimento = $dataNascimento;
        return $this;
    }

    public function getSexo(): string
    {
        return $this->sexo;
    }

    public function setSexo(string $sexo): PessoaDados
    {
        $this->sexo = $sexo;
        return $this;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): PessoaDados
    {
        $this->cpf = $cpf;
        return $this;
    }

    public function getNaturalidade(): string
    {
        return $this->naturalidade;
    }

    public function setNaturalidade(string $naturalidade): PessoaDados
    {
        $this->naturalidade = $naturalidade;
        return $this;
    }

    public function getRg(): string
    {
        return $this->rg;
    }

    public function setRg(string $rg): PessoaDados
    {
        $this->rg = $rg;
        return $this;
    }

    public function getDataEmissaoRg(): \DateTimeInterface
    {
        return $this->dataEmissaoRg;
    }

    public function setDataEmissaoRg(\DateTimeInterface $dataEmissaoRg): PessoaDados
    {
        $this->dataEmissaoRg = $dataEmissaoRg;
        return $this;
    }

    public function getOrgaoEmissorRg(): string
    {
        return $this->orgaoEmissorRg;
    }

    public function setOrgaoEmissorRg(string $orgaoEmissorRg): PessoaDados
    {
        $this->orgaoEmissorRg = $orgaoEmissorRg;
        return $this;
    }
    
    public function getEndereco(): Endereco
    {
        return $this->endereco;
    }

    public function setEndereco(Endereco $endereco): PessoaDados
    {
        $this->endereco = $endereco;
        return $this;
    }

    public function getPessoa(): Pessoa
    {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa): PessoaDados
    {
        $this->pessoa = $pessoa;
        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): PessoaDados
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getEmail(): Contato
    {
        return $this->email;
    }

    public function setEmail(Contato $email): PessoaDados
    {
        $this->email = $email;
        return $this;
    }

    public function getTelefone(): Contato
    {
        return $this->telefone;
    }

    public function setTelefone(Contato $telefone): PessoaDados
    {
        $this->telefone = $telefone;
        return $this;
    }

    public function getCelular(): Contato
    {
        return $this->celular;
    }

    public function setCelular(Contato $celular): PessoaDados
    {
        $this->celular = $celular;
        return $this;
    }

    public function getDtInclusao(): DateTime
    {
        return $this->dtInclusao;
    }

    public function setDtInclusao(DateTime $dtInclusao): PessoaDados
    {
        $this->dtInclusao = $dtInclusao;
        return $this;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): PessoaDados
    {
        $this->ativo = $ativo;
        return $this;
    }
}