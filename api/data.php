<?php
declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

cors_headers();
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
  http_response_code(204);
  exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
  if ($method === 'GET') {
    handle_get();
  }
  if ($method === 'POST') {
    handle_post();
  }
  json_error('Método não permitido', 405);
} catch (Throwable $e) {
  json_error('Erro interno', 500, $e->getMessage());
}

function handle_get(): void
{
  $full = isset($_GET['full']);
  if ($full) {
    require_admin();
    $data = load_store();
    // Não devolve senha em texto no GET se houver hash no MySQL
    if (isset($data['auth']) && is_array($data['auth'])) {
      $data['auth']['password'] = admin_password_header();
    }
    json_response($data);
  }

  // Público: catálogo sem pedidos/auth
  $data = load_store();
  json_response(public_catalog_from_store($data));
}

function handle_post(): void
{
  $body = read_json_body();

  // Full save do painel: { data: {...} }
  if (isset($body['data']) && is_array($body['data']) && !isset($body['action'])) {
    require_admin();
    $incoming = $body['data'];
    $current = load_store();

    // Preserva pedidos remotos que o painel local ainda não tem
    $localOrders = is_array($incoming['orders'] ?? null) ? $incoming['orders'] : [];
    $remoteOrders = is_array($current['orders'] ?? null) ? $current['orders'] : [];
    $byId = [];
    $byNum = [];
    foreach ($localOrders as $o) {
      if (!is_array($o)) continue;
      $id = (string)($o['id'] ?? '');
      $num = (string)($o['number'] ?? '');
      if ($id !== '') $byId[$id] = true;
      if ($num !== '') $byNum[$num] = true;
    }
    foreach ($remoteOrders as $ro) {
      if (!is_array($ro)) continue;
      $id = (string)($ro['id'] ?? '');
      $num = (string)($ro['number'] ?? '');
      if ($id !== '' && isset($byId[$id])) continue;
      if ($num !== '' && isset($byNum[$num])) continue;
      $localOrders[] = $ro;
      if ($id !== '') $byId[$id] = true;
      if ($num !== '') $byNum[$num] = true;
    }
    $incoming['orders'] = $localOrders;

    // Auth: se veio senha nova no blob, atualiza hash
    if (isset($incoming['auth']['password']) && $incoming['auth']['password'] !== '') {
      sync_admin_credentials(
        (string)($incoming['auth']['email'] ?? ''),
        (string)$incoming['auth']['password']
      );
    } else {
      // Mantém e-mail do MySQL / config
      $incoming['auth'] = $incoming['auth'] ?? [];
      $incoming['auth']['email'] = (string)($incoming['auth']['email'] ?? app_config()['admin_email']);
      $incoming['auth']['password'] = admin_password_header();
    }

    save_store($incoming);
    write_public_catalog($incoming);
    json_response(['ok' => true]);
  }

  $action = (string)($body['action'] ?? '');
  switch ($action) {
    case 'login':
      action_login($body);
      break;
    case 'save_settings':
      require_admin();
      action_save_settings($body);
      break;
    case 'save_inventory_item':
      require_admin();
      action_save_inventory_item($body);
      break;
    case 'delete_inventory_item':
      require_admin();
      action_delete_inventory_item($body);
      break;
    case 'save_catalog_order':
      require_admin();
      action_save_catalog_order($body);
      break;
    case 'publish_catalog':
      require_admin();
      action_publish_catalog();
      break;
    case 'set_product_active':
      require_admin();
      action_set_product_active($body);
      break;
    case 'create_order':
      action_create_order($body);
      break;
    case 'loyalty_status':
      action_loyalty_status($body);
      break;
    case 'order_status':
      action_order_status($body);
      break;
    case 'delete_order':
      require_admin();
      action_delete_order($body);
      break;
    default:
      json_error('Ação inválida', 400);
  }
}

