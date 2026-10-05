<?php
declare(strict_types=1);

/**
 * Rode UMA VEZ após importar schema.sql e configurar config.php.
 * Depois APAGUE este arquivo do servidor.
 */

require __DIR__ . '/bootstrap.php';
cors_headers();
header('Content-Type: text/html; charset=utf-8');

$cfg = app_config();
$messages = [];

try {
  db()->query('SELECT 1');
  $messages[] = 'Conexão MySQL OK.';
} catch (Throwable $e) {
  echo '<h1>Erro MySQL</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>';
  echo '<p>Confira db_host / db_name / db_user / db_pass em api/config.php</p>';
  exit;
}

$email = trim((string)($cfg['admin_email'] ?? 'admin@pocpocgourmet.com.br'));
$password = (string)($cfg['admin_password'] ?? 'pocpoc123');
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = db()->prepare('INSERT INTO admin_users (id, email, password_hash) VALUES (1, :e, :h)
  ON DUPLICATE KEY UPDATE email = VALUES(email), password_hash = VALUES(password_hash)');
$stmt->execute([':e' => $email, ':h' => $hash]);
$messages[] = 'Admin criado: ' . htmlspecialchars($email) . ' / senha do config.php';

$seedPath = __DIR__ . '/seed-data.json';
if (!is_file($seedPath)) {
  echo '<h1>seed-data.json não encontrado</h1>';
  exit;
}

$seed = json_decode((string)file_get_contents($seedPath), true);
if (!is_array($seed) || empty($seed['products'])) {
  echo '<h1>seed-data.json inválido</h1>';
  exit;
}

$seed['auth'] = [
  'email' => $email,
  'password' => $password,
];
$seed['orders'] = $seed['orders'] ?? [];
$seed['clients'] = $seed['clients'] ?? [];
$seed['inventoryItems'] = $seed['inventoryItems'] ?? [];
$seed['coupons'] = $seed['coupons'] ?? [];
$seed['finance'] = $seed['finance'] ?? [];

save_store($seed);
$messages[] = 'Catálogo inicial gravado (' . count($seed['products']) . ' produtos).';

$published = write_public_catalog($seed);
$messages[] = $published
  ? 'catalog.json e catalog.live.json publicados na raiz do site.'
  : 'Aviso: não deu para gravar catalog.json (ajuste permissão da pasta raiz).';

$uploadDir = rtrim((string)$cfg['upload_dir'], '/\\');
if (!is_dir($uploadDir)) {
  @mkdir($uploadDir, 0755, true);
}
$messages[] = is_dir($uploadDir) ? 'Pasta uploads OK.' : 'Aviso: não criou pasta uploads.';

echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="utf-8"><title>Install Poc Poc</title>';
echo '<style>body{font-family:system-ui;max-width:640px;margin:2rem auto;padding:0 1rem;line-height:1.5}';
echo 'li{margin:.4rem 0}.ok{color:#0a7} .warn{color:#b45309}</style></head><body>';
echo '<h1>Instalação concluída</h1><ul>';
foreach ($messages as $m) {
  echo '<li class="ok">' . htmlspecialchars($m) . '</li>';
}
echo '</ul>';
echo '<p><strong>Próximos passos:</strong></p><ol>';
echo '<li>Abra o painel: <code>/admin/login.html</code></li>';
echo '<li>Login: <code>' . htmlspecialchars($email) . '</code> / senha do config</li>';
echo '<li><strong>APAGUE</strong> o arquivo <code>api/install.php</code> do servidor</li>';
echo '<li>Troque a senha depois (ou no config + install de novo)</li>';
echo '</ol>';
echo '<p class="warn">Deixe este arquivo no ar só durante a instalação.</p>';
echo '</body></html>';
