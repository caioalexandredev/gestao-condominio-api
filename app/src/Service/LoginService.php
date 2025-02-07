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
        private EntityManager $entityManager
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

    public function verificarTokenUsuario(string $accessToken): bool
    {
        $token = str_replace('Bearer ', '', $accessToken);
        $key = getenv('API_JWT_KEY');
    
        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return true;
        } catch (\Throwable $t) {
            return false;
        }
    
    }

    private function consultarUsuarioPorCpf(string $cpf): Usuario
    {
        $repository = $this->entityManager->getRepository(Pessoa::class);
        $pessoa = $repository->findOneBy(['cpf' => $cpf, 'ativo' => true]);

        if(is_null($pessoa)){
            throw new BadRequestException('Dados não encontrado!');
        }

        $repository = $this->entityManager->getRepository(Usuario::class);
        $usuario = $repository->findOneBy(['pessoa' => $pessoa, 'ativo' => true]);

        if(is_null($usuario)){
            throw new BadRequestException('Usuário não encontrado!');
        }

        return $usuario;
    }

    private function isSenhaValida(Usuario $usuario, string $senha): bool
    {
        $repository = $this->entityManager->getRepository(Senha::class);
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
}