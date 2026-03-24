<?php
// config.php - Configurações do banco de dados
session_start();

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'skate_fest_competition');
define('DB_USER', 'root'); // Altere conforme sua configuração
define('DB_PASS', ''); // Altere conforme sua configuração

// Função para conectar ao banco de dados
function conectarBD() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Erro de conexão com o banco de dados: " . $e->getMessage());
    }
}

// Função para calcular a média das notas
function calcularMedia($notas) {
    if (empty($notas)) return 0;
    return array_sum($notas) / count($notas);
}

// Função para adicionar um novo skatista
function adicionarSkater($dados) {
    $pdo = conectarBD();
    
    // Calcular média
    $notas = [
        floatval($dados['manobra1']),
        floatval($dados['manobra2']),
        floatval($dados['manobra3']),
        floatval($dados['manobra4']),
        floatval($dados['manobra5'])
    ];
    $media = calcularMedia($notas);
    
    // Preparar SQL
    $sql = "INSERT INTO skatistas (nome, pais, idade, nota_kickflip, nota_heelflip, nota_treflip, nota_varial, nota_laser, media) 
            VALUES (:nome, :pais, :idade, :kickflip, :heelflip, :treflip, :varial, :laser, :media)";
    
    $stmt = $pdo->prepare($sql);
    
    // Executar com os dados
    $stmt->execute([
        ':nome' => htmlspecialchars($dados['nome']),
        ':pais' => htmlspecialchars($dados['pais']),
        ':idade' => intval($dados['idade']),
        ':kickflip' => $notas[0],
        ':heelflip' => $notas[1],
        ':treflip' => $notas[2],
        ':varial' => $notas[3],
        ':laser' => $notas[4],
        ':media' => $media
    ]);
    
    return [
        'id' => $pdo->lastInsertId(),
        'nome' => $dados['nome'],
        'media' => $media
    ];
}

// Função para listar skatistas ordenados por média
function listarSkaters() {
    $pdo = conectarBD();
    
    $sql = "SELECT id, nome, pais, idade, media, data_cadastro 
            FROM skatistas 
            ORDER BY media DESC, data_cadastro ASC";
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Função para resetar todos os skatistas
function resetarCompeticao() {
    $pdo = conectarBD();
    $sql = "TRUNCATE TABLE skatistas";
    $pdo->exec($sql);
    return true;
}

// Função para obter estatísticas
function getEstatisticas() {
    $pdo = conectarBD();
    
    $sql = "SELECT 
                COUNT(*) as total,
                AVG(media) as media_geral,
                MAX(media) as maior_nota,
                MIN(media) as menor_nota
            FROM skatistas";
    
    $stmt = $pdo->query($sql);
    return $stmt->fetch();
}

// Processar requisições AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'cadastrar':
            try {
                // Validar dados
                $erros = [];
                
                if (empty($_POST['nome'])) $erros[] = 'Nome é obrigatório';
                if (empty($_POST['pais'])) $erros[] = 'País é obrigatório';
                if (empty($_POST['idade']) || $_POST['idade'] < 10 || $_POST['idade'] > 60) {
                    $erros[] = 'Idade deve ser entre 10 e 60 anos';
                }
                
                for ($i = 1; $i <= 5; $i++) {
                    if (!isset($_POST["manobra$i"]) || $_POST["manobra$i"] === '') {
                        $erros[] = "Nota da manobra $i é obrigatória";
                    } elseif ($_POST["manobra$i"] < 0 || $_POST["manobra$i"] > 10) {
                        $erros[] = "Nota da manobra $i deve ser entre 0 e 10";
                    }
                }
                
                if (!empty($erros)) {
                    echo json_encode(['success' => false, 'errors' => $erros]);
                    exit;
                }
                
                $novoSkater = adicionarSkater($_POST);
                echo json_encode(['success' => true, 'skater' => $novoSkater]);
                
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'errors' => ['Erro ao cadastrar: ' . $e->getMessage()]]);
            }
            break;
            
        case 'listar':
            $skaters = listarSkaters();
            echo json_encode(['success' => true, 'skaters' => $skaters]);
            break;
            
        case 'resetar':
            resetarCompeticao();
            echo json_encode(['success' => true]);
            break;
            
        case 'estatisticas':
            $estatisticas = getEstatisticas();
            echo json_encode(['success' => true, 'estatisticas' => $estatisticas]);
            break;
            
        default:
            echo json_encode(['success' => false, 'errors' => ['Ação inválida']]);
    }
    exit;
}

