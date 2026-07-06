<?php
$files = glob(__DIR__ . '/../storage/framework/views/*.php');
foreach ($files as $file) {
    @unlink($file);
}
$log = __DIR__ . '/../storage/logs/laravel.log';
@unlink($log);
echo "Cleared";
