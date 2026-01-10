<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \DB::select('select * from users where email = ?', ['test@example.com']);
echo "USERS:\n";
echo json_encode($user, JSON_PRETTY_PRINT) . "\n\n";

$clients = \DB::select('select * from clients where user_id = (select id from users where email = ?)', ['test@example.com']);
echo "CLIENTS:\n";
echo json_encode($clients, JSON_PRETTY_PRINT) . "\n";