function sync_admin_credentials(string $email, string $password): void
{
  $email = trim($email) !== '' ? trim($email) : (string)app_config()['admin_email'];
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = db()->prepare('INSERT INTO admin_users (id, email, password_hash) VALUES (1, :e, :h)
    ON DUPLICATE KEY UPDATE email = VALUES(email), password_hash = VALUES(password_hash)');
  $stmt->execute([':e' => $email, ':h' => $hash]);
}

function action_login(array $body): void
{
  $email = trim((string)($body['email'] ?? ''));
  $password = (string)($body['password'] ?? '');
  if ($email === '' || $password === '') {
    json_error('Informe e-mail e senha', 400);
  }
  if (!verify_admin_login($email, $password)) {
    json_error('E-mail ou senha inválidos', 401);
  }
  $data = load_store();
  $data['auth'] = [
    'email' => $email,
    'password' => $password, // o front atual espera isso na sessão
  ];
  json_response(['ok' => true, 'data' => $data]);
}

function action_save_settings(array $body): void
{
  $data = load_store();
  $patch = $body['settings'] ?? null;
  if (!is_array($patch)) json_error('settings inválido', 400);
  $data['settings'] = array_merge(is_array($data['settings']) ? $data['settings'] : [], $patch);
  save_store($data);
  write_public_catalog($data);
  json_response(['ok' => true]);
}

function action_save_inventory_item(array $body): void
{
  $item = $body['item'] ?? null;
  if (!is_array($item) || trim((string)($item['name'] ?? '')) === '') {
    json_error('Informe o nome do item.', 400);
  }
  $data = load_store();
  $list = is_array($data['inventoryItems']) ? $data['inventoryItems'] : [];
  if (empty($item['id'])) {
    $item['id'] = 'inv_' . bin2hex(random_bytes(4));
  }
  $id = (string)$item['id'];
  $found = false;
  foreach ($list as $i => $row) {
    if ((string)($row['id'] ?? '') === $id) {
      $list[$i] = array_merge($row, $item);
      $item = $list[$i];
      $found = true;
      break;
    }
  }
  if (!$found) $list[] = $item;
  $data['inventoryItems'] = $list;
  save_store($data);
  json_response(['ok' => true, 'item' => $item]);
}

function action_delete_inventory_item(array $body): void
{
  $id = (string)($body['id'] ?? '');
  if ($id === '') json_error('ID inválido', 400);
  $data = load_store();
  $data['inventoryItems'] = array_values(array_filter(
    $data['inventoryItems'] ?? [],
    static fn($row) => (string)($row['id'] ?? '') !== $id
  ));
  save_store($data);
  json_response(['ok' => true]);
}

function action_save_catalog_order(array $body): void
{
  $data = load_store();
  $catIds = is_array($body['categoryIds'] ?? null) ? $body['categoryIds'] : [];
  $prodIds = is_array($body['productIds'] ?? null) ? $body['productIds'] : [];

  $catMap = [];
  foreach ($catIds as $i => $id) $catMap[(string)$id] = (int)$i;
  $prodMap = [];
  foreach ($prodIds as $i => $id) $prodMap[(string)$id] = (int)$i;

  foreach ($data['categories'] as &$c) {
    $id = (string)($c['id'] ?? '');
    if (isset($catMap[$id])) $c['sortOrder'] = $catMap[$id];
  }
  unset($c);
  foreach ($data['products'] as &$p) {
    $id = (string)($p['id'] ?? '');
    if (isset($prodMap[$id])) $p['sortOrder'] = $prodMap[$id];
  }
  unset($p);

  save_store($data);
  $published = write_public_catalog($data);
  json_response(['ok' => true, 'catalog' => $published]);
}

function action_publish_catalog(): void
{
  $data = load_store();
  $ok = write_public_catalog($data);
  if (!$ok) json_error('Não foi possível gravar catalog.json (permissão de escrita?)', 500);
  json_response(['ok' => true]);
}

function action_set_product_active(array $body): void
{
  $id = (string)($body['id'] ?? '');
  if ($id === '') json_error('Produto inválido', 400);
  $active = !empty($body['active']);
  $data = load_store();
  $found = false;
  foreach ($data['products'] as &$p) {
    if ((string)($p['id'] ?? '') === $id) {
      $p['active'] = $active;
      $found = true;
      break;
    }
  }
  unset($p);
  if (!$found) json_error('Produto não encontrado', 404);
  save_store($data);
  write_public_catalog($data);
  json_response(['ok' => true]);
}

function action_create_order(array $body): void
{
  $order = $body['order'] ?? null;
  $client = $body['client'] ?? null;
  if (!is_array($order) || !is_array($client)) {
    json_error('Pedido inválido', 400);
  }

  $data = load_store();
  $orders = is_array($data['orders']) ? $data['orders'] : [];
  $clients = is_array($data['clients']) ? $data['clients'] : [];

  $phone = only_digits((string)($order['clientWhatsapp'] ?? $client['phone'] ?? ''));
  $orderId = (string)($order['id'] ?? '');
  $notes = (string)($order['notes'] ?? '');

  // Anti-duplicata recente (90s)
  $now = time();
  foreach ($orders as $existing) {
    if (!is_array($existing)) continue;
    if (only_digits((string)($existing['clientWhatsapp'] ?? '')) !== $phone) continue;
    $ts = strtotime((string)($existing['date'] ?? '')) ?: 0;
    if ($now - $ts > 90) continue;
    if ((string)($existing['notes'] ?? '') !== $notes) continue;
    json_response([
      'ok' => true,
      'duplicated' => true,
      'orderId' => $existing['id'] ?? '',
      'orderNumber' => $existing['number'] ?? '',
      'status' => $existing['status'] ?? 'novo',
      'loyalty' => compute_loyalty($orders, $phone),
    ]);
  }

  if ($orderId !== '') {
    foreach ($orders as $existing) {
      if ((string)($existing['id'] ?? '') === $orderId) {
        json_response([
          'ok' => true,
          'duplicated' => true,
          'orderId' => $existing['id'],
          'orderNumber' => $existing['number'] ?? '',
          'status' => $existing['status'] ?? 'novo',
          'loyalty' => compute_loyalty($orders, $phone),
        ]);
      }
    }
  }

  // Upsert cliente
  $clientId = (string)($client['id'] ?? '');
  $foundClient = false;
  foreach ($clients as &$c) {
    if (only_digits((string)($c['phone'] ?? '')) === $phone && $phone !== '') {
      $c['name'] = (string)($client['name'] ?? $c['name'] ?? '');
      $c['phone'] = $phone;
      if (!empty($client['address'])) $c['address'] = $client['address'];
      $clientId = (string)$c['id'];
      $foundClient = true;
      break;
    }
  }
  unset($c);
  if (!$foundClient) {
    if ($clientId === '') $clientId = 'c' . bin2hex(random_bytes(5));
    $clients[] = [
      'id' => $clientId,
      'name' => (string)($client['name'] ?? ''),
      'email' => (string)($client['email'] ?? ''),
      'phone' => $phone,
      'address' => (string)($client['address'] ?? ''),
    ];
  }

  if (empty($order['number'])) {
    $order['number'] = next_order_number($orders);
  }
  if ($orderId === '') {
    $order['id'] = 'o' . bin2hex(random_bytes(5));
  }
  $order['clientId'] = $clientId;
  $order['clientWhatsapp'] = $phone;
  $order['status'] = $order['status'] ?? 'novo';
  $order['date'] = $order['date'] ?? gmdate('c');
  $order['source'] = $order['source'] ?? 'site';

  $orders[] = $order;
  $data['orders'] = $orders;
  $data['clients'] = $clients;
  save_store($data);

  json_response([
    'ok' => true,
    'orderId' => $order['id'],
    'orderNumber' => $order['number'],
    'status' => $order['status'],
    'loyalty' => compute_loyalty($orders, $phone),
  ]);
}

function action_loyalty_status(array $body): void
{
  $phone = only_digits((string)($body['phone'] ?? ''));
  if ($phone === '') json_error('Informe o WhatsApp', 400);
  $data = load_store();
  json_response([
    'ok' => true,
    'loyalty' => compute_loyalty($data['orders'] ?? [], $phone),
  ]);
}

function action_order_status(array $body): void
{
  $phone = only_digits((string)($body['phone'] ?? ''));
  $orderNumber = trim((string)($body['orderNumber'] ?? ''));
  if ($phone === '') json_error('Informe o WhatsApp do pedido.', 400);

  $data = load_store();
  $list = [];
  foreach ($data['orders'] ?? [] as $o) {
    if (!is_array($o)) continue;
    if (only_digits((string)($o['clientWhatsapp'] ?? '')) === $phone) $list[] = $o;
  }

  $order = null;
  if ($orderNumber !== '') {
    foreach ($list as $o) {
      if ((string)($o['number'] ?? '') === $orderNumber) {
        $order = $o;
        break;
      }
    }
  } else {
    foreach ($list as $o) {
      $st = (string)($o['status'] ?? '');
      if (!in_array($st, ['finalizado', 'cancelado'], true)) {
        $order = $o;
        break;
      }
    }
    if (!$order && $list) $order = $list[count($list) - 1];
  }

  if (!$order) json_error('Pedido não encontrado.', 404);

  json_response([
    'ok' => true,
    'order' => [
      'orderNumber' => $order['number'] ?? '',
      'status' => $order['status'] ?? 'novo',
      'total' => (float)($order['total'] ?? 0),
      'orderedAt' => $order['date'] ?? '',
    ],
  ]);
}

function action_delete_order(array $body): void
{
  $id = (string)($body['id'] ?? '');
  if ($id === '') json_error('ID inválido', 400);
  $data = load_store();
  $before = count($data['orders'] ?? []);
  $data['orders'] = array_values(array_filter(
    $data['orders'] ?? [],
    static fn($o) => (string)($o['id'] ?? '') !== $id
  ));
  if (count($data['orders']) === $before) {
    json_error('Pedido não encontrado', 404);
  }
  save_store($data);
  json_response(['ok' => true]);
}
