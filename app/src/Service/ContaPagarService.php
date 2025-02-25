<?php

namespace App\Service;

use App\Entity\ContaPagar;
use App\Entity\Usuario;
use App\Exception\BadRequestException;
use App\Util\Paginacao;
use DateTime;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class ContaPagarService
{
    public function __construct(
        private EntityManager $em,
        private ContaTipoService $contaTipoService,
        private ContaStatusService $contaStatusService,
        private ContaPagarCategoriaService $contaPagarCategoriaService,
        private Usuario $usuario,
        private SessionInterface $session
    ) {
        $this->usuario = $this->em->find(Usuario::class, $this->session->get('user'));
    }

    public function cadastrar(
        array $data
    ): ContaPagar
    {
        $tipo = $this->contaTipoService->consultar($data['tipo']);

        if(is_null($tipo)){
            throw new BadRequestException("Tipo não encontrado!");
        }

        $status = $this->contaStatusService->consultar($data['status']);

        if(is_null($status)){
            throw new BadRequestException("Status não encontrado!");
        }

        $categoria = $this->contaPagarCategoriaService->consultar($data['categoria']);

        if(is_null($categoria)){
            throw new BadRequestException("Categoria não encontrada!");
        }

        $contaPagar = new ContaPagar();
        $contaPagar->setDescricao($data["descricao"]);
        $contaPagar->setValor($data["valor"]);
        $contaPagar->setTipo($tipo);
        $contaPagar->setVencimento(new \DateTime($data["vencimento"]));
        $contaPagar->setStatus($status);
        $contaPagar->setCategoria($categoria);
        $contaPagar->setObservacao($data["observacao"]);
        $contaPagar->setDtInclusao(new DateTime());
        $contaPagar->setUsuario($this->usuario);

        $this->em->persist($contaPagar);
        $this->em->flush();

        return $contaPagar;
    }

    public function atualizar(
        int $id,
        array $data
    ): ContaPagar {
        
        $contaPagar = $this->consultar($id);

        if(is_null($contaPagar)){
            throw new BadRequestException("Conta a pagar não encontrada!");
        }

        $tipo = $this->contaTipoService->consultar($data['tipo']);

        if(is_null($tipo)){
            throw new BadRequestException("Tipo não encontrado!");
        }

        $status = $this->contaStatusService->consultar($data['status']);

        if(is_null($status)){
            throw new BadRequestException("Status não encontrado!");
        }

        $categoria = $this->contaPagarCategoriaService->consultar($data['categoria']);

        if(is_null($categoria)){
            throw new BadRequestException("Categoria não encontrada!");
        }

        try {
            $this->em->getConnection()->beginTransaction();

            $contaPagar->setDescricao($data["descricao"]);
            $contaPagar->setValor($data["valor"]);
            $contaPagar->setTipo($tipo);
            $contaPagar->setVencimento(new \DateTime($data["vencimento"]));
            $contaPagar->setStatus($status);
            $contaPagar->setCategoria($categoria);
            $contaPagar->setObservacao($data["observacao"]);

            $this->em->flush();
            $this->em->getConnection()->commit();

            return $contaPagar;
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
            'cp.id AS id',
            'cp.descricao AS descricao',
            'cpt.descricao AS tipo',
            'cp.valor AS valor',
            'cp.vencimento AS dt_vencimento',
            'cp.dtInclusao AS dt_inclusao'
        ])->from(ContaPagar::class, 'cp')
            ->join('cp.tipo', 'cpt');

        if (!empty($tipo)) {
            $qb->andWhere($qb->expr()->eq('cpt.id', ':tipo'))
                ->setParameter('tipo', $tipo);
        }

        if (!empty($descricao)) {
            $qb->andWhere(
                $qb->expr()->like("cp.descricao)", ':descricao')
            )->setParameter('descricao', '%' . $descricao . '%');
        }

        if (!is_null($dtInicioVencimento)) {
            $dtInicioVencimento = new DateTime($dtInicioVencimento);
            $qb->andWhere($qb->expr()->gte('cp.vencimento', ':dtInicioVencimento'))
                ->setParameter('dtInicioVencimento', $dtInicioVencimento->format('Y-m-d'));
        }

        if (!is_null($dtFimVencimento)) {
            $dtFimVencimento = new DateTime($dtFimVencimento);
            $qb->andWhere($qb->expr()->gte('cp.vencimento', ':dtFimVencimento'))
                ->setParameter('dtFimVencimento', $dtFimVencimento->format('Y-m-d'));
        }

        $qb->andWhere($qb->expr()->eq('cp.ativo', true))
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

    public function consultar(int $id): ?ContaPagar
    {
        return $this->em->find(ContaPagar::class, $id);
    }

    public function consultarDados(int $id): array
    {
        $contaPagar = $this->consultar($id);

        if(is_null($contaPagar)){
            throw new BadRequestException("Conta a pagar não encontrada!");
        }

        return $contaPagar->getDataApi();
    }

    public function deletar(int $id): ContaPagar
    {
        $contaPagar = $this->consultar($id);

        if(is_null($contaPagar)){
            throw new BadRequestException("Conta a pagar não encontrada!");
        }

        $contaPagar->setAtivo(false);

        $this->em->flush();

        return $contaPagar;
    }
}
