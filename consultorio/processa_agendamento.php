<?php
session_start();
require_once 'config.php';

// Verificar se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Receber e limpar os dados do formulário
$medico_id = filter_input(INPUT_POST, 'medico_id', FILTER_VALIDATE_INT);
$paciente_nome = trim(filter_input(INPUT_POST, 'paciente_nome', FILTER_SANITIZE_STRING));
$paciente_email = trim(filter_input(INPUT_POST, 'paciente_email', FILTER_SANITIZE_EMAIL));
$paciente_telefone = trim(filter_input(INPUT_POST, 'paciente_telefone', FILTER_SANITIZE_STRING));
$data_consulta = filter_input(INPUT_POST, 'data_consulta', FILTER_SANITIZE_STRING);
$hora_consulta = filter_input(INPUT_POST, 'hora_consulta', FILTER_SANITIZE_STRING);
$observacoes = trim(filter_input(INPUT_POST, 'observacoes', FILTER_SANITIZE_STRING));

// Validar campos obrigatórios
$erros = [];

if (!$medico_id) {
    $erros[] = "ID do médico inválido";
}

if (empty($paciente_nome)) {
    $erros[] = "Nome do paciente é obrigatório";
}

if (empty($paciente_email) || !filter_var($paciente_email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = "E-mail inválido";
}

if (empty($paciente_telefone)) {
    $erros[] = "Telefone é obrigatório";
}

if (empty($data_consulta)) {
    $erros[] = "Data da consulta é obrigatória";
} else {
    // Verificar se a data é futura
    $hoje = date('Y-m-d');
    if ($data_consulta < $hoje) {
        $erros[] = "A data da consulta deve ser a partir de hoje";
    }
}

if (empty($hora_consulta)) {
    $erros[] = "Hora da consulta é obrigatória";
}

// Se houver erros, redirecionar de volta com mensagens
if (!empty($erros)) {
    $_SESSION['modal_message'] = implode("<br>", $erros);
    $_SESSION['modal_message_type'] = 'error';
    header('Location: index.php');
    exit;
}

// Verificar se o médico existe e está ativo
$sql_check_medico = "SELECT id FROM medicos WHERE id = ? AND status = 'ativo'";
$stmt_check = $conn->prepare($sql_check_medico);
$stmt_check->bind_param("i", $medico_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    $_SESSION['modal_message'] = "Médico não encontrado ou não está disponível";
    $_SESSION['modal_message_type'] = 'error';
    header('Location: index.php');
    exit;
}
$stmt_check->close();

// Verificar se já existe agendamento para o mesmo médico na mesma data e hora
$sql_check_horario = "SELECT id FROM agendamentos 
                      WHERE medico_id = ? 
                      AND data_consulta = ? 
                      AND hora_consulta = ?
                      AND status != 'cancelado'";
$stmt_check_horario = $conn->prepare($sql_check_horario);
$stmt_check_horario->bind_param("iss", $medico_id, $data_consulta, $hora_consulta);
$stmt_check_horario->execute();
$result_check_horario = $stmt_check_horario->get_result();

if ($result_check_horario->num_rows > 0) {
    $_SESSION['modal_message'] = "Este horário já está agendado para este médico";
    $_SESSION['modal_message_type'] = 'error';
    header('Location: index.php');
    exit;
}
$stmt_check_horario->close();

// Preparar a query de inserção
$sql = "INSERT INTO agendamentos (
    medico_id, 
    paciente_nome, 
    paciente_email, 
    paciente_telefone, 
    data_consulta, 
    hora_consulta, 
    observacoes, 
    status,
    data_criacao
) VALUES (?, ?, ?, ?, ?, ?, ?, 'agendado', NOW())";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    $_SESSION['modal_message'] = "Erro no sistema: " . $conn->error;
    $_SESSION['modal_message_type'] = 'error';
    header('Location: index.php');
    exit;
}

// Bind dos parâmetros
$stmt->bind_param(
    "issssss",
    $medico_id,
    $paciente_nome,
    $paciente_email,
    $paciente_telefone,
    $data_consulta,
    $hora_consulta,
    $observacoes
);

// Executar a query
if ($stmt->execute()) {
    // Agendamento realizado com sucesso
    $agendamento_id = $stmt->insert_id;
    
    // Aqui você pode adicionar código para enviar e-mail de confirmação
    // enviarEmailConfirmacao($paciente_email, $paciente_nome, $data_consulta, $hora_consulta);
    
    $_SESSION['modal_message'] = "Consulta agendada com sucesso! ID do agendamento: #" . $agendamento_id;
    $_SESSION['modal_message_type'] = 'success';
} else {
    // Erro ao inserir
    $_SESSION['modal_message'] = "Erro ao agendar consulta: " . $stmt->error;
    $_SESSION['modal_message_type'] = 'error';
}

// Fechar a declaração e a conexão
$stmt->close();
$conn->close();

// Redirecionar de volta para a página inicial
header('Location: index.php');
exit;
?>

