<?php
declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
cors_headers();

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
  http_response_code(204);
  exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  json_error('Método não permitido', 405);
}

require_admin();

$cfg = app_config();
$dir = rtrim((string)$cfg['upload_dir'], '/\\');
if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
  json_error('Não foi possível criar pasta de uploads', 500);
}

if (empty($_FILES['image']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
  json_error('Envie o campo image', 400);
}

$file = $_FILES['image'];
if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
  json_error('Falha no upload', 400);
}

$max = (int)($cfg['max_upload_bytes'] ?? 2500000);
if ((int)$file['size'] > $max) {
  json_error('Arquivo muito grande', 400);
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file['tmp_name']) ?: '';
$allowed = [
  'image/jpeg' => 'jpg',
  'image/png' => 'png',
  'image/webp' => 'webp',
];
if (!isset($allowed[$mime])) {
  json_error('Use JPG, PNG ou WebP', 400);
}

$name = 'p_' . date('Ymd_His') . '_' . bin2hex(random_bytes(3)) . '.' . $allowed[$mime];
$dest = $dir . DIRECTORY_SEPARATOR . $name;
if (!move_uploaded_file($file['tmp_name'], $dest)) {
  json_error('Não foi possível salvar a foto', 500);
}

@chmod($dest, 0644);

$path = rtrim((string)$cfg['upload_url_path'], '/') . '/' . $name;
json_response([
  'ok' => true,
  'path' => $path,
  'blob' => true,
]);
