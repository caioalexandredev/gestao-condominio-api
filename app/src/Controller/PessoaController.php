<?php

namespace App\Controller;

use App\Service\LoginService;
use App\Service\PessoaDadosService;
use App\Service\PessoaService;
use Odan\Session\SessionInterface;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class PessoaController extends DefaultController
{
    public function __construct(
        private ContainerInterface $containerInterface,
        private LoginService $loginService,
        private PessoaService $pessoaService,
        private PessoaDadosService $pessoaDadosService,
    )
    {
        parent::__construct($containerInterface);
    }

    #[OA\Post(
        path: '/api/pessoa',
        summary: 'Realiza o cadastro completo de pessoa',
        tags: ['Pessoa'],
        security: [['api_key' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'nome', type: 'string', description: 'Nome do usuário'),
                        new OA\Property(property: 'sobrenome', type: 'string', description: 'Sobrenome do usuário'),
                        new OA\Property(property: 'dt_nascimento', type: 'string', description: 'Data de nascimento do usuário (YYYY-MM-DD)'),
                        new OA\Property(property: 'sexo', type: 'string', description: 'Sexo do usuário (M/F)'),
                        new OA\Property(property: 'cpf', type: 'string', description: 'CPF do usuário'),
                        new OA\Property(property: 'naturalidade', type: 'string', description: 'Cidade natal do usuário'),
                        new OA\Property(property: 'rg', type: 'string', description: 'Registro Geral (RG) do usuário'),
                        new OA\Property(property: 'orgao_emissao', type: 'string', description: 'Órgão emissor do RG'),
                        new OA\Property(property: 'dt_emissao', type: 'string', description: 'Data de emissão do RG (YYYY-MM-DD)'),
                        new OA\Property(property: 'telefone', type: 'string', description: 'Número de telefone do usuário'),
                        new OA\Property(property: 'celular', type: 'string', description: 'Número de telefone celular do usuário'),
                        new OA\Property(property: 'email', type: 'string', description: 'Endereço de e-mail do usuário'),
                        new OA\Property(property: 'cep', type: 'string', description: 'CEP do endereço do usuário'),
                        new OA\Property(property: 'cidade', type: 'string', description: 'Cidade do endereço do usuário'),
                        new OA\Property(property: 'logradouro', type: 'string', description: 'Nome da rua do endereço do usuário'),
                        new OA\Property(property: 'bairro', type: 'string', description: 'Bairro do endereço do usuário'),
                        new OA\Property(property: 'numero', type: 'string', description: 'Número do endereço do usuário'),
                        new OA\Property(property: 'complemento', type: 'string', description: 'Complemento do endereço (opcional)')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Requisição bem-sucedida'),
            new OA\Response(response: 400, description: 'Requisição inválida, dados incorretos ou faltando parâmetros'),
            new OA\Response(response: 500, description: 'Erro interno do servidor')
        ]
    )]
    public function cadastrar(
        RequestInterface       $request,
        ResponseInterface      $response,
        SessionInterface       $session,
    ): ResponseInterface 
    {
        try {
            $data = $this->getDataRequest($request);
            
            $this->pessoaService->cadastrar($data);

            return $this->jsonResponse($response, $session, true);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Get(
        path: '/api/pessoa/listagem',
        summary: 'Listagem de Pessoas do Sistema',
        tags: ['Pessoa'],
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(
                name: 'nome',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Nome'
            ),
            new OA\Parameter(
                name: 'cpf',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'CPF'
            ),
            new OA\Parameter(
                name: 'rg',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'RG'
            ),
            new OA\Parameter(
                name: 'pagina',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Página'
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Requisição bem-sucedida'),
            new OA\Response(response: 400, description: 'Requisição inválida, dados incorretos ou faltando parâmetros'),
            new OA\Response(response: 500, description: 'Erro interno do servidor')
        ]
    )]
    public function listagem(
        RequestInterface       $request,
        ResponseInterface      $response,
        SessionInterface       $session,
    ): ResponseInterface 
    {
        try {
            $data = $this->getDataRequest($request);

            $result = $this->pessoaDadosService->listagem(
                $data['nome'] ?? null,
                $data['cpf'] ?? null,
                $data['rg'] ?? null,
                $data['pagina'] ?? null
            );
            return $this->jsonResponse($response, $session, $result);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Get(
        path: '/api/pessoa/{id}',
        summary: 'Consultar Pessoa',
        tags: ['Pessoa'],
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'id'
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Requisição bem-sucedida'),
            new OA\Response(response: 400, description: 'Requisição inválida, dados incorretos ou faltando parâmetros'),
            new OA\Response(response: 500, description: 'Erro interno do servidor')
        ]
    )]
    public function consultar(
        RequestInterface       $request,
        ResponseInterface      $response,
        SessionInterface       $session,
    ): ResponseInterface 
    {
        try {
            $data = $this->getDataRequest($request);
            $data['id'] = $this->getAttributeRequest($request, 'id');
            return $this->jsonResponse($response, $session, $this->pessoaDadosService->consultarDados($data['id']));
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Delete(
        path: '/api/pessoa/{id}',
        summary: 'Excluir Pessoa do Sistema',
        tags: ['Pessoa'],
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Id'
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Requisição bem-sucedida'),
            new OA\Response(response: 400, description: 'Requisição inválida, dados incorretos ou faltando parâmetros'),
            new OA\Response(response: 500, description: 'Erro interno do servidor')
        ]
    )]
    public function excluir(
        ResponseInterface      $response,
        RequestInterface       $request,
        SessionInterface       $session,
    ): ResponseInterface
    {
        try{
            $data = $this->getDataRequest($request);
            $data['id'] = $this->getAttributeRequest($request, 'id');

            $this->pessoaDadosService->deletar($data['id']);

            return $this->jsonResponse($response, $session, true);
        }catch(Throwable $e){
            return $this->handleException($response, $e, $session);
        }
    }

    #[OA\Put(
        path: '/api/pessoa/{id}',
        summary: 'Atualiza o cadastro completo de pessoa',
        tags: ['Pessoa'],
        security: [['api_key' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: false,
                schema: new OA\Schema(type: 'string'),
                description: 'Id'
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'nome', type: 'string', description: 'Nome do usuário'),
                        new OA\Property(property: 'sobrenome', type: 'string', description: 'Sobrenome do usuário'),
                        new OA\Property(property: 'dt_nascimento', type: 'string', description: 'Data de nascimento do usuário (YYYY-MM-DD)'),
                        new OA\Property(property: 'sexo', type: 'string', description: 'Sexo do usuário (M/F)'),
                        new OA\Property(property: 'cpf', type: 'string', description: 'CPF do usuário'),
                        new OA\Property(property: 'naturalidade', type: 'string', description: 'Cidade natal do usuário'),
                        new OA\Property(property: 'rg', type: 'string', description: 'Registro Geral (RG) do usuário'),
                        new OA\Property(property: 'orgao_emissao', type: 'string', description: 'Órgão emissor do RG'),
                        new OA\Property(property: 'dt_emissao', type: 'string', description: 'Data de emissão do RG (YYYY-MM-DD)'),
                        new OA\Property(property: 'telefone', type: 'string', description: 'Número de telefone do usuário'),
                        new OA\Property(property: 'celular', type: 'string', description: 'Número de telefone celular do usuário'),
                        new OA\Property(property: 'email', type: 'string', description: 'Endereço de e-mail do usuário'),
                        new OA\Property(property: 'cep', type: 'string', description: 'CEP do endereço do usuário'),
                        new OA\Property(property: 'cidade', type: 'string', description: 'Cidade do endereço do usuário'),
                        new OA\Property(property: 'logradouro', type: 'string', description: 'Nome da rua do endereço do usuário'),
                        new OA\Property(property: 'bairro', type: 'string', description: 'Bairro do endereço do usuário'),
                        new OA\Property(property: 'numero', type: 'string', description: 'Número do endereço do usuário'),
                        new OA\Property(property: 'complemento', type: 'string', description: 'Complemento do endereço (opcional)')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Requisição bem-sucedida'),
            new OA\Response(response: 400, description: 'Requisição inválida, dados incorretos ou faltando parâmetros'),
            new OA\Response(response: 500, description: 'Erro interno do servidor')
        ]
    )]
    public function atualizar(
        RequestInterface       $request,
        ResponseInterface      $response,
        SessionInterface       $session,
    ): ResponseInterface 
    {
        try {
            $data = $this->getDataRequest($request);
            $data['id'] = $this->getAttributeRequest($request, 'id');
            
            $this->pessoaDadosService->atualizar($data['id'], $data);

            return $this->jsonResponse($response, $session, true);
        } catch (Throwable $e) {
            return $this->handleException($response, $e, $session);
        }
    }
}