<?php
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
$path = __DIR__ . '/data/screens.json';
$screens = [];
if (file_exists($path)) {
  $json = json_decode(file_get_contents($path), true);
  if (is_array($json)) $screens = $json;
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Simulateur d'écran info gare</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="app-body">
  <main class="container">
    <header class="hero">
      <div>
        <p class="eyebrow">Simulateur web</p>
        <h1>Écrans info gare</h1>
        <p>Crée un écran de départs ou d'arrivées, sauvegarde-le en JSON et affiche-le en plein écran.</p>
      </div>
      <a class="btn primary" href="editor.php">Créer un écran</a>
    </header>

    <section class="panel">
      <div class="section-title">
        <h2>Écrans sauvegardés</h2>
        <span><?= count($screens) ?> écran(s)</span>
      </div>

      <?php if (!$screens): ?>
        <p class="muted">Aucun écran pour le moment.</p>
      <?php else: ?>
        <div class="cards">
          <?php foreach ($screens as $id => $screen): ?>
            <article class="card">
              <h3><?= h($screen['station'] ?? 'Gare sans nom') ?></h3>
              <p><?= h(($screen['mode'] ?? 'departures') === 'arrivals' ? 'Arrivées' : 'Départs') ?> · <?= h($screen['updatedAt'] ?? '') ?></p>
              <div class="actions">
                <a class="btn" href="editor.php?id=<?= h($id) ?>">Modifier</a>
                <a class="btn dark" href="screen.php?id=<?= h($id) ?>" target="_blank">Plein écran</a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
