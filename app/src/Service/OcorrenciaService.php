<?php

namespace App\Service;

use App\Entity\ContaPagar;
use App\Entity\Informativo;
use App\Entity\Ocorrencia;
use App\Entity\Usuario;
use App\Exception\BadRequestException;
use App\Util\Paginacao;
use DateTime;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class OcorrenciaService
{
    public function __construct(
        private EntityManager $em,
        private Usuario $usuario,
        private OcorrenciaTipoService $ocorrenciaTipoService,
        private SessionInterface $session
    ) {
        $this->usuario = $this->em->find(Usuario::class, $this->session->get('user'));
    }

    public function cadastrar(
        array $data
    ): Ocorrencia {
        $tipo = $this->ocorrenciaTipoService->consultar($data['tipo']);

        if (is_null($tipo)) {
            throw new BadRequestException("Tipo não encontrado!");
        }

        $ocorrencia = new Ocorrencia();
        $ocorrencia->setAssunto($data["assunto"]);
        $ocorrencia->setDescricao($data["descricao"]);
        $ocorrencia->setTipo($tipo);
        $ocorrencia->setDtOcorrencia(new DateTime($data["dt_ocorrencia"]));
        $ocorrencia->setDtInclusao(new DateTime());
        $ocorrencia->setUsuario($this->usuario);

        $this->em->persist($ocorrencia);
        $this->em->flush();

        return $ocorrencia;
    }

    public function atualizar(
        int $id,
        array $data
    ): Ocorrencia {

        $ocorrencia = $this->consultar($id);

        if (is_null($ocorrencia)) {
            throw new BadRequestException("Ocorrência não encontrada!");
        }

        $tipo = $this->ocorrenciaTipoService->consultar($data['tipo']);

        if (is_null($tipo)) {
            throw new BadRequestException("Tipo não encontrado!");
        }

        try {
            $this->em->getConnection()->beginTransaction();

            $ocorrencia->setAssunto($data["assunto"]);
            $ocorrencia->setDescricao($data["descricao"]);
            $ocorrencia->setTipo($tipo);
            $ocorrencia->setDtOcorrencia(new DateTime($data["dt_ocorrencia"]));

            $this->em->flush();
            $this->em->getConnection()->commit();

            return $ocorrencia;
        } catch (\Throwable $th) {
            $this->em->getConnection()->rollBack();
            throw $th;
        }
    }

    public function listagem(
        ?string $assunto = null,
        ?string $dtInicioOcorrencia = null,
        ?string $dtFimOcorrencia = null,
        ?int $pagina = null
    ): array {
        $qb = $this->em->createQueryBuilder();

        $qb->select([
            'o.id AS id',
            'o.assunto AS assunto',
            'ot.descricao AS tipo',
            'o.dtOcorrencia AS dt_ocorrencia',
            'o.dtInclusao AS dt_inclusao'
        ])->from(Ocorrencia::class, 'o')
            ->join('o.tipo', 'ot')
            ->join('o.usuario', 'u')
            ->join('u.pessoa', 'p');

        if (!empty($assunto)) {
            $qb->andWhere(
                $qb->expr()->like("o.assunto", ':assunto')
            )->setParameter('assunto', '%' . $assunto . '%');
        }

        if (!is_null($dtInicioOcorrencia)) {
            $dtInicioOcorrencia = new DateTime($dtInicioOcorrencia);
            $qb->andWhere($qb->expr()->gte('o.dtOcorrencia', ':dtInicioOcorrencia'))
                ->setParameter('dtInicioOcorrencia', $dtInicioOcorrencia->format('Y-m-d'));
        }

        if (!is_null($dtFimOcorrencia)) {
            $dtFimOcorrencia = new DateTime($dtFimOcorrencia);
            $qb->andWhere($qb->expr()->lte('o.dtOcorrencia', ':dtFimOcorrencia'))
                ->setParameter('dtFimOcorrencia', $dtFimOcorrencia->format('Y-m-d'));
        }

        $qb->andWhere($qb->expr()->eq('o.ativo', true))
            ->orderBy('id', 'DESC');

        $dados = Paginacao::prepararListagem($qb->getQuery(), 10, $pagina ?? 1);

        foreach ($dados['resultado'] as &$registro) {
            if (isset($registro['dt_ocorrencia']) && $registro['dt_ocorrencia'] instanceof \DateTimeInterface) {
                $registro['dt_ocorrencia'] = $registro['dt_ocorrencia']->format('d/m/Y H:i:s');
            }
        }

        return $dados;
    }

    public function consultar(int $id): ?Ocorrencia
    {
        return $this->em->find(Ocorrencia::class, $id);
    }

    public function consultarDados(int $id): array
    {
        $ocorrencia = $this->consultar($id);

        if (is_null($ocorrencia)) {
            throw new BadRequestException("Ocorrência não encontrada!");
        }

        return $ocorrencia->getDataApi();
    }

    public function deletar(int $id): Ocorrencia
    {
        $ocorrencia = $this->consultar($id);

        if (is_null($ocorrencia)) {
            throw new BadRequestException("Ocorrência não encontrada!");
        }

        $ocorrencia->setAtivo(false);

        $this->em->flush();

        return $ocorrencia;
    }
}
