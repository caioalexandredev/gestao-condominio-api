<?php

namespace App\Service;

use App\Entity\PessoaDados;
use App\Entity\Endereco;
use App\Entity\Usuario;
use App\Util\Paginacao;
use DateTime;
use Doctrine\ORM\EntityManager;
use Odan\Session\SessionInterface;

class PessoaDadosService
{
    public function __construct(
        private EntityManager $em,
        private ContatoService $contatoService,
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
}
