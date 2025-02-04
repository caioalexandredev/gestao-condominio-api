<?php

namespace App\Security;

class PasswordHasher
{
    // Define o algoritmo padrão
    private string $algorithm;

    // Define as opções do algoritmo (como custo)
    private array $options;

    public function __construct(string $algorithm = PASSWORD_DEFAULT, array $options = ['cost' => 10])
    {
        $this->algorithm = $algorithm;
        $this->options = $options;
    }

    /**
     * Gera o hash de uma senha.
     *
     * @param string $password A senha do usuário.
     * @return string O hash da senha.
     */
    public function hashPassword(string $password): string
    {
        $hashedPassword = password_hash($password, $this->algorithm, $this->options);

        if ($hashedPassword === false) {
            throw new \RuntimeException('Falha ao gerar o hash da senha.');
        }

        return $hashedPassword;
    }

    /**
     * Verifica se a senha fornecida corresponde ao hash armazenado.
     *
     * @param string $password A senha fornecida pelo usuário.
     * @param string $hashedPassword O hash armazenado.
     * @return bool True se a senha corresponder ao hash, false caso contrário.
     */
    public function verifyPassword(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }

    /**
     * Verifica se o hash precisa ser atualizado com base nos novos parâmetros.
     *
     * @param string $hashedPassword O hash armazenado.
     * @return bool True se o hash precisar ser refeito, false caso contrário.
     */
    public function needsRehash(string $hashedPassword): bool
    {
        return password_needs_rehash($hashedPassword, $this->algorithm, $this->options);
    }
}
