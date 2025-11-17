<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
$data = json_decode(file_get_contents('php://input'), true);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($data['usuario'])) {
    $_SESSION['usuario'] = $data['usuario'];
    echo json_encode(['mensaje' => "Sesión iniciada para {$_SESSION['usuario']}"]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(['usuario' => $_SESSION['usuario'] ?? null]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    session_destroy();
    echo json_encode(['mensaje' => 'Sesión cerrada']);
    exit;
}
