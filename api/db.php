<?php
// db.php - conexión a Postgres (Supabase) usando PDO
// Se espera la variable de entorno DATABASE_URL o variables separadas DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS

function getPDO() {
    $databaseUrl = getenv('DATABASE_URL');

    if ($databaseUrl) {
        // DATABASE_URL formato: postgres://user:pass@host:port/dbname
        $parts = parse_url($databaseUrl);
        $host = $parts['host'];
        $port = $parts['port'] ?? 5432;
        $user = $parts['user'];
        $pass = $parts['pass'];
        $dbname = ltrim($parts['path'], '/');
    } else {
        // alternativa: leer variables separadas
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT') ?: 5432;
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');
        $dbname = getenv('DB_NAME');
    }

    if (!$host || !$user || !$dbname) {
        http_response_code(500);
        echo json_encode(['error' => 'Database configuration missing']);
        exit;
    }

    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};";
    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'DB connection failed: '.$e->getMessage()]);
        exit;
    }
}

