<?php
require_once 'config.php';

// Verificar se usuário está logado
if (!isLoggedIn()) {
    $_SESSION['modal_message'] = 'Você precisa estar logado para agendar uma consulta.';
    $_SESSION['modal_message_type'] = 'error';
    header('Location: login.php');
    exit;
}

// Verificar se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');
    exit;
}

// Pegar os dados do formulário
$medico_id = intval($_POST['medico_id'] ?? 0);
$usuario_id = intval($_SESSION['usuario_id'] ?? 0);
$paciente_nome = trim($_POST['paciente_nome'] ?? '');
$paciente_email = trim($_POST['paciente_email'] ?? '');
$paciente_telefone = trim($_POST['paciente_telefone'] ?? '');
$data_consulta = $_POST['data_consulta'] ?? '';
$hora_consulta = $_POST['hora_consulta'] ?? '';
$observacoes = trim($_POST['observacoes'] ?? '');

// Validar os dados
$erros = [];

if ($medico_id <= 0) {
    $erros[] = 'Médico não selecionado.';
}

if (empty($paciente_nome)) {
    $erros[] = 'Nome do paciente é obrigatório.';
}

if (empty($paciente_email) || !filter_var($paciente_email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = 'E-mail inválido.';
}

if (empty($paciente_telefone)) {
    $erros[] = 'Telefone é obrigatório.';
}

if (empty($data_consulta)) {
    $erros[] = 'Data da consulta é obrigatória.';
}

if (empty($hora_consulta)) {
    $erros[] = 'Hora da consulta é obrigatória.';
}

// Verificar se a data é futura
if (!empty($data_consulta) && $data_consulta < date('Y-m-d')) {
    $erros[] = 'A data da consulta deve ser hoje ou futura.';
}

// Se houver erros, mostrar e voltar
if (!empty($erros)) {
    $_SESSION['modal_message'] = implode('<br>', $erros);
    $_SESSION['modal_message_type'] = 'error';
    header('Location: index.php');
    exit;
}

// Verificar se já existe agendamento para este médico nesta data/hora
$stmt = $conn->prepare("SELECT id FROM agendamentos WHERE medico_id = ? AND data_consulta = ? AND hora_consulta = ? AND status NOT IN ('cancelado', 'realizado')");
$stmt->bind_param("iss", $medico_id, $data_consulta, $hora_consulta);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['modal_message'] = 'Este horário já está ocupado. Por favor, escolha outro horário.';
    $_SESSION['modal_message_type'] = 'error';
    $stmt->close();
    header('Location: index.php');
    exit;
}
$stmt->close();

// Inserir o agendamento
$sql = "INSERT INTO agendamentos (medico_id, usuario_id, paciente_nome, paciente_email, paciente_telefone, data_consulta, hora_consulta, observacoes, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'agendado')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iissssss", $medico_id, $usuario_id, $paciente_nome, $paciente_email, $paciente_telefone, $data_consulta, $hora_consulta, $observacoes);

if ($stmt->execute()) {
    $_SESSION['modal_message'] = '✅ Agendamento realizado com sucesso!';
    $_SESSION['modal_message_type'] = 'success';
} else {
    $_SESSION['modal_message'] = '❌ Erro ao realizar agendamento: ' . $conn->error;
    $_SESSION['modal_message_type'] = 'error';
}

$stmt->close();

// Redirecionar de volta para a página inicial
header('Location: index.php');
exit;
?>