
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
     <section class="form-section">
            <h2>📋 Cadastrar Novo Médico</h2>
            
            <?php 
            // Verificar se existe mensagem para exibir
            
            if (isset($_SESSION['message'])) {
                $message_type = $_SESSION['message_type'] ?? 'info';
                echo "<div class='alert alert-{$message_type}'>{$_SESSION['message']}</div>";
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            ?>
            
            <form id="form-medico" action="processa_medico.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" name="nome" placeholder="Nome completo do médico" required>
                </div>
                <div class="form-group">
                    <input type="text" name="especialidade" placeholder="Especialidade" required>
                </div>
                <div class="form-group">
                    <input type="text" name="crm" placeholder="CRM" required>
                </div>
                <div class="form-group">
                    <textarea name="descricao" placeholder="Descrição breve" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="foto">Foto do Médico:</label>
                    <input type="file" name="foto" id="foto" accept="image/*" required>
                    <small>Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB</small>
                </div>
                <button type="submit">Cadastrar Médico</button>
            </form>
        </section>

</body>
</html>