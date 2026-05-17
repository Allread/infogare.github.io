<?php
require __DIR__ . '/storage.php';
send_json(['ok' => true, 'screens' => read_screens()]);
