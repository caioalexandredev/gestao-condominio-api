<?php

namespace App\Service;

use App\Entity\ContaPagar;
use App\Entity\Informativo;
use App\Entity\Usuario;
use App\Exception\BadRequestException;
use App\Util\Paginacao;
use DateTime;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class InformativoService
{
    public function __construct(
        private EntityManager $em,
        private Usuario $usuario,
        private InformativoVisibilidadeService $informativoVisibilidadeService,
        private SessionInterface $session
    ) {
        $this->usuario = $this->em->find(Usuario::class, $this->session->get('user'));
    }

    public function cadastrar(
        array $data
    ): Informativo
    {
        $visibilidade = $this->informativoVisibilidadeService->consultar($data['visibilidade']);

        if(is_null($visibilidade)){
            throw new BadRequestException("Visibilidade não encontrada!");
        }

        $informativo = new Informativo();
        $informativo->setAssunto($data["assunto"]);
        $informativo->setInformacao($data["informacao"]);
        $informativo->setVisibilidade($visibilidade);
        $informativo->setDtInclusao(new DateTime());
        $informativo->setUsuario($this->usuario);

        $this->em->persist($informativo);
        $this->em->flush();

        return $informativo;
    }

    public function atualizar(
        int $id,
        array $data
    ): Informativo {
        
        $informativo = $this->consultar($id);

        if(is_null($informativo)){
            throw new BadRequestException("Informativo não encontrado!");
        }

        $visibilidade = $this->informativoVisibilidadeService->consultar($data['visibilidade']);

        if(is_null($visibilidade)){
            throw new BadRequestException("Visibilidade não encontrada!");
        }

        try {
            $this->em->getConnection()->beginTransaction();

            $informativo->setAssunto($data["assunto"]);
            $informativo->setInformacao($data["informacao"]);
            $informativo->setVisibilidade($visibilidade);

            $this->em->flush();
            $this->em->getConnection()->commit();

            return $informativo;
        } catch (\Throwable $th) {
            $this->em->getConnection()->rollBack();
            throw $th;
        }
    }

    public function listagem(
        ?string $assunto = null,
        ?string $visibilidade = null,
        ?string $dtInicioInclusao = null,
        ?string $dtFimInclusao = null,
        ?int $pagina = null
    ): array {
        $qb = $this->em->createQueryBuilder();

        $qb->select([
            'i.id AS id',
            'i.assunto AS assunto',
            'iv.descricao AS visibilidade',
            'i.dtInclusao AS dt_inclusao'
        ])->from(Informativo::class, 'i')
            ->join('i.visibilidade', 'iv');

        if (!empty($visibilidade)) {
            $qb->andWhere($qb->expr()->eq('iv.id', ':visibilidade'))
                ->setParameter('visibilidade', $visibilidade);
        }

        if (!empty($assunto)) {
            $qb->andWhere(
                $qb->expr()->like("i.assunto", ':assunto')
            )->setParameter('assunto', '%' . $assunto . '%');
        }

        if (!is_null($dtInicioInclusao)) {
            $dtInicioInclusao = new DateTime($dtInicioInclusao);
            $qb->andWhere($qb->expr()->gte('i.dtInclusao', ':dtInicioInclusao'))
                ->setParameter('dtInicioInclusao', $dtInicioInclusao->format('Y-m-d'));
        }

        if (!is_null($dtFimInclusao)) {
            $dtFimInclusao = new DateTime($dtFimInclusao);
            $qb->andWhere($qb->expr()->lte('i.dtInclusao', ':dtFimInclusao'))
                ->setParameter('dtFimInclusao', $dtFimInclusao->format('Y-m-d'));
        }

        $qb->andWhere($qb->expr()->eq('i.ativo', true))
            ->orderBy('id', 'DESC');

        $dados = Paginacao::prepararListagem($qb->getQuery(), 10, $pagina ?? 1);

        foreach ($dados['resultado'] as &$registro) {
            if (isset($registro['dt_inclusao']) && $registro['dt_inclusao'] instanceof \DateTimeInterface) {
                $registro['dt_inclusao'] = $registro['dt_inclusao']->format('d/m/Y H:i:s');
            }
        }

        return $dados;
    }

    public function consultar(int $id): ?Informativo
    {
        return $this->em->find(Informativo::class, $id);
    }

    public function consultarDados(int $id): array
    {
        $informativo = $this->consultar($id);

        if(is_null($informativo)){
            throw new BadRequestException("Informativo não encontrada!");
        }

        return $informativo->getDataApi();
    }

    public function deletar(int $id): Informativo
    {
        $informativo = $this->consultar($id);

        if(is_null($informativo)){
            throw new BadRequestException("Informativo não encontrada!");
        }

        $informativo->setAtivo(false);

        $this->em->flush();

        return $informativo;
    }
}
