<?php
// app/controllers/MemberController.php

// Ensure the Member model is loaded
require_once APP_ROOT . '/app/models/Member.php'; 

class MemberController {
    
    protected $memberModel;
    
    public function __construct() {
        // --- Authentication Check (Standard practice) ---
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL_ROOT . '/index.php?url=login');
            exit;
        }
        
        $this->memberModel = new Member;
    }

    // Action: URL_ROOT/index.php?url=member/add
    public function add() {
        
        $data = [
            'page_title' => 'Add New Member',
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone' => '',
            'address' => '',
            'message' => ''
        ];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post_data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Clean and trim data
            $data['first_name'] = trim($post_data['first_name']);
            $data['last_name'] = trim($post_data['last_name']);
            $data['email'] = trim($post_data['email']);
            $data['phone'] = trim($post_data['phone']);
            $data['address'] = trim($post_data['address']);

            // Simple Validation
            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
                $data['message'] = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">Please fill in required fields (Name and Email).</div>';
            } elseif ($this->memberModel->addMember($data)) {
                // Success! Redirect with success flag
                header('Location: ' . URL_ROOT . '/index.php?url=member/add&success=true');
                exit;
            } else {
                $data['message'] = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">An error occurred while adding the member. Email may already be registered.</div>';
            }
        }
        
        $this->view('member/add', $data);
    }
    
    // Simple View Loader helper function 
    private function view($view, $data = []) {
        extract($data); 
        $path_attempted = APP_ROOT . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php'; 
        
        if (file_exists($path_attempted)) { 
            require_once $path_attempted;
        } else {
            die("View does not exist: " . $path_attempted);
        }
    }
}