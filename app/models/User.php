<?php
// app/models/User.php
// Modèle pour l'authentification de l'administrateur

// LIGNE 4 : Supprimez la ligne 'use LoggerTrait;' si elle existe ici.

class User {
    
    // Ajoutez 'use LoggerTrait;' À L'INTÉRIEUR de la classe.
    use LoggerTrait; 
    
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    /**
     * Tente de connecter un utilisateur (administrateur).
     * @param string $username
     * @param string $password
     * @return object|false L'objet utilisateur si succès, false sinon.
     */
    public function login(string $username, string $password) {
        // ... (votre logique de connexion) ...

        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);
        $row = $this->db->single();

        if (!$row) {
            $this->log("Login Failed: User '{$username}' not found in DB.", 'warning');
            return false;
        }

        $hashed_password = $row->password; 
        
        // Vérifier le mot de passe
        if (password_verify($password, $hashed_password)) {
            $this->log("Login Success: User '{$username}' verified.", 'info');
            return $row; // Connexion réussie
        } else {
            $this->log("Login Failed: Password verification failed for '{$username}'.", 'warning');
            return false; // Mot de passe incorrect
        }
    }
}