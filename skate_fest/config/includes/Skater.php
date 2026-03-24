<?php
// includes/Skater.php

class Skater {
    private $conn;
    private $table_name = "skatistas";

    public $id;
    public $nome;
    public $pais;
    public $idade;
    public $kickflip;
    public $heelflip;
    public $tre_flip;
    public $varial;
    public $laser;
    public $data_cadastro;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Cadastrar novo skatista
    public function cadastrar() {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                      SET nome = :nome,
                          pais = :pais,
                          idade = :idade,
                          kickflip = :kickflip,
                          heelflip = :heelflip,
                          tre_flip = :tre_flip,
                          varial = :varial,
                          laser = :laser";

            $stmt = $this->conn->prepare($query);

            // Sanitizar dados
            $this->nome = htmlspecialchars(strip_tags($this->nome));
            $this->pais = htmlspecialchars(strip_tags($this->pais));
            $this->idade = htmlspecialchars(strip_tags($this->idade));
            $this->kickflip = htmlspecialchars(strip_tags($this->kickflip));
            $this->heelflip = htmlspecialchars(strip_tags($this->heelflip));
            $this->tre_flip = htmlspecialchars(strip_tags($this->tre_flip));
            $this->varial = htmlspecialchars(strip_tags($this->varial));
            $this->laser = htmlspecialchars(strip_tags($this->laser));

            // Bind dos parâmetros
            $stmt->bindParam(":nome", $this->nome);
            $stmt->bindParam(":pais", $this->pais);
            $stmt->bindParam(":idade", $this->idade);
            $stmt->bindParam(":kickflip", $this->kickflip);
            $stmt->bindParam(":heelflip", $this->heelflip);
            $stmt->bindParam(":tre_flip", $this->tre_flip);
            $stmt->bindParam(":varial", $this->varial);
            $stmt->bindParam(":laser", $this->laser);

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
    }

    // Listar todos os skatistas
    public function listarTodos() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY media_geral DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obter ranking completo
    public function getRankingCompleto() {
        $query = "SELECT * FROM ranking_completo";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obter podium (top 3)
    public function getPodium() {
        $query = "SELECT * FROM ranking_podium";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obter estatísticas
    public function getEstatisticas() {
        $query = "SELECT * FROM estatisticas_competicao";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Resetar competição
    public function resetarCompeticao() {
        try {
            $query = "CALL resetar_competicao()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }

    // Validar dados
    public function validarDados() {
        $erros = [];

        if(empty($this->nome)) {
            $erros[] = "Nome é obrigatório";
        }

        if(empty($this->pais)) {
            $erros[] = "País é obrigatório";
        }

        if($this->idade < 10 || $this->idade > 60) {
            $erros[] = "Idade deve estar entre 10 e 60 anos";
        }

        $notas = [$this->kickflip, $this->heelflip, $this->tre_flip, $this->varial, $this->laser];
        foreach($notas as $nota) {
            if($nota < 0 || $nota > 10) {
                $erros[] = "Todas as notas devem estar entre 0 e 10";
                break;
            }
        }

        return $erros;
    }
}
?>