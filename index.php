<?php
// C:\xampp\htdocs\library-management-system\index.php

// 0. Start Session FIRST (Essentiel pour la gestion des utilisateurs)
session_start();

// 1. Load configuration and database class
// NOTE: Ces chemins sont relatifs à la racine du projet
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

// ------------------------------------------------------------
// --- INTÉGRATION LOGGING ---
// ------------------------------------------------------------

// 2. Charger les dépendances de Composer (incluant Monolog)
// Ceci est CRITIQUE pour que les classes Monolog soient connues
require_once 'vendor/autoload.php';

// 3. Initialisation du Logger (charge et configure Monolog)
// Assurez-vous que ce fichier s'appelle bien 'Logger.php'
$GLOBALS['logger'] = require_once 'app/config/Logger.php';

// ------------------------------------------------------------

// 4. Simple Autoloader pour les classes locales (MVC)
spl_autoload_register(function($className) {
    // Liste des dossiers à auto-charger
    $folders = [
        'app/controllers/',
        'app/models/',
        'app/traits/', // Pour LoggerTrait.php
        'app/core/',
    ];

    foreach ($folders as $folder) {
        $path = $folder . $className . '.php';
        if (file_exists($path)) {
            require_once $path;
            return; // Sortir après avoir trouvé la classe/trait
        }
    }
});

// 5. Basic Router: Détermine quel contrôleur/méthode charger
$url = isset($_GET['url']) ? $_GET['url'] : 'login';
$url = explode('/', filter_var(trim($url, '/'), FILTER_SANITIZE_URL));

$controllerName = ucfirst($url[0]) . 'Controller';
$controllerPath = 'app/controllers/' . $controllerName . '.php';

// Si le fichier du contrôleur existe, charger et exécuter la méthode
if (file_exists($controllerPath)) {
    // La classe est auto-chargée par spl_autoload_register
    $controller = new $controllerName;
    $method = isset($url[1]) ? $url[1] : 'index';
    
    if (method_exists($controller, $method)) {
        unset($url[0]);
        unset($url[1]);
        $params = array_values($url);
        
        call_user_func_array([$controller, $method], $params);
    } else {
        // --- FIX : Gérer le cas où la méthode demandée (ou index) n'existe pas ---
        
        // 1. Vérifiez si la méthode par défaut spécifique au contrôleur (login/dashboard) existe.
        if (method_exists($controller, $url[0])) {
            $controller->{$url[0]}(); // Exécuter la méthode nommée comme le contrôleur (ex: /login -> login())
        }
        // 2. Sinon, tentez la méthode 'index()' (standard MVC fallback)
        else if (method_exists($controller, 'index')) {
            $controller->index();
        }
        // 3. Si même 'index()' n'existe pas, il y a une erreur de conception
        else {
             die("404 Not Found. Method '{$url[1]}' or default methods '{$url[0]}()'/'index()' not found in {$controllerName}.");
        }
        // --- Fin du FIX ---
    }
} else {
    // Si l'URL demandée n'existe pas, charger le LoginController par défaut
    
    $defaultControllerName = 'LoginController'; 
    $defaultMethodName = 'login';
    
    // On vérifie l'existence du fichier du contrôleur de secours
    if (file_exists('app/controllers/' . $defaultControllerName . '.php')) {
        // La classe est auto-chargée
        $auth = new $defaultControllerName; 
        $auth->$defaultMethodName();
    } else {
        // Message d'erreur critique si même le contrôleur de connexion est manquant
        die("404 Not Found. Default controller ('$defaultControllerName') missing.");
    }
}