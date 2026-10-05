<?php
declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$name = basename((string)($_GET['f'] ?? ''));
if ($name === '' || $name === '.' || $name === '..') {
  http_response_code(400);
  header('Content-Type: text/plain; charset=utf-8');
  echo 'Arquivo inválido';
  exit;
}

$cfg = app_config();
$candidates = [
  rtrim((string)$cfg['upload_dir'], '/\\') . DIRECTORY_SEPARATOR . $name,
  dirname(__DIR__) . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . $name,
  dirname(__DIR__) . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $name,
];

$file = null;
foreach ($candidates as $path) {
  if (is_file($path)) {
    $file = $path;
    break;
  }
}

if ($file === null) {
  http_response_code(404);
  header('Content-Type: text/plain; charset=utf-8');
  echo 'Não encontrado';
  exit;
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file) ?: 'application/octet-stream';
header('Content-Type: ' . $mime);
header('Cache-Control: public, max-age=86400');
header('Content-Length: ' . (string)filesize($file));
readfile($file);
exit;
