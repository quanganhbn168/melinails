<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$cats = App\Models\ProjectCategory::where('is_home', 1)->with(["projects" => function ($query) {
            $query->where("status", 1);
        }])->get();
$homeProjects = $cats->pluck('projects')->flatten();
echo "Count homeProjects: " . $homeProjects->count() . "\n";
foreach ($homeProjects as $p) {
    echo $p->name . "\n";
}
