<?php

namespace App\Service;

use App\Entity\PessoaDados;
use App\Entity\Endereco;
use App\Entity\Usuario;
use App\Exception\BadRequestException;
use App\Util\Paginacao;
use DateTime;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class PessoaDadosService
{
    public function __construct(
        private EntityManager $em,
        private ContatoService $contatoService,
        private CidadeService $cidadeService,
        private EnderecoService $enderecoService,
        private Usuario $usuario,
        private SessionInterface $session
    ) {
        $this->usuario = $this->em->find(Usuario::class, $this->session->get('user'));
    }

    public function cadastrar(
        array $data,
        Endereco $endereco
    ): PessoaDados {
        $pessoaDados = new PessoaDados();

        $pessoaDados->setTelefone(
            $this->contatoService->cadastrar(1, $data["telefone"])
        );

        $pessoaDados->setCelular(
            $this->contatoService->cadastrar(2, $data["celular"])
        );

        $pessoaDados->setEmail(
            $this->contatoService->cadastrar(3, $data["email"])
        );

        $pessoaDados->setNome($data["nome"]);
        $pessoaDados->setSobrenome($data["sobrenome"]);
        $pessoaDados->setDataNascimento(new DateTime($data["dt_nascimento"]));
        $pessoaDados->setSexo($data["sexo"]);
        $pessoaDados->setCpf(preg_replace('/\D/', '', $data['cpf']));
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

    public function atualizar(
        int $id,
        array $data
    ): PessoaDados {
        
        $pessoaDados = $this->consultar($id);

        if(is_null($pessoaDados)){
            throw new BadRequestException("Pessoa não encontrada!");
        }

        try {
            $this->em->getConnection()->beginTransaction();

            if($pessoaDados->getTelefone()->getContato() != $data["telefone"]){
                $pessoaDados->setTelefone(
                    $this->contatoService->cadastrar(1, $data["telefone"])
                );
            }

            if($pessoaDados->getCelular()->getContato() != $data["celular"]){
                $pessoaDados->setCelular(
                    $this->contatoService->cadastrar(1, $data["celular"])
                );
            }

            if($pessoaDados->getEmail()->getContato() != $data["email"]){
                $pessoaDados->setEmail(
                    $this->contatoService->cadastrar(1, $data["celular"])
                );
            }

            $pessoaDados->setNome($data["nome"]);
            $pessoaDados->setSobrenome($data["sobrenome"]);
            $pessoaDados->setDataNascimento(new DateTime($data["dt_nascimento"]));
            $pessoaDados->setSexo($data["sexo"]);
            $pessoaDados->setCpf(preg_replace('/\D/', '', $data['cpf']));
            $pessoaDados->setNaturalidade($data["naturalidade"]);
            $pessoaDados->setRg($data["rg"]);
            $pessoaDados->setOrgaoEmissorRg($data["orgao_emissao"]);
            $pessoaDados->setDataEmissaoRg(new DateTime($data["dt_emissao"]));
            
            $this->enderecoService->atualizar($pessoaDados->getEndereco(), $data);

            $this->em->flush();
            $this->em->getConnection()->commit();

            return $pessoaDados;
        } catch (\Throwable $th) {
            $this->em->getConnection()->rollBack();
            throw $th;
        }
    }

    public function listagem(
        ?string $nome = null,
        ?string $cpf = null,
        ?string $rg = null,
        ?int $pagina = null
    ): array {
        $qb = $this->em->createQueryBuilder();

        $qb->select([
            'pd.id AS id',
            "CONCAT(pd.nome, ' ', pd.sobrenome) AS nome",
            'pd.sobrenome AS sobrenome',
            'pd.cpf AS cpf',
            "CONCAT(pd.rg, ' - ', pd.orgaoEmissorRg) AS rg",
            'pd.dtInclusao AS dt_inclusao'
        ])->from(PessoaDados::class, 'pd');

        if (!empty($cpf)) {
            $qb->andWhere($qb->expr()->eq('pd.cpf', ':cpf'))
                ->setParameter('cpf', $cpf);
        }

        if (!empty($nome)) {
            $qb->andWhere(
                $qb->expr()->like("CONCAT(pd.nome, ' ', pd.sobrenome)", ':nome')
            )->setParameter('nome', '%' . $nome . '%');
        }

        if (!empty($rg)) {
            $qb->andWhere($qb->expr()->eq('pd.rg', ':rg'))
                ->setParameter('rg', $rg);
        }

        $qb->andWhere($qb->expr()->eq('pd.ativo', true))
            ->orderBy('id', 'DESC');

        $dados = Paginacao::prepararListagem($qb->getQuery(), 10, $pagina ?? 1);

        foreach ($dados['resultado'] as &$registro) {
            if (isset($registro['dt_inclusao']) && $registro['dt_inclusao'] instanceof \DateTimeInterface) {
                $registro['dt_inclusao'] = $registro['dt_inclusao']->format('d/m/Y H:i:s');
            }
        }

        return $dados;
    }

    public function consultar(int $id): ?PessoaDados
    {
        return $this->em->find(PessoaDados::class, $id);
    }

    public function consultarDados(int $id): array
    {
        $pessoaDados = $this->consultar($id);

        if(is_null($pessoaDados)){
            throw new BadRequestException("Pessoa não encontrada!");
        }

        return array_merge($pessoaDados->getDataApi(), [
            'select_cidade' => $this->cidadeService->selectOptionKey($pessoaDados->getEndereco()->getCidade()->getEstado()->getUf())
        ]);
    }

    public function deletar(int $id): PessoaDados
    {
        $pessoaDados = $this->consultar($id);

        if(is_null($pessoaDados)){
            throw new BadRequestException("Pessoa não encontrada!");
        }

        $pessoaDados->setAtivo(false);

        $this->em->persist($pessoaDados);
        $this->em->flush();

        return $pessoaDados;
    }
}
