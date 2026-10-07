<?php
class Usuario {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /** Login por e-mail + senha. Retorna ['erro'=>bool, 'usuario'=>[], 'mensagem'=>string] */
    public function login(string $email, string $senha): array {
        $stmt = $this->pdo->prepare("
            SELECT id, nome_completo, email, senha, tipo_usuario, modalidade
            FROM usuarios
            WHERE email = ? AND ativo = 1
            LIMIT 1
        ");
        $stmt->execute([$email]);
        $u = $stmt->fetch();

        if (!$u || !password_verify($senha, $u['senha'])) {
            return ['erro' => true, 'mensagem' => 'E-mail ou senha inválidos.'];
        }

        return [
            'erro'    => false,
            'usuario' => [
                'id'         => (int)$u['id'],
                'nome'       => $u['nome_completo'],
                'email'      => $u['email'],
                'tipo'       => $u['tipo_usuario'], // admin | instrutor | usuario
                'modalidade' => $u['modalidade'],
            ],
        ];
    }

    /** Cadastra um novo usuário (tipo padrão: 'usuario'). */
    public function cadastrar(array $dados): array {
        $stmt = $this->pdo->prepare("
            INSERT INTO usuarios (nome_completo, email, senha, telefone, data_nascimento, cpf, modalidade, tipo_usuario)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'usuario')
        ");
        try {
            $stmt->execute([
                $dados['nome'],
                $dados['email'],
                password_hash($dados['senha'], PASSWORD_DEFAULT),
                $dados['telefone']        ?? null,
                $dados['data_nascimento'] ?? null,
                $dados['cpf']             ?? null,
                $dados['modalidade']      ?? 'Musculação',
            ]);
            return ['erro' => false, 'id' => (int)$this->pdo->lastInsertId()];
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return ['erro' => true, 'mensagem' => 'E-mail ou CPF já cadastrado.'];
            }
            return ['erro' => true, 'mensagem' => 'Erro ao cadastrar.'];
        }
    }

    /** Busca por ID. */
    public function buscar(int $id): ?array {
        $stmt = $this->pdo->prepare("
            SELECT id, nome_completo AS nome, email, tipo_usuario AS tipo, modalidade
            FROM usuarios WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}