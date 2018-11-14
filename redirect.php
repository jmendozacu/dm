<?php

$currentURI = $_SERVER['REQUEST_URI'];

if ($currentURI != '/') {
    $files = array(
        'redirect/urls.csv',
        'redirect/urls2.csv',
    );

    foreach ($files as $file) {
        if (($handle = fopen($file, 'r')) === FALSE) {
            continue;
        }
        
        while(($row = fgetcsv($handle, 0, ",")) !== FALSE) {
            if (strpos($row[0], $currentURI) !== FALSE) {
                for ($i=1;$i<count($row);$i++) {
                    if (strpos($row[$i], 'http') !== FALSE) {
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header('Location: ' . $row[$i]);
                        exit;
                    }
                }
            }
        }
    }
}

