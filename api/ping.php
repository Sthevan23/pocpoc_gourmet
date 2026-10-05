<?php
declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
cors_headers();

try {
  db()->query('SELECT 1');
  json_response([
    'ok' => true,
    'service' => 'pocpoc-api',
    'time' => gmdate('c'),
  ]);
} catch (Throwable $e) {
  json_error('API offline', 503, $e->getMessage());
}
