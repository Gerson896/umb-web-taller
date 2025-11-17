<?php
require_once __DIR__ . '/db.php';

// CREATE
function crearTarea($titulo) {
    $pdo = getPDO();
    $sql = "INSERT INTO public.tareas (titulo) VALUES (:titulo) RETURNING id, titulo, completada, created_at";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':titulo' => $titulo]);
    return $stmt->fetch();
}

// READ ALL
function obtenerTareas() {
    $pdo = getPDO();
    $sql = "SELECT id, titulo, completada, created_at FROM public.tareas ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// READ ONE
function obtenerTarea($id) {
    $pdo = getPDO();
    $sql = "SELECT id, titulo, completada, created_at FROM public.tareas WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

// UPDATE (toggle completada / update titulo)
function actualizarTarea($id, $titulo = null, $completada = null) {
    $pdo = getPDO();
    $parts = [];
    $params = [':id' => $id];
    if ($titulo !== null) { $parts[] = "titulo = :titulo"; $params[':titulo'] = $titulo; }
    if ($completada !== null) { $parts[] = "completada = :completada"; $params[':completada'] = $completada; }

    if (empty($parts)) return obtenerTarea($id);

    $sql = "UPDATE public.tareas SET " . implode(', ', $parts) . " WHERE id = :id RETURNING id, titulo, completada, created_at";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

// DELETE
function eliminarTarea($id) {
    $pdo = getPDO();
    $sql = "DELETE FROM public.tareas WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}
