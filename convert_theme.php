<?php

// script to convert dark mode tailwind classes to light mode
$replacements = [
    'class="dark"' => '',
    'bg-gray-900' => 'bg-gray-50',
    'bg-gray-800' => 'bg-white',
    'border-gray-700' => 'border-gray-200',
    'border-gray-600' => 'border-gray-300',
    'text-gray-100' => 'text-gray-900',
    'text-gray-200' => 'text-gray-800',
    'text-gray-300' => 'text-gray-700',
    'text-gray-400' => 'text-gray-500',
    'text-white' => 'text-slate-900', // Need to be careful here, maybe just text-gray-900
    'text-indigo-400' => 'text-indigo-600',
    'text-blue-400' => 'text-blue-600',
    'text-red-400' => 'text-red-600',
    'text-green-400' => 'text-green-600',
    'text-yellow-400' => 'text-yellow-600',
    'hover:bg-gray-700' => 'hover:bg-gray-100',
    'hover:bg-gray-800' => 'hover:bg-gray-50',
    'prose-invert' => '',
    'bg-white/10' => 'bg-gray-100/50',
    'shadow-lg shadow-blue-500/20' => 'shadow-md shadow-blue-500/20',
];

$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

foreach($files as $file) {
    if (str_contains($file[0], 'welcome.blade.php') || str_contains($file[0], 'auth')) continue;

    $content = file_get_contents($file[0]);

    // Handle min-h-screen -> h-screen for app layout scrolling
    if (str_contains($file[0], 'app.blade.php')) {
        $content = str_replace('min-h-screen', 'h-screen', $content);
    }

    foreach ($replacements as $search => $replace) {
        // Need to be cautious with text-white, sometimes we want white text on buttons
        if ($search === 'text-white') {
            // Replace text-white unless it's inside a button, badge or gradient
            $content = preg_replace_callback('/(class="[^"]*)(?<!\w)text-white(?!\w)([^"]*")/is', function($matches) {
                // If the class contains bg-gradient, bg-blue, bg-red, etc, don't replace
                if (preg_match('/bg-(gradient|blue|indigo|red|green|yellow|purple)-\d+/', $matches[1] . $matches[2]) || 
                    preg_match('/bg-[a-z]+-500/', $matches[1] . $matches[2])) {
                    return $matches[1] . 'text-white' . $matches[2];
                }
                if (str_contains($matches[1].$matches[2], 'text-transparent')) {
                    return $matches[1] . 'text-white' . $matches[2]; // prevent replacing white text inside gradient clip
                }
                return $matches[1] . 'text-gray-900' . $matches[2];
            }, $content);
        } else {
            $content = str_replace($search, $replace, $content);
        }
    }
    
    file_put_contents($file[0], $content);
    echo "Converted {$file[0]}\n";
}
echo "Done.\n";
