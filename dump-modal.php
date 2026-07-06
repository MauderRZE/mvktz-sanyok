<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Fake login
$user = \App\Models\User::first();
if ($user) {
    auth()->login($user);
}

$request = Illuminate\Http\Request::create('/admin/assets', 'GET');
app()->instance('request', $request);

$html = \Livewire\Livewire::mount('admin.asset-manager', ['isOpen' => true]);
file_put_contents('/tmp/dump.html', $html);
echo "Done\n";
