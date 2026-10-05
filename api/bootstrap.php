<?php
declare(strict_types=1);

function app_config(): array
{
  static $cfg = null;
  if ($cfg !== null) return $cfg;
  $path = __DIR__ . '/config.php';
  $sample = __DIR__ . '/config.sample.php';
  if (!is_file($path) && is_file($sample)) {
    $path = $sample;
  }
  if (!is_file($path)) {
    json_error('Configure api/config.php (copie de config.sample.php e preencha o MySQL)', 500);
  }
  $cfg = require $path;
  return $cfg;
}

function db(): PDO
{
  static $pdo = null;
  if ($pdo instanceof PDO) return $pdo;
  $c = app_config();
  $dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=%s',
    $c['db_host'],
    $c['db_name'],
    $c['db_charset'] ?? 'utf8mb4'
  );
  try {
    $pdo = new PDO($dsn, $c['db_user'], $c['db_pass'], [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]);
  } catch (Throwable $e) {
    json_error('Falha na conexão MySQL', 500, $e->getMessage());
  }
  return $pdo;
}

function cors_headers(): void
{
  $origin = app_config()['cors_origin'] ?? '*';
  header('Access-Control-Allow-Origin: ' . $origin);
  header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
  header('Access-Control-Allow-Headers: Content-Type, X-Admin-Password');
  header('Access-Control-Max-Age: 86400');
}

function json_response(array $payload, int $status = 200): void
{
  http_response_code($status);
  header('Content-Type: application/json; charset=utf-8');
  header('Cache-Control: no-store');
  echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  exit;
}

function json_error(string $error, int $status = 400, string $detail = ''): void
{
  $out = ['ok' => false, 'error' => $error];
  if ($detail !== '') $out['detail'] = $detail;
  json_response($out, $status);
}

function read_json_body(): array
{
  $raw = file_get_contents('php://input');
  if ($raw === false || trim($raw) === '') return [];
  $data = json_decode($raw, true);
  if (!is_array($data)) json_error('JSON inválido', 400);
  return $data;
}

function admin_password_header(): string
{
  $hdr = $_SERVER['HTTP_X_ADMIN_PASSWORD'] ?? '';
  return is_string($hdr) ? $hdr : '';
}

function require_admin(): void
{
  $password = admin_password_header();
  if ($password === '' || !verify_admin_password($password)) {
    json_error('Não autorizado', 401);
  }
}

function verify_admin_password(string $password): bool
{
  try {
    $stmt = db()->query('SELECT password_hash FROM admin_users WHERE id = 1 LIMIT 1');
    $row = $stmt->fetch();
    if ($row && !empty($row['password_hash'])) {
      if (password_verify($password, $row['password_hash'])) return true;
    }
  } catch (Throwable $e) {
    // segue para fallback do blob
  }

  $store = load_store();
  $plain = (string)($store['auth']['password'] ?? '');
  return $plain !== '' && hash_equals($plain, $password);
}

function verify_admin_login(string $email, string $password): bool
{
  $email = trim($email);
  try {
    $stmt = db()->prepare('SELECT email, password_hash FROM admin_users WHERE id = 1 LIMIT 1');
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row) {
      $okEmail = strcasecmp((string)$row['email'], $email) === 0;
      $okPass = password_verify($password, (string)$row['password_hash']);
      if ($okEmail && $okPass) return true;
    }
  } catch (Throwable $e) {
    // fallback
  }

  $store = load_store();
  $authEmail = trim((string)($store['auth']['email'] ?? ''));
  $authPass = (string)($store['auth']['password'] ?? '');
  return $authEmail !== '' && $authPass !== ''
    && strcasecmp($authEmail, $email) === 0
    && hash_equals($authPass, $password);
}

