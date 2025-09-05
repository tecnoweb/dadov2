<?php
/**
 * xCrudRevolution - Fix each() Deprecated Function
 * Converte automaticamente tutte le chiamate each() in foreach
 */

echo "🔧 xCrudRevolution - each() Fixer Tool\n";
echo "=====================================\n\n";

$files_to_fix = [
    '../xcrud.php',
    '../core/xss.php'
];

$total_fixes = 0;

foreach ($files_to_fix as $file) {
    if (!file_exists($file)) {
        echo "❌ File not found: $file\n";
        continue;
    }
    
    echo "📁 Processing: $file\n";
    
    $content = file_get_contents($file);
    $original = $content;
    $fixes_in_file = 0;
    
    // Pattern per trovare while + each
    // Esempio: while (list($k, $v) = each($array))
    $pattern1 = '/while\s*\(\s*list\s*\(([^,]+),\s*([^)]+)\)\s*=\s*each\s*\(([^)]+)\)\s*\)/';
    $content = preg_replace_callback($pattern1, function($matches) use (&$fixes_in_file) {
        $key_var = trim($matches[1]);
        $val_var = trim($matches[2]);
        $array_var = trim($matches[3]);
        $fixes_in_file++;
        return "foreach ($array_var as $key_var => $val_var)";
    }, $content);
    
    // Pattern per while con solo value
    // Esempio: while (list(, $v) = each($array))
    $pattern2 = '/while\s*\(\s*list\s*\(,\s*([^)]+)\)\s*=\s*each\s*\(([^)]+)\)\s*\)/';
    $content = preg_replace_callback($pattern2, function($matches) use (&$fixes_in_file) {
        $val_var = trim($matches[1]);
        $array_var = trim($matches[2]);
        $fixes_in_file++;
        return "foreach ($array_var as $val_var)";
    }, $content);
    
    // Pattern per while con solo key
    // Esempio: while (list($k) = each($array))
    $pattern3 = '/while\s*\(\s*list\s*\(([^,)]+)\)\s*=\s*each\s*\(([^)]+)\)\s*\)/';
    $content = preg_replace_callback($pattern3, function($matches) use (&$fixes_in_file) {
        $key_var = trim($matches[1]);
        $array_var = trim($matches[2]);
        $fixes_in_file++;
        return "foreach ($array_var as $key_var => \$_xcrud_unused_value)";
    }, $content);
    
    // Pattern per each() semplice in assegnazioni
    // Esempio: $item = each($array)
    $pattern4 = '/(\$[a-zA-Z_][a-zA-Z0-9_]*)\s*=\s*each\s*\(([^)]+)\)/';
    $content = preg_replace_callback($pattern4, function($matches) use (&$fixes_in_file) {
        $var = trim($matches[1]);
        $array_var = trim($matches[2]);
        $fixes_in_file++;
        // each() ritorna array con [0=>key, 1=>value, 'key'=>key, 'value'=>value]
        // Dobbiamo simularlo con current() e key()
        return "$var = (current($array_var) !== false ? array(0 => key($array_var), 1 => current($array_var), 'key' => key($array_var), 'value' => current($array_var)) : false); next($array_var)";
    }, $content);
    
    // Pattern per each() in if statements
    // Esempio: if(each($array))
    $pattern5 = '/if\s*\(\s*each\s*\(([^)]+)\)\s*\)/';
    $content = preg_replace_callback($pattern5, function($matches) use (&$fixes_in_file) {
        $array_var = trim($matches[1]);
        $fixes_in_file++;
        return "if (!empty($array_var))";
    }, $content);
    
    // Pattern per reset + each combo tipico
    // Esempio: reset($arr); while(list($k,$v) = each($arr))
    $pattern6 = '/reset\s*\(([^)]+)\)\s*;\s*while\s*\(\s*list\s*\(([^,]+),\s*([^)]+)\)\s*=\s*each\s*\(([^)]+)\)\s*\)/';
    $content = preg_replace_callback($pattern6, function($matches) use (&$fixes_in_file) {
        $reset_var = trim($matches[1]);
        $key_var = trim($matches[2]);
        $val_var = trim($matches[3]);
        $array_var = trim($matches[4]);
        // Se le variabili coincidono, è un pattern tipico
        if ($reset_var === $array_var) {
            $fixes_in_file++;
            return "foreach ($array_var as $key_var => $val_var)";
        }
        return $matches[0]; // Non toccare se non siamo sicuri
    }, $content);
    
    // Salva solo se ci sono stati cambiamenti
    if ($content !== $original) {
        // Backup del file originale
        $backup_file = $file . '.backup_each_' . date('YmdHis');
        file_put_contents($backup_file, $original);
        echo "  📦 Backup saved to: $backup_file\n";
        
        // Salva il file modificato
        file_put_contents($file, $content);
        echo "  ✅ Fixed $fixes_in_file occurrences of each()\n";
        
        $total_fixes += $fixes_in_file;
    } else {
        echo "  ℹ️  No each() functions found or already fixed\n";
    }
    
    echo "\n";
}

echo "🎉 TOTALE: $total_fixes fix applicati!\n";

// Verifica se ci sono ancora each() residui
echo "\n📊 Verifica finale...\n";
foreach ($files_to_fix as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        preg_match_all('/\beach\s*\(/', $content, $matches);
        $remaining = count($matches[0]);
        if ($remaining > 0) {
            echo "  ⚠️  $file: ancora $remaining each() da fixare manualmente\n";
            
            // Mostra le linee con each() rimanenti
            $lines = explode("\n", $content);
            foreach ($lines as $num => $line) {
                if (preg_match('/\beach\s*\(/', $line)) {
                    $line_num = $num + 1;
                    echo "      Line $line_num: " . trim(substr($line, 0, 100)) . "...\n";
                }
            }
        } else {
            echo "  ✅ $file: CLEAN!\n";
        }
    }
}

echo "\n✨ Fix completato! Ricorda di testare tutto!\n";