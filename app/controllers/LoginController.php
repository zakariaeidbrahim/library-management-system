<?php
// app/controllers/LoginController.php

// Le Trait de logging est requis
require_once APP_ROOT . '/app/traits/LoggerTrait.php'; 

class LoginController {
    use LoggerTrait; 
    
    // Identifiants de l'administrateur définis en dur pour la démo.
    // NOTE: Utiliser des variables d'environnement ou config.php est préférable en vrai.
    private const ADMIN_USERNAME = 'admin';
    private const ADMIN_PASSWORD_HASH = '$2y$10$DlU2CuXoAPdf.sNxrlfuz.Rxh5Jd0yjanaKpwoibDmc33pLnjjiuC'; // Exemple de hachage pour 'admin123'
    // Pour générer un nouveau hachage, exécutez: echo password_hash('votre_mot_de_passe', PASSWORD_DEFAULT);

    // Retrait de $userModel

    public function __construct() {
        // Aucune initialisation de modèle nécessaire
    }
    
    // Ajout de la méthode index pour satisfaire le routeur
    public function index() {
        $this->login();
    }

    public function login() {
        $data = [
            'error_message' => '', 
            'username' => ''
        ];
        
        $loggedInUser = null; 

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $post_data = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $username = $post_data['username'] ?? '';
            $password = $post_data['password'] ?? '';
            
            // 1. Vérification du nom d'utilisateur
            if ($username !== self::ADMIN_USERNAME) {
                $loggedInUser = false;
            } else {
                // 2. Vérification du mot de passe haché
                if (password_verify($password, self::ADMIN_PASSWORD_HASH)) {
                    // Connexion réussie, créer un objet utilisateur simulé
                    $loggedInUser = (object)['id' => 1, 'username' => self::ADMIN_USERNAME];
                } else {
                    $loggedInUser = false;
                }
            }
            
            // --- Traitement du résultat ---
            if ($loggedInUser) {
                
                $_SESSION['user_id'] = $loggedInUser->id;
                $_SESSION['username'] = $loggedInUser->username;

                // Log SUCCESS
                $this->log("Connexion Administrateur réussie sans modèle DB.", 'critical', [
                    'user_id' => $loggedInUser->id,
                    'username' => $loggedInUser->username,
                    'ip_address' => $_SERVER['REMOTE_ADDR']
                ]);
                header('Location: ' . URL_ROOT . '/index.php?url=dashboard/index');
                exit;
            } else {
                // Log FAILURE
                $this->log("Échec de connexion (sans DB).", 'warning', [
                    'attempted_username' => $username,
                    'ip_address' => $_SERVER['REMOTE_ADDR']
                ]);
                
                $data = [
                    'error_message' => 'Nom d\'utilisateur ou mot de passe incorrect.',
                    'username' => $username
                ];
            }
        } 
        
        $this->view('login', $data); 
    }
    
    // ... (méthode view() ici) ...
    private function view($view, $data = []) {
        extract($data); 
        
        $path_attempted = APP_ROOT . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php'; 
        
        if (file_exists($path_attempted)) { 
            require_once $path_attempted;
        } else {
            $this->log("ERREUR CRITIQUE: Vue manquante.", 'critical', ['view_path' => $path_attempted]);
            die("View does not exist: " . $path_attempted);
        }
    }
}