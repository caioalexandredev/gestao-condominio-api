<?php

namespace App\Service;

use App\Entity\Contato;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class ContatoService
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
        int $tipo,
        string $informacao
    ): Contato
    {
        $contato = new Contato();
        $contato->setContato($informacao);
        $contato->setTipo($tipo);
        $contato->setUsuario($this->usuario);

        $this->em->persist($contato);
        $this->em->flush();

        return $contato;
    }
}