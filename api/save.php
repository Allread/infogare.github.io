<?php
require __DIR__ . '/storage.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') send_json(['ok' => false, 'error' => 'POST requis'], 405);
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) send_json(['ok' => false, 'error' => 'JSON invalide'], 400);

$id = clean_id($input['id'] ?? '');
$screen = $input['screen'] ?? null;
if (!is_array($screen)) send_json(['ok' => false, 'error' => 'Écran manquant'], 400);

$station = trim((string)($screen['station'] ?? ''));
if ($station === '') send_json(['ok' => false, 'error' => 'Nom de gare obligatoire'], 400);

$allowedModes = ['departures', 'arrivals'];
$allowedThemes = ['blue', 'green', 'dark'];
$screen['station'] = mb_substr($station, 0, 80);
$screen['mode'] = in_array($screen['mode'] ?? '', $allowedModes, true) ? $screen['mode'] : 'departures';
$screen['theme'] = in_array($screen['theme'] ?? '', $allowedThemes, true) ? $screen['theme'] : 'blue';
$screen['ticker'] = mb_substr(trim((string)($screen['ticker'] ?? '')), 0, 180);
$screen['trains'] = array_values(array_slice(is_array($screen['trains'] ?? null) ? $screen['trains'] : [], 0, 14));
foreach ($screen['trains'] as &$t) {
  $t['time'] = mb_substr(trim((string)($t['time'] ?? '')), 0, 5);
  $t['number'] = mb_substr(trim((string)($t['number'] ?? '')), 0, 16);
  $t['type'] = mb_substr(trim((string)($t['type'] ?? 'TRAIN')), 0, 18);
  $t['destination'] = mb_substr(trim((string)($t['destination'] ?? '')), 0, 80);
  $t['via'] = mb_substr(trim((string)($t['via'] ?? '')), 0, 160);
  $t['platform'] = mb_substr(trim((string)($t['platform'] ?? '')), 0, 8);
  $t['status'] = mb_substr(trim((string)($t['status'] ?? 'À l’heure')), 0, 40);
}
unset($t);
$screen['updatedAt'] = date('Y-m-d H:i:s');

$screens = read_screens();
if ($id === '') $id = new_id();
$screens[$id] = $screen;
if (!write_screens($screens)) send_json(['ok' => false, 'error' => 'Impossible de sauvegarder'], 500);
send_json(['ok' => true, 'id' => $id, 'screen' => $screen]);
