<?php

namespace App\Service;

use App\Entity\Cidade;
use App\Entity\Endereco;
use App\Entity\Usuario;
use DateTime;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class EnderecoService
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
        array $data
    ): Endereco
    {
        $cidade = $this->em->find(Cidade::class, $data["cidade"]);

        $endereco = new Endereco();
        $endereco->setCidade($cidade);
        $endereco->setNumero($data["numero"]);
        $endereco->setComplemento($data["complemento"]);
        $endereco->setBairro($data["bairro"]);
        $endereco->setLogradouro($data["logradouro"]);
        $endereco->setCep($data["cep"]);
        $endereco->setDtInclusao(new DateTime());
        $endereco->setUsuario($this->usuario);

        $this->em->persist($endereco);
        $this->em->flush();

        return $endereco;
    }
}