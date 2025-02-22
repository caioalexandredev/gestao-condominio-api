<?php

namespace App\Service;

use App\Entity\Pessoa;
use App\Entity\Senha;
use App\Entity\Usuario;
use App\Exception\BadRequestException;
use App\Security\PasswordHasher;
use Doctrine\ORM\EntityManager;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class LoginService
{
    public function __construct(
        private EntityManager $em
    )
    {
        
    }

    public function gerarTokenUsuario(
        string $cpf,
        string $senha
    ): string
    {
        $usuario = $this->consultarUsuarioPorCpf($cpf);

        if(!$this->isSenhaValida($usuario, $senha)) {
            throw new BadRequestException('Usuário ou Senha inválidos!');
        }

        $key = getenv('API_JWT_KEY');

        $payload = [
            'sub' => $usuario->getId(),
            'exp' => time() + (3600 * 8) // Token válido por 8 hora
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public function verificarTokenUsuario(string $accessToken): bool|array
    {
        $token = str_replace('Bearer ', '', $accessToken);
        $key = getenv('API_JWT_KEY');
    
        try {
            return (array) JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Throwable $t) {
            return false;
        }
    
    }

    private function consultarUsuarioPorCpf(string $cpf): Usuario
    {
        $repository = $this->em->getRepository(Pessoa::class);
        $pessoa = $repository->findOneBy(['cpf' => $cpf, 'ativo' => true]);

        if(is_null($pessoa)){
            throw new BadRequestException('Dados não encontrado!');
        }

        $repository = $this->em->getRepository(Usuario::class);
        $usuario = $repository->findOneBy(['pessoa' => $pessoa, 'ativo' => true]);

        if(is_null($usuario)){
            throw new BadRequestException('Usuário não encontrado!');
        }

        return $usuario;
    }

    public function consultarUsuarioPorSub(string $sub): Usuario
    {
        $repository = $this->em->getRepository(Usuario::class);
        $usuario = $repository->findOneBy(['id' => $sub, 'ativo' => true]);

        if(is_null($usuario)){
            throw new BadRequestException('Usuário não encontrado!');
        }

        return $usuario;
    }

    private function isSenhaValida(Usuario $usuario, string $senha): bool
    {
        $repository = $this->em->getRepository(Senha::class);
        $senhasUsuario = $repository->findBy(['usuario' => $usuario, 'ativo' => true]);

        $passwordHasher = new PasswordHasher();
        $senhaValida = false;
        
        foreach ($senhasUsuario as $senhaUsuario) {
            if($passwordHasher->verifyPassword($senha, $senhaUsuario->getHash())){
                $senhaValida = true;
                break;
            }
        }

        return $senhaValida;
    }

    public function gerarUsuarioADM(): bool
    {
        $repository = $this->em->getRepository(Pessoa::class);

        if(!$repository->findOneBy(['cpf' => '00000000000', 'ativo' => true])){
            $pessoa = new Pessoa();
            $pessoa->setNome('Master Administrador');
            $pessoa->setCpf('00000000000');
    
            $this->em->persist($pessoa);
            $this->em->flush();
    
            $usuario = new Usuario();
            $usuario->setPessoa($pessoa);

            $this->em->persist($usuario);
            $this->em->flush();
    
            $passwordHasher = new PasswordHasher();
    
            $senha = new Senha();
            $senha->setUsuario($usuario);
            $senha->setDtInclusao(new \DateTime());
            $senha->setHash($passwordHasher->hashPassword('admin'));

            $this->em->persist($senha);
            $this->em->flush();
    
            return true;
        }

        return false;
       
    }
}