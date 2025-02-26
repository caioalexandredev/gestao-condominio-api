<?php

namespace App\Service;

use App\Entity\ContaPagar;
use App\Entity\ContaReceber;
use App\Entity\Usuario;
use App\Exception\BadRequestException;
use App\Util\Paginacao;
use DateTime;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class ContaReceberService
{
    public function __construct(
        private EntityManager $em,
        private ContaTipoService $contaTipoService,
        private ContaStatusService $contaStatusService,
        private ContaReceberCategoriaService $contaReceberCategoriaService,
        private PropriedadeService $propriedadeService,
        private PessoaDadosService $pessoaDadosService,
        private Usuario $usuario,
        private SessionInterface $session
    ) {
        $this->usuario = $this->em->find(Usuario::class, $this->session->get('user'));
    }

    public function cadastrar(
        array $data
    ): ContaReceber
    {
        $tipo = $this->contaTipoService->consultar($data['tipo']);

        if(is_null($tipo)){
            throw new BadRequestException("Tipo não encontrado!");
        }

        $status = $this->contaStatusService->consultar($data['status']);

        if(is_null($status)){
            throw new BadRequestException("Status não encontrado!");
        }

        $categoria = $this->contaReceberCategoriaService->consultar($data['categoria']);

        if(is_null($categoria)){
            throw new BadRequestException("Categoria não encontrada!");
        }

        $propriedade = $this->propriedadeService->consultar($data['propriedade']);

        if(is_null($categoria)){
            throw new BadRequestException("Propriedade não encontrada!");
        }

        $proprietario = $this->pessoaDadosService->consultar($data['proprietario']);

        if(is_null($categoria)){
            throw new BadRequestException("Proprietário não encontrado!");
        }

        $contaReceber = new ContaReceber();
        $contaReceber->setDescricao($data["descricao"]);
        $contaReceber->setValor($data["valor"]);
        $contaReceber->setTipo($tipo);
        $contaReceber->setVencimento(new \DateTime($data["vencimento"]));
        $contaReceber->setStatus($status);
        $contaReceber->setPropriedade($propriedade);
        $contaReceber->setProprietario($proprietario);
        $contaReceber->setCategoria($categoria);
        $contaReceber->setObservacao($data["observacao"]);
        $contaReceber->setDtInclusao(new DateTime());
        $contaReceber->setUsuario($this->usuario);

        $this->em->persist($contaReceber);
        $this->em->flush();

        return $contaReceber;
    }

    public function atualizar(
        int $id,
        array $data
    ): ContaReceber {
        
        $contaReceber = $this->consultar($id);

        if(is_null($contaReceber)){
            throw new BadRequestException("Conta a receber não encontrada!");
        }

        $tipo = $this->contaTipoService->consultar($data['tipo']);

        if(is_null($tipo)){
            throw new BadRequestException("Tipo não encontrado!");
        }

        $status = $this->contaStatusService->consultar($data['status']);

        if(is_null($status)){
            throw new BadRequestException("Status não encontrado!");
        }

        $categoria = $this->contaReceberCategoriaService->consultar($data['categoria']);

        if(is_null($categoria)){
            throw new BadRequestException("Categoria não encontrada!");
        }

        $propriedade = $this->propriedadeService->consultar($data['propriedade']);

        if(is_null($categoria)){
            throw new BadRequestException("Propriedade não encontrada!");
        }

        $proprietario = $this->pessoaDadosService->consultar($data['proprietario']);

        if(is_null($categoria)){
            throw new BadRequestException("Proprietário não encontrado!");
        }

        try {
            $this->em->getConnection()->beginTransaction();

            $contaReceber->setDescricao($data["descricao"]);
            $contaReceber->setValor($data["valor"]);
            $contaReceber->setTipo($tipo);
            $contaReceber->setVencimento(new \DateTime($data["vencimento"]));
            $contaReceber->setStatus($status);
            $contaReceber->setCategoria($categoria);
            $contaReceber->setPropriedade($propriedade);
            $contaReceber->setProprietario($proprietario);
            $contaReceber->setObservacao($data["observacao"]);

            $this->em->flush();
            $this->em->getConnection()->commit();

            return $contaReceber;
        } catch (\Throwable $th) {
            $this->em->getConnection()->rollBack();
            throw $th;
        }
    }

    public function listagem(
        ?string $descricao = null,
        ?string $tipo = null,
        ?string $dtInicioVencimento = null,
        ?string $dtFimVencimento = null,
        ?int $pagina = null
    ): array {
        $qb = $this->em->createQueryBuilder();

        $qb->select([
            'cr.id AS id',
            'cr.descricao AS descricao',
            'crt.descricao AS tipo',
            'cr.valor AS valor',
            "CONCAT(pd.nome, ' ', pd.sobrenome) AS proprietario",
            'cr.vencimento AS dt_vencimento',
            'cr.dtInclusao AS dt_inclusao'
        ])->from(ContaReceber::class, 'cr')
            ->join('cr.tipo', 'crt')
            ->join('cr.proprietario', 'pd');

        if (!empty($tipo)) {
            $qb->andWhere($qb->expr()->eq('crt.id', ':tipo'))
                ->setParameter('tipo', $tipo);
        }

        if (!empty($descricao)) {
            $qb->andWhere(
                $qb->expr()->like("cr.descricao", ':descricao')
            )->setParameter('descricao', '%' . $descricao . '%');
        }

        if (!is_null($dtInicioVencimento)) {
            $dtInicioVencimento = new DateTime($dtInicioVencimento);
            $qb->andWhere($qb->expr()->gte('cr.vencimento', ':dtInicioVencimento'))
                ->setParameter('dtInicioVencimento', $dtInicioVencimento->format('Y-m-d'));
        }

        if (!is_null($dtFimVencimento)) {
            $dtFimVencimento = new DateTime($dtFimVencimento);
            $qb->andWhere($qb->expr()->lte('cr.vencimento', ':dtFimVencimento'))
                ->setParameter('dtFimVencimento', $dtFimVencimento->format('Y-m-d'));
        }

        $qb->andWhere($qb->expr()->eq('cr.ativo', true))
            ->orderBy('id', 'DESC');

        $dados = Paginacao::prepararListagem($qb->getQuery(), 10, $pagina ?? 1);

        foreach ($dados['resultado'] as &$registro) {
            if (isset($registro['dt_inclusao']) && $registro['dt_inclusao'] instanceof \DateTimeInterface) {
                $registro['dt_inclusao'] = $registro['dt_inclusao']->format('d/m/Y H:i:s');
            }

            if (isset($registro['dt_vencimento']) && $registro['dt_vencimento'] instanceof \DateTimeInterface) {
                $registro['dt_vencimento'] = $registro['dt_vencimento']->format('d/m/Y');
            }
        }

        return $dados;
    }

    public function consultar(int $id): ?ContaReceber
    {
        return $this->em->find(ContaReceber::class, $id);
    }

    public function consultarDados(int $id): array
    {
        $contaReceber = $this->consultar($id);

        if(is_null($contaReceber)){
            throw new BadRequestException("Conta a receber não encontrada!");
        }

        return $contaReceber->getDataApi();
    }

    public function deletar(int $id): ContaReceber
    {
        $contaReceber = $this->consultar($id);

        if(is_null($contaReceber)){
            throw new BadRequestException("Conta a receber não encontrada!");
        }

        $contaReceber->setAtivo(false);

        $this->em->flush();

        return $contaReceber;
    }
}
