<?php
function data_file(): string {
  return __DIR__ . '/../data/screens.json';
}

function read_screens(): array {
  $file = data_file();
  if (!file_exists($file)) return [];
  $raw = file_get_contents($file);
  $json = json_decode($raw, true);
  return is_array($json) ? $json : [];
}

function write_screens(array $screens): bool {
  $file = data_file();
  $dir = dirname($file);
  if (!is_dir($dir)) mkdir($dir, 0775, true);
  $json = json_encode($screens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  return file_put_contents($file, $json, LOCK_EX) !== false;
}

function clean_id(?string $id): string {
  return preg_replace('/[^a-zA-Z0-9_-]/', '', (string)$id);
}

function new_id(): string {
  return bin2hex(random_bytes(5));
}

function send_json($data, int $status = 200): void {
  http_response_code($status);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  exit;
}
