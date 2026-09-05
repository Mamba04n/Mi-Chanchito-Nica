<?php

$files = [
    'app/Livewire/Education/Home.php',
    'app/Livewire/Education/Explore.php',
    'app/Livewire/Education/MyLearning.php',
    'app/Livewire/Education/ProgramDetail.php',
    'app/Livewire/Education/LessonViewer.php',
    'app/Livewire/Education/AssessmentViewer.php',
    'app/Livewire/Gamification/Dashboard.php',
];

foreach ($files as $file) {
    $path = "c:/Users/Mamba/Desktop/Chanchito Nica/" . $file;
    $content = file_get_contents($path);
    if (strpos($content, "->layout('layouts.app')") === false) {
        $content = preg_replace("/return view\(([^)]+)\);/", "return view($1)->layout('layouts.app');", $content);
        file_put_contents($path, $content);
        echo "Updated $file\n";
    }
}
