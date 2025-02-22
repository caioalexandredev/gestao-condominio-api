<?php

namespace App\Service;

use App\Entity\PessoaDados;
use App\Entity\Endereco;
use App\Entity\Usuario;
use DateTime;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class PessoaDadosService
{
    public function __construct(
        private EntityManager $em,
        private Usuario $usuario,
        private SessionInterface $session
    )
    {
        $this->usuario = $this->em->find(Usuario::class, $this->session->get('user'));
    }

    public function cadastrar(
        array $data,
        Endereco $endereco
    ): PessoaDados
    {
        $pessoaDados = new PessoaDados();
        $pessoaDados->setNome($data["nome"]);
        $pessoaDados->setSobrenome($data["sobrenome"]);
        $pessoaDados->setDataNascimento(new DateTime($data["dt_nascimento"]));
        $pessoaDados->setSexo($data["sexo"]);
        $pessoaDados->setCpf($data["cpf"]);
        $pessoaDados->setNaturalidade($data["naturalidade"]);
        $pessoaDados->setRg($data["rg"]);
        $pessoaDados->setOrgaoEmissorRg($data["orgao_emissao"]);
        $pessoaDados->setDataEmissaoRg(new DateTime($data["dt_emissao"]));
        $pessoaDados->setEndereco($endereco);
        $pessoaDados->setDtInclusao(new DateTime());
        $pessoaDados->setUsuario($this->usuario);

        $this->em->persist($pessoaDados);
        $this->em->flush();

        return $pessoaDados;
    }
}