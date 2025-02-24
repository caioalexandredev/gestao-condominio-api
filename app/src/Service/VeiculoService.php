<?php

namespace App\Service;

use App\Entity\Propriedade;
use App\Entity\Usuario;
use App\Entity\Veiculo;
use App\Exception\BadRequestException;
use App\Util\Paginacao;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class VeiculoService
{
    public function __construct(
        private EntityManager $em,
        private PessoaDadosService $pessoaDadosService,
        private VeiculoCorService $veiculoCorService,
        private PropriedadeService $propriedadeService,
        private VeiculoCategoriaService $veiculoCategoriaService,
        private VeiculoMarcaService $veiculoMarcaService,
        private Usuario $usuario,
        private SessionInterface $session
    ) {
        $this->usuario = $this->em->find(Usuario::class, $this->session->get('user'));
    }

    public function cadastrar(
        array $data
    ): Veiculo 
    {
        $proprietario = $this->pessoaDadosService->consultar($data['proprietario']);

        if(is_null($proprietario)){
            throw new BadRequestException("Proprietário não encontrada!");
        }

        $propriedade = $this->propriedadeService->consultar($data['propriedade']);

        if(is_null($propriedade)){
            throw new BadRequestException("Propriedade não encontrada!");
        }

        $marca = $this->veiculoMarcaService->consultar($data['marca']);

        if(is_null($marca)){
            throw new BadRequestException("Marca não encontrada!");
        }

        $categoria = $this->veiculoCategoriaService->consultar($data['categoria']);

        if(is_null($categoria)){
            throw new BadRequestException("Categoria não encontrada!");
        }

        $cor = $this->veiculoCorService->consultar($data['cor']);

        if(is_null($cor)){
            throw new BadRequestException("Cor não encontrada!");
        }

        $veiculo = new Veiculo();
        $veiculo->setProprietario($proprietario);
        $veiculo->setPropriedade($propriedade);
        $veiculo->setModelo($data["modelo"]);
        $veiculo->setPlaca(preg_replace('/\D/', '', $data['placa']));
        $veiculo->setAno($data["ano"]);
        $veiculo->setCategoria($categoria);
        $veiculo->setCor($cor);
        $veiculo->setMarca($marca);
        $veiculo->setUsuario($this->usuario);

        $this->em->persist($veiculo);
        $this->em->flush();

        return $veiculo;
    }

    public function atualizar(
        int $id,
        array $data
    ): Propriedade {
        
        $veiculo = $this->consultar($id);

        if(is_null($veiculo)){
            throw new BadRequestException("Veículo não encontrado!");
        }

        $proprietario = $this->pessoaDadosService->consultar($data['proprietario']);

        if(is_null($proprietario)){
            throw new BadRequestException("Proprietário não encontrada!");
        }

        $propriedade = $this->propriedadeService->consultar($data['propriedade']);

        if(is_null($propriedade)){
            throw new BadRequestException("Propriedade não encontrada!");
        }

        $marca = $this->veiculoMarcaService->consultar($data['marca']);

        if(is_null($marca)){
            throw new BadRequestException("Marca não encontrada!");
        }

        $categoria = $this->veiculoCategoriaService->consultar($data['categoria']);

        if(is_null($categoria)){
            throw new BadRequestException("Categoria não encontrada!");
        }

        $cor = $this->veiculoCorService->consultar($data['cor']);

        if(is_null($cor)){
            throw new BadRequestException("Cor não encontrada!");
        }

        try {
            $this->em->getConnection()->beginTransaction();

            $veiculo->setProprietario($proprietario);
            $veiculo->setPropriedade($propriedade);
            $veiculo->setModelo($data["modelo"]);
            $veiculo->setPlaca(preg_replace('/\D/', '', $data['placa']));
            $veiculo->setAno($data["ano"]);
            $veiculo->setCategoria($categoria);
            $veiculo->setCor($cor);
            $veiculo->setMarca($marca);

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
        ?string $categoria = null,
        ?string $placa = null,
        ?string $modelo = null,
        ?int $pagina = null
    ): array {
        $qb = $this->em->createQueryBuilder();

        $qb->select([
            'v.id AS id',
            'v.placa AS placa',
            "CONCAT(pd.nome, ' ', pd.sobrenome) AS proprietario",
            "c.descricao AS categoria",
            "v.modelo AS modelo",
            'v.dtInclusao AS dt_inclusao'
        ])->from(Veiculo::class, 'v')
            ->join('v.proprietario', 'pd')
            ->join('v.propriedade', 'p')
            ->join('v.categoria', 'c');

        if (!empty($categoria)) {
            $qb->andWhere($qb->expr()->eq('c.id', ':categoria'))
                ->setParameter('categoria', $categoria);
        }

        if (!empty($nome)) {
            $qb->andWhere(
                $qb->expr()->like("CONCAT(pd.nome, ' ', pd.sobrenome)", ':nome')
            )->setParameter('nome', '%' . $nome . '%');
        }

        if (!empty($placa)) {
            $qb->andWhere($qb->expr()->eq('v.placa', ':placa'))
                ->setParameter('placa', $placa);
        }

        if (!empty($modelo)) {
            $qb->andWhere(
                $qb->expr()->like("v.modelo", ':modelo')
            )->setParameter('modelo', '%' . $modelo . '%');
        }

        $qb->andWhere($qb->expr()->eq('v.ativo', true))
            ->orderBy('id', 'DESC');

        $dados = Paginacao::prepararListagem($qb->getQuery(), 10, $pagina ?? 1);

        foreach ($dados['resultado'] as &$registro) {
            if (isset($registro['dt_inclusao']) && $registro['dt_inclusao'] instanceof \DateTimeInterface) {
                $registro['dt_inclusao'] = $registro['dt_inclusao']->format('d/m/Y H:i:s');
            }
        }

        return $dados;
    }

    public function consultar(int $id): ?Veiculo
    {
        return $this->em->find(Veiculo::class, $id);
    }

    public function consultarDados(int $id): array
    {
        $veiculo = $this->consultar($id);

        if(is_null($veiculo)){
            throw new BadRequestException("Veículo não encontrada!");
        }

        return array_merge($veiculo->getDataApi());
    }

    public function deletar(int $id): Veiculo
    {
        $veiculo = $this->consultar($id);

        if(is_null($veiculo)){
            throw new BadRequestException("Veículo não encontrada!");
        }

        $veiculo->setAtivo(false);

        $this->em->flush();

        return $veiculo;
    }
}
