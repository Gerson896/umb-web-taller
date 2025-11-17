<?php
// index.php - endpoint público de la API
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *"); // para desarrollo. En producción restringir dominios.
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . '/modelo.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Suponiendo que /api/index.php se expone como la raíz, usar query param o path
// Para simplicidad, manejamos operaciones según method y parámetros enviados en JSON.
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $tarea = obtenerTarea((int)$_GET['id']);
            echo json_encode($tarea ?: []);
        } else {
            $tareas = obtenerTareas();
            echo json_encode($tareas);
        }
        break;

    case 'POST':
        // crear
        $titulo = $input['titulo'] ?? null;
        if (!$titulo) {
            http_response_code(400);
            echo json_encode(['error' => 'titulo es requerido']);
            break;
        }
        $tarea = crearTarea($titulo);
        echo json_encode($tarea);
        break;

    case 'PUT':
    case 'PATCH':
        $id = $input['id'] ?? null;
        if (!$id) { http_response_code(400); echo json_encode(['error'=>'id requerido']); break; }
        $titulo = $input['titulo'] ?? null;
        $completada = isset($input['completada']) ? (bool)$input['completada'] : null;
        $tarea = actualizarTarea($id, $titulo, $completada);
        echo json_encode($tarea);
        break;

    case 'DELETE':
        $id = $input['id'] ?? $_GET['id'] ?? null;
        if (!$id) { http_response_code(400); echo json_encode(['error'=>'id requerido']); break; }
        $success = eliminarTarea($id);
        echo json_encode(['ok' => $success]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