function empty_store(): array
{
  return [
    'version' => 2,
    'settings' => [],
    'auth' => ['email' => '', 'password' => ''],
    'categories' => [],
    'products' => [],
    'orders' => [],
    'clients' => [],
    'inventoryItems' => [],
    'coupons' => [],
    'finance' => [],
    'reviews' => [],
    'faq' => [],
    'gallery' => [],
  ];
}

function load_store(): array
{
  $stmt = db()->query('SELECT payload FROM store_state WHERE id = 1 LIMIT 1');
  $row = $stmt->fetch();
  if (!$row || empty($row['payload'])) {
    return empty_store();
  }
  $data = json_decode((string)$row['payload'], true);
  if (!is_array($data)) return empty_store();
  foreach (empty_store() as $k => $v) {
    if (!array_key_exists($k, $data)) $data[$k] = $v;
  }
  if (!is_array($data['products'])) $data['products'] = [];
  if (!is_array($data['categories'])) $data['categories'] = [];
  if (!is_array($data['orders'])) $data['orders'] = [];
  if (!is_array($data['clients'])) $data['clients'] = [];
  if (!is_array($data['inventoryItems'])) $data['inventoryItems'] = [];
  if (!is_array($data['coupons'])) $data['coupons'] = [];
  if (!is_array($data['settings'])) $data['settings'] = [];
  if (!is_array($data['auth'])) $data['auth'] = ['email' => '', 'password' => ''];
  return $data;
}

function save_store(array $data): void
{
  $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  if ($json === false) json_error('Falha ao serializar dados', 500);
  $stmt = db()->prepare('INSERT INTO store_state (id, payload) VALUES (1, :p)
    ON DUPLICATE KEY UPDATE payload = VALUES(payload)');
  $stmt->execute([':p' => $json]);
}

function public_catalog_from_store(array $data): array
{
  $products = array_values(array_filter(
    $data['products'] ?? [],
    static fn($p) => is_array($p) && ($p['active'] ?? true) !== false
  ));
  return [
    'version' => (int)($data['version'] ?? 2),
    'generatedAt' => gmdate('c'),
    'settings' => $data['settings'] ?? [],
    'categories' => $data['categories'] ?? [],
    'products' => $products,
    'gallery' => $data['gallery'] ?? [],
    'faq' => $data['faq'] ?? [],
    'reviews' => $data['reviews'] ?? [],
  ];
}

function write_public_catalog(array $data): bool
{
  $root = dirname(__DIR__);
  $catalog = public_catalog_from_store($data);
  $json = json_encode($catalog, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
  if ($json === false) return false;
  $ok1 = @file_put_contents($root . '/catalog.live.json', $json) !== false;
  $ok2 = @file_put_contents($root . '/catalog.json', $json) !== false;
  return $ok1 || $ok2;
}

function next_order_number(array $orders): string
{
  $year = (int)date('Y');
  $max = 0;
  foreach ($orders as $order) {
    if (!is_array($order)) continue;
    if (preg_match('/PED-(\d{4})-(\d+)/i', (string)($order['number'] ?? ''), $m)) {
      if ((int)$m[1] === $year) $max = max($max, (int)$m[2]);
    }
  }
  return sprintf('PED-%d-%03d', $year, $max + 1);
}

function only_digits(string $value): string
{
  return preg_replace('/\D+/', '', $value) ?? '';
}

function compute_loyalty(array $orders, string $phone): array
{
  $digits = only_digits($phone);
  $count = 0;
  foreach ($orders as $o) {
    if (!is_array($o)) continue;
    if (($o['status'] ?? '') !== 'finalizado') continue;
    if (only_digits((string)($o['clientWhatsapp'] ?? '')) !== $digits) continue;
    $count++;
  }
  $goal = 10;
  $progress = $count % $goal;
  return [
    'phone' => $digits,
    'finishedOrders' => $count,
    'goal' => $goal,
    'progress' => $progress,
    'remaining' => max(0, $goal - $progress),
    'rewardReady' => $progress === 0 && $count > 0,
  ];
}
