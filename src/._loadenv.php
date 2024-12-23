<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This web app needs just Apache, PHP (74->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains all the ENVIRONMENT logic for the link shortener
-
v0.1.0 - Aldo Prinzi - 03 Nov 2024
=====================================================================
*/
try {
    loadEnv(__DIR__ . '/../.env'); 
} catch (Exception $e) {
    die('Errore: ' . $e->getMessage());
}
function loadEnv($envFilePath) {
    session_start();
    $_SESSION["pageTitle"] = "Prinzimaker's link shortener";
    if (!file_exists($envFilePath)) {
        throw new Exception('.env file non trovato nel percorso ' . $envFilePath);
    }

    $lines = file($envFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Salta le linee di commento e vuote
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Divide la linea in nome e valore
        $parts = explode('=', $line, 2);
        if (count($parts) == 2) {
            $name = trim($parts[0]);
            $value = trim($parts[1]);

            // Rimuove eventuali virgolette intorno al valore
            $value = trim($value, "'\"");

            // Imposta la variabile d'ambiente
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}
?>