// Carregar dados iniciais para exibição
$skaters = listarSkaters();
$totalParticipantes = count($skaters);
$primeirosColocados = array_slice($skaters, 0, 3);
$estatisticas = getEstatisticas();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skate Fest - Sistema de Competição</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container-principal {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .cabecalho {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        .titulo-principal {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .titulo-palavra {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0 5px;
        }

        .subtitulo {
            font-size: 1.2rem;
            color: #666;
            letter-spacing: 3px;
        }

        .grid-conteudo {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .card-cadastro, .card-ranking {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .card-titulo {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .card-titulo h2 {
            color: #333;
            font-size: 1.5rem;
        }

        .formulario {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .campo {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .campo label {
            font-weight: bold;
            color: #555;
            font-size: 0.9rem;
        }

        .campo input {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .campo input:focus {
            outline: none;
            border-color: #667eea;
        }

        .linha-campos {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .secao-notas h3 {
            margin-bottom: 15px;
            color: #555;
            font-size: 1rem;
        }

        .grid-notas {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 15px;
        }

        .item-nota {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .item-nota label {
            font-size: 0.8rem;
            font-weight: bold;
            color: #666;
        }

        .input-nota {
            padding: 8px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            text-align: center;
        }

        .btn-radical {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .btn-radical:hover {
            transform: translateY(-2px);
        }

        .btn-limpar {
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
        }

        .btn-limpar:hover {
            background: #c82333;
        }

        .mensagem {
            padding: 10px;
            border-radius: 8px;
            display: none;
        }

        .mensagem.sucesso {
            background: #d4edda;
            color: #155724;
            display: block;
        }

        .mensagem.erro {
            background: #f8d7da;
            color: #721c24;
            display: block;
        }

        .estatisticas {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        .stats-numero {
            font-size: 2rem;
            font-weight: bold;
            color: white;
            display: block;
        }

        .stats-texto {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
        }

        .estatisticas-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .estatistica-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            border-left: 4px solid #667eea;
        }

        .estatistica-valor {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }

        .estatistica-label {
            font-size: 0.8rem;
            color: #666;
            margin-top: 5px;
        }

        .podium-container {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            gap: 20px;
            margin: 30px 0;
            min-height: 250px;
        }

        .podium-item {
            text-align: center;
            flex: 1;
            position: relative;
        }

        .podium-base {
            width: 100%;
            background: #e0e0e0;
            margin: 10px 0;
            transition: height 0.3s;
        }

        .podium-base.primeiro {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            height: 120px;
        }

        .podium-base.segundo {
            background: linear-gradient(135deg, #c0c0c0 0%, #e8e8e8 100%);
            height: 80px;
        }

        .podium-base.terceiro {
            background: linear-gradient(135deg, #cd7f32 0%, #e6a756 100%);
            height: 60px;
        }

        .podium-numero {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .coroa {
            font-size: 2rem;
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
        }

        .podium-info {
            margin-top: 10px;
            font-size: 0.8rem;
        }

        .lista-skate {
            max-height: 400px;
            overflow-y: auto;
        }

        .skater-item {
            background: #f8f9fa;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s;
        }

        .skater-item:hover {
            transform: translateX(5px);
        }

        .skater-info {
            flex: 1;
        }

        .skater-nome {
            font-weight: bold;
            color: #333;
        }

        .skater-pais {
            font-size: 0.8rem;
            color: #666;
        }

        .skater-media {
            font-weight: bold;
            color: #667eea;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .grid-conteudo {
                grid-template-columns: 1fr;
            }
            
            .titulo-principal {
                font-size: 2rem;
            }
            
            .estatisticas-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="container-principal">
        <header class="cabecalho">
            <h1 class="titulo-principal">
                <span class="titulo-palavra">SKATE</span>
                <span class="titulo-palavra">FEST</span>
            </h1>
            <p class="subtitulo">SISTEMA DE COMPETIÇÃO</p>
        </header>

        <div class="grid-conteudo">
            <section class="card-cadastro">
                <div class="card-titulo">
                    <h2>CADASTRO DE SKATISTA</h2>
                </div>
                
                <form id="formSkater" class="formulario">
                    <div class="campo">
                        <label for="nome">NOME COMPLETO</label>
                        <input type="text" id="nome" name="nome" placeholder="Ex: TONY HAWK" required>
                    </div>

                    <div class="linha-campos">
                        <div class="campo">
                            <label for="pais">PAÍS</label>
                            <input type="text" id="pais" name="pais" placeholder="BRASIL" required>
                        </div>

                        <div class="campo">
                            <label for="idade">IDADE</label>
                            <input type="number" id="idade" name="idade" placeholder="18" min="10" max="60" required>
                        </div>
                    </div>

                    <div class="secao-notas">
                        <h3>NOTAS DAS MANOBRAS (0 a 10)</h3>

                        <div class="grid-notas">
                            <div class="item-nota">
                                <label>KICKFLIP</label>
                                <input type="number" name="manobra1" class="input-nota" min="0" max="10" step="0.1" placeholder="0.0" required>
                            </div>
                            
                            <div class="item-nota">
                                <label>HEELFLIP</label>
                                <input type="number" name="manobra2" class="input-nota" min="0" max="10" step="0.1" placeholder="0.0" required>
                            </div>
                            
                            <div class="item-nota">
                                <label>TRE FLIP</label>
                                <input type="number" name="manobra3" class="input-nota" min="0" max="10" step="0.1" placeholder="0.0" required>
                            </div>
                            
                            <div class="item-nota">
                                <label>VARIAL</label>
                                <input type="number" name="manobra4" class="input-nota" min="0" max="10" step="0.1" placeholder="0.0" required>
                            </div>
                            
                            <div class="item-nota">
                                <label>LASER</label>
                                <input type="number" name="manobra5" class="input-nota" min="0" max="10" step="0.1" placeholder="0.0" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-radical">CADASTRAR SKATISTA</button>
                    <div id="mensagem" class="mensagem"></div>
                </form>
            </section>

            <section class="card-ranking">
                <div class="card-titulo">
                    <h2>RANKING</h2>
                </div>

                <div class="estatisticas" id="totalSkaters">
                    <span class="stats-numero" id="totalParticipantes"><?php echo $totalParticipantes; ?></span>
                    <span class="stats-texto">PARTICIPANTES</span>
                </div>

                <div class="estatisticas-grid">
                    <div class="estatistica-card">
                        <div class="estatistica-valor" id="mediaGeral"><?php echo number_format($estatisticas['media_geral'] ?? 0, 1); ?></div>
                        <div class="estatistica-label">MÉDIA GERAL</div>
                    </div>
                    <div class="estatistica-card">
                        <div class="estatistica-valor" id="maiorNota"><?php echo number_format($estatisticas['maior_nota'] ?? 0, 1); ?></div>
                        <div class="estatistica-label">MAIOR NOTA</div>
                    </div>
                    <div class="estatistica-card">
                        <div class="estatistica-valor" id="menorNota"><?php echo number_format($estatisticas['menor_nota'] ?? 0, 1); ?></div>
                        <div class="estatistica-label">MENOR NOTA</div>
                    </div>
                </div>

                <div class="podium-container" id="podiumContainer">
                    <div class="podium-item segundo" id="podium2">
                        <div class="podium-numero">2º</div>
                        <div class="podium-base segundo"></div>
                        <div class="podium-info">
                            <?php if(isset($primeirosColocados[1])): ?>
                                <strong><?php echo htmlspecialchars($primeirosColocados[1]['nome']); ?></strong><br>
                                Média: <?php echo number_format($primeirosColocados[1]['media'], 1); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="podium-item primeiro" id="podium1">
                        <div class="podium-numero">1º</div>
                        <div class="podium-base primeiro"></div>
                        <div class="coroa">👑</div>
                        <div class="podium-info">
                            <?php if(isset($primeirosColocados[0])): ?>
                                <strong><?php echo htmlspecialchars($primeirosColocados[0]['nome']); ?></strong><br>
                                Média: <?php echo number_format($primeirosColocados[0]['media'], 1); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="podium-item terceiro" id="podium3">
                        <div class="podium-numero">3º</div>
                        <div class="podium-base terceiro"></div>
                        <div class="podium-info">
                            <?php if(isset($primeirosColocados[2])): ?>
                                <strong><?php echo htmlspecialchars($primeirosColocados[2]['nome']); ?></strong><br>
                                Média: <?php echo number_format($primeirosColocados[2]['media'], 1); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="lista-skate" id="listaSkaters">
                    <?php foreach($skaters as $index => $skater): ?>
                        <div class="skater-item" data-id="<?php echo $skater['id']; ?>">
                            <div class="skater-info">
                                <div class="skater-nome">
                                    <?php echo ($index + 1) . "º - " . htmlspecialchars($skater['nome']); ?>
                                </div>
                                <div class="skater-pais">
                                    <?php echo htmlspecialchars($skater['pais']); ?> • <?php echo $skater['idade']; ?> anos
                                </div>
                            </div>
                            <div class="skater-media">
                                <?php echo number_format($skater['media'], 1); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button class="btn-limpar" id="limparTodos">RESETAR COMPETIÇÃO</button>
            </section>
        </div>
    </main>

    <script>
        // Função para atualizar o ranking via AJAX
        function atualizarRanking() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=listar'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const skaters = data.skaters;
                    
                    // Atualizar total de participantes
                    document.getElementById('totalParticipantes').textContent = skaters.length;
                    
                    // Atualizar pódio
                    const podium1 = document.querySelector('#podium1 .podium-info');
                    const podium2 = document.querySelector('#podium2 .podium-info');
                    const podium3 = document.querySelector('#podium3 .podium-info');
                    
                    if (skaters[0]) {
                        podium1.innerHTML = `<strong>${skaters[0].nome}</strong><br>Média: ${skaters[0].media.toFixed(1)}`;
                    } else {
                        podium1.innerHTML = '';
                    }
                    
                    if (skaters[1]) {
                        podium2.innerHTML = `<strong>${skaters[1].nome}</strong><br>Média: ${skaters[1].media.toFixed(1)}`;
                    } else {
                        podium2.innerHTML = '';
                    }
                    
                    if (skaters[2]) {
                        podium3.innerHTML = `<strong>${skaters[2].nome}</strong><br>Média: ${skaters[2].media.toFixed(1)}`;
                    } else {
                        podium3.innerHTML = '';
                    }
                    
                    // Atualizar lista completa
                    const listaContainer = document.getElementById('listaSkaters');
                    listaContainer.innerHTML = '';
                    
                    skaters.forEach((skater, index) => {
                        const skaterDiv = document.createElement('div');
                        skaterDiv.className = 'skater-item';
                        skaterDiv.innerHTML = `
                            <div class="skater-info">
                                <div class="skater-nome">
                                    ${index + 1}º - ${skater.nome}
                                </div>
                                <div class="skater-pais">
                                    ${skater.pais} • ${skater.idade} anos
                                </div>
                            </div>
                            <div class="skater-media">
                                ${skater.media.toFixed(1)}
                            </div>
                        `;
                        listaContainer.appendChild(skaterDiv);
                    });
                }
            });
        }
        
        // Função para atualizar estatísticas
        function atualizarEstatisticas() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=estatisticas'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const stats = data.estatisticas;
                    document.getElementById('mediaGeral').textContent = stats.media_geral ? stats.media_geral.toFixed(1) : '0.0';
                    document.getElementById('maiorNota').textContent = stats.maior_nota ? stats.maior_nota.toFixed(1) : '0.0';
                    document.getElementById('menorNota').textContent = stats.menor_nota ? stats.menor_nota.toFixed(1) : '0.0';
                }
            });
        }
        
        // Cadastrar skatista
        document.getElementById('formSkater').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'cadastrar');
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const mensagemDiv = document.getElementById('mensagem');
                
                if (data.success) {
                    mensagemDiv.className = 'mensagem sucesso';
                    mensagemDiv.textContent = 'Skatista cadastrado com sucesso!';
                    
                    // Limpar formulário
                    this.reset();
                    
                    // Atualizar ranking e estatísticas
                    atualizarRanking();
                    atualizarEstatisticas();
                    
                    // Esconder mensagem após 3 segundos
                    setTimeout(() => {
                        mensagemDiv.className = 'mensagem';
                    }, 3000);
                } else {
                    mensagemDiv.className = 'mensagem erro';
                    mensagemDiv.textContent = data.errors.join(', ');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                const mensagemDiv = document.getElementById('mensagem');
                mensagemDiv.className = 'mensagem erro';
                mensagemDiv.textContent = 'Erro ao cadastrar skatista';
            });
        });
        
        // Resetar competição
        document.getElementById('limparTodos').addEventListener('click', function() {
            if (confirm('Tem certeza que deseja resetar toda a competição? Esta ação não pode ser desfeita.')) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=resetar'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        atualizarRanking();
                        atualizarEstatisticas();
                        const mensagemDiv = document.getElementById('mensagem');
                        mensagemDiv.className = 'mensagem sucesso';
                        mensagemDiv.textContent = 'Competição resetada com sucesso!';
                        
                        setTimeout(() => {
                            mensagemDiv.className = 'mensagem';
                        }, 3000);
                    }
                });
            }
        });
        
        // Atualizar dados a cada 10 segundos
        setInterval(() => {
            atualizarRanking();
            atualizarEstatisticas();
        }, 10000);
    </script>
</body>
</html>