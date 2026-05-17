<?php
require __DIR__ . '/storage.php';
$id = clean_id($_GET['id'] ?? '');
$screens = read_screens();
if ($id === '' || !isset($screens[$id])) {
  send_json(['ok' => false, 'error' => 'Écran introuvable'], 404);
}
send_json(['ok' => true, 'id' => $id, 'screen' => $screens[$id]]);
