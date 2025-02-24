<?php

namespace App\Service;

use App\Entity\Propriedade;
use App\Entity\PropriedadeTipo;
use App\Entity\Usuario;
use App\Exception\BadRequestException;
use App\Util\Paginacao;
use DateTime;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class PropriedadeService
{
    public function __construct(
        private EntityManager $em,
        private CidadeService $cidadeService,
        private PessoaDadosService $pessoaDadosService,
        private EnderecoService $enderecoService,
        private Usuario $usuario,
        private SessionInterface $session
    ) {
        $this->usuario = $this->em->find(Usuario::class, $this->session->get('user'));
    }

    public function cadastrar(
        array $data
    ): Propriedade 
    {
        $endereco = $this->enderecoService->cadastrar($data);

        $proprietario = $this->pessoaDadosService->consultar($data['proprietario']);

        if(is_null($proprietario)){
            throw new BadRequestException("Proprietário não encontrada!");
        }

        $tipo = $this->consultarTipo($data['tipo']);

        if(is_null($tipo)){
            throw new BadRequestException("Tipo não encontrada!");
        }

        $propriedade = new Propriedade();
        $propriedade->setProprietario($proprietario);
        $propriedade->setObservacao($data["observacao"]);
        $propriedade->setTipo($tipo);
        $propriedade->setEndereco($endereco);
        $propriedade->setDtInclusao(new DateTime());
        $propriedade->setUsuario($this->usuario);

        $this->em->persist($propriedade);
        $this->em->flush();

        return $propriedade;
    }

    public function atualizar(
        int $id,
        array $data
    ): Propriedade {
        
        $propriedade = $this->consultar($id);

        if(is_null($propriedade)){
            throw new BadRequestException("Propriedade não encontrada!");
        }

        $proprietario = $this->pessoaDadosService->consultar($data['proprietario']);

        if(is_null($proprietario)){
            throw new BadRequestException("Proprietário não encontrada!");
        }

        $tipo = $this->consultarTipo($data['tipo']);

        if(is_null($tipo)){
            throw new BadRequestException("Tipo não encontrada!");
        }

        try {
            $this->em->getConnection()->beginTransaction();

            $propriedade->setProprietario($proprietario);
            $propriedade->setObservacao($data["observacao"]);
            $propriedade->setTipo($tipo);
            
            $this->enderecoService->atualizar($propriedade->getEndereco(), $data);

            $this->em->flush();
            $this->em->getConnection()->commit();

            return $propriedade;
        } catch (\Throwable $th) {
            $this->em->getConnection()->rollBack();
            throw $th;
        }
    }

    public function listagem(
        ?string $nome = null,
        ?string $cpf = null,
        ?string $endereco = null,
        ?string $tipo = null,
        ?int $pagina = null
    ): array {
        $qb = $this->em->createQueryBuilder();

        $qb->select([
            'p.id AS id',
            'pt.descricao AS tipo',
            "CONCAT(pd.nome, ' ', pd.sobrenome) AS proprietario",
            "CONCAT(e.logradouro, ', N° ', e.numero, ', ', e.complemento) AS endereco",
            'p.dtInclusao AS dt_inclusao'
        ])->from(Propriedade::class, 'p')
            ->join('p.proprietario', 'pd')
            ->join('p.tipo', 'pt')
            ->join('p.endereco', 'e');

        if (!empty($cpf)) {
            $qb->andWhere($qb->expr()->eq('pd.cpf', ':cpf'))
                ->setParameter('cpf', $cpf);
        }

        if (!empty($nome)) {
            $qb->andWhere(
                $qb->expr()->like("CONCAT(pd.nome, ' ', pd.sobrenome)", ':nome')
            )->setParameter('nome', '%' . $nome . '%');
        }

        if (!empty($endereco)) {
            $qb->andWhere(
                $qb->expr()->like("CONCAT(e.logradouro, ', N° ', e.numero, ', ', e.complemento)", ':endereco')
            )->setParameter('endereco', '%' . $endereco . '%');
        }

        if (!empty($tipo)) {
            $qb->andWhere($qb->expr()->eq('pt.id', ':tipo'))
                ->setParameter('tipo', $tipo);
        }

        $qb->andWhere($qb->expr()->eq('p.ativo', true))
            ->orderBy('id', 'DESC');

        $dados = Paginacao::prepararListagem($qb->getQuery(), 10, $pagina ?? 1);

        foreach ($dados['resultado'] as &$registro) {
            if (isset($registro['dt_inclusao']) && $registro['dt_inclusao'] instanceof \DateTimeInterface) {
                $registro['dt_inclusao'] = $registro['dt_inclusao']->format('d/m/Y H:i:s');
            }
        }

        return $dados;
    }

    public function consultar(int $id): ?Propriedade
    {
        return $this->em->find(Propriedade::class, $id);
    }

    public function consultarTipo(int $id): ?PropriedadeTipo
    {
        return $this->em->find(PropriedadeTipo::class, $id);
    }

    public function consultarDados(int $id): array
    {
        $propriedade = $this->consultar($id);

        if(is_null($propriedade)){
            throw new BadRequestException("Propriedade não encontrada!");
        }

        return array_merge($propriedade->getDataApi(), [
            'select_cidade' => $this->cidadeService->selectOptionKey($propriedade->getEndereco()->getCidade()->getEstado()->getUf())
        ]);
    }

    public function deletar(int $id): Propriedade
    {
        $propriedade = $this->consultar($id);

        if(is_null($propriedade)){
            throw new BadRequestException("Propriedade não encontrada!");
        }

        $propriedade->setAtivo(false);

        $this->em->flush();

        return $propriedade;
    }

    public function select(): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select("p.id AS key", "CONCAT(e.logradouro, ', N° ', e.numero, ', ', e.complemento) AS value")
            ->from(Propriedade::class, 'p')
            ->join('p.endereco', 'e')
            ->where($qb->expr()->eq('p.ativo', true));

        return $qb->orderBy('value', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
