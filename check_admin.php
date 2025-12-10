<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$admin = App\Models\Admin::where('email', 'quanganhbn168@gmail.com')->first();

if ($admin) {
    echo "ID: " . $admin->id . "\n";
    echo "Name: " . $admin->name . "\n";
    echo "Roles: " . $admin->getRoleNames()->implode(', ') . "\n";
    echo "Layout: " . $admin->layout . "\n";
} else {
    echo "Admin not found\n";
}
