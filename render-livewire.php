<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$html = Illuminate\Support\Facades\Blade::render('<x-ui.modal title="Test" maxWidth="2xl">hello</x-ui.modal>');
file_put_contents('/tmp/modal-test.html', $html);
echo "Done\n";
