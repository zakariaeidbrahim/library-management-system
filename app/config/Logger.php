<?php
// app/config/Logger.php
// Ce fichier initialise et configure l'objet Monolog

// --- FIX: Déclarations 'use' pour importer les classes Monolog ---
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
// -------------------------------------------------------------

// On suppose que APP_ROOT est défini dans app/core/config.php
// Définir le chemin d'accès au fichier de log
$log_file_path = APP_ROOT . '/app/logs/application.log';

// 1. Créer le logger avec le nom de canal 'library_app'
// La classe Logger est maintenant reconnue grâce au "use Monolog\Logger;"
$logger = new Logger('library_app');

// 2. Créer un gestionnaire (Handler) pour écrire les logs dans le fichier.
// La classe StreamHandler est maintenant reconnue grâce au "use Monolog\Handler\StreamHandler;"
$fileHandler = new StreamHandler($log_file_path, Logger::DEBUG);

// 3. Ajouter le gestionnaire au logger
$logger->pushHandler($fileHandler);

// 4. Retourner l'objet logger
return $logger;