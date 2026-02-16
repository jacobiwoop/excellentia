<?php
$path = 'uploads/cours/1771248551_page de garde (1).pdf';
$exists = App\Models\Cours::where('fichier_path', $path)->exists();
dump($exists);
