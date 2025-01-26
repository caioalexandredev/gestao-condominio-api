<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Entity\User;

class UserController
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addUser($request, $response, $args)
    {
        $user = new User();
        $user->setName("John Doe");
        $user->setEmail("john.doe@example.com");

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $response->getBody()->write("Usuário criado com ID " . $user->getId());
        return $response;
    }

    public function listUsers($request, $response, $args)
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $data = [];

        foreach ($users as $user) {
            $data[] = ['id' => $user->getId(), 'name' => $user->getName(), 'email' => $user->getEmail()];
        }

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }
}