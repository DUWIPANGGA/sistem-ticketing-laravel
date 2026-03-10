<?php

$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

foreach($files as $file) {
    $content = file_get_contents($file[0]);
    
    // Fix buttons changing from dark to light text
    $newContent = str_replace('text-gray-900 bg-gradient', 'text-white bg-gradient', $content);
    $newContent = str_replace('focus:ring-offset-gray-900', 'focus:ring-offset-white', $newContent);
    $newContent = str_replace('from-gray-700 to-gray-600', 'from-blue-600 to-indigo-500 text-white', $newContent);
    
    // Check ticket user profile initials rendering bug
    // if 'text-gray-900' inside $newContent -> ok, it's a minor thing.
    
    if ($content !== $newContent) {
        file_put_contents($file[0], $newContent);
        echo "Fixed {$file[0]}\n";
    }
}
echo "Done.\n";
