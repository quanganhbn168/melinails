<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$cats = App\Models\ProjectCategory::where('is_home', 1)->withCount('projects')->get(); 
foreach($cats as $cat) { 
    echo $cat->name . ' - ' . $cat->projects_count . "\n"; 
}
$allProjects = App\Models\Project::count();
echo "Total projects: " . $allProjects . "\n";
