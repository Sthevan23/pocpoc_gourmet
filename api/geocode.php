<?php
declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
cors_headers();

$q = trim((string)($_GET['q'] ?? ''));
$lat = (float)($_GET['lat'] ?? 0);
$lng = (float)($_GET['lng'] ?? 0);

if ($q === '') {
  json_error('Informe q', 400);
}

$url = 'https://photon.komoot.io/api/?q=' . rawurlencode($q) . '&limit=5&lang=pt';
if ($lat && $lng) {
  $url .= '&lat=' . rawurlencode((string)$lat) . '&lon=' . rawurlencode((string)$lng);
}

$ctx = stream_context_create([
  'http' => [
    'timeout' => 8,
    'header' => "User-Agent: PocPocGourmet/1.0\r\nAccept: application/json\r\n",
  ],
]);

$raw = @file_get_contents($url, false, $ctx);
if ($raw === false) {
  json_error('Geocode indisponível', 503);
}

$data = json_decode($raw, true);
if (!is_array($data)) {
  json_error('Resposta inválida', 502);
}

json_response([
  'ok' => true,
  'features' => $data['features'] ?? [],
]);
