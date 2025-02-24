<?php

namespace App\Database\Fixtures;

use Doctrine\Persistence\ObjectManager;
use App\Entity\Pessoa;
use App\Entity\Senha;
use App\Entity\Usuario;
use App\Security\PasswordHasher;
use Doctrine\Common\DataFixtures\AbstractFixture;

class AdministradorFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $pessoa = new Pessoa();
        $pessoa->setNome('Master Administrador');
        $pessoa->setCpf('00000000000');

        $manager->persist($pessoa);
        $manager->flush();

        $usuario = new Usuario();
        $usuario->setPessoa($pessoa);

        $manager->persist($usuario);
        $manager->flush();

        $passwordHasher = new PasswordHasher();

        $senha = new Senha();
        $senha->setUsuario($usuario);
        $senha->setDtInclusao(new \DateTime());
        $senha->setHash($passwordHasher->hashPassword('admin'));

        $manager->persist($senha);
        $manager->flush();
        $manager->flush();

        echo "Usuário administrador gerado com sucesso!\n";
    }
}
