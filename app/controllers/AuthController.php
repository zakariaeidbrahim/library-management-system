<?php
// app/controllers/AuthController.php

class AuthController {
    
    // In a real application, you would initialize models here
    // protected $userModel;
    
    // public function __construct(){
    //     $this->userModel = $this->model('User');
    // }

    public function login() {
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Process form
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // 1. Validation (Basic)
        if (empty($username) || empty($password)) {
            $error = "Please enter both username and password.";
            $this->view('login', ['error' => $error, 'username' => $username]);
            return; // <--- Ensure this is here
        }

        // 2. Authentication (Mock)
        if ($username == 'admin' && $password == 'admin123') { 
            // SUCCESS block: Redirects and exits
            session_start(); 
            // ... set session variables ...
           header('Location: ' . URL_ROOT . '/index.php?url=dashboard');
            exit; // <--- Ensures immediate stop
        } else {
            // FAILURE block
            $error = "Invalid username or password.";
            $this->view('login', ['error' => $error, 'username' => $username]);
            return; // <--- Ensure this is here
        }

    } else {
        // Initial GET request
        $this->view('login');
    }
}
    
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        
        // --- FIX: Explicitly include index.php in the redirect path ---
        header('Location: ' . URL_ROOT . '/index.php?url=login'); 
        exit;
    }

    // Simple View Loader helper function
    private function view($view, $data = []) {
    extract($data); 
    
    // CHANGE FROM '../views/' to 'app/views/'
    $path_attempted = 'app/views/' . $view . '.php'; 
    
    if (file_exists($path_attempted)) { 
        require_once $path_attempted;
    } else {
        // Updated error message to show the correct failing path
        die("View does not exist: " . $path_attempted); 
    }
}
}