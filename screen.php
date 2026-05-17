<?php
$id = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['id'] ?? '');
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Écran info gare</title>
  <link rel="stylesheet" href="assets/css/style.css?v=2.2">
</head>
<body class="screen-body">
  <div id="screen" class="station-screen loading">Chargement...</div>
  <script>window.SCREEN_ID = <?= json_encode($id) ?>;</script>
  <script src="assets/js/screen.js?v=2.2"></script>
</body>
</html>
