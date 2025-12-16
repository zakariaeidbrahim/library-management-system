<?php
// app/controllers/CategoryController.php

class CategoryController {
    
    protected $categoryModel;
    
    public function __construct() {
        // We assume session_start() is already called in index.php
        
        // --- Authentication Check ---
        if (!isset($_SESSION['user_id'])) {
            // Redirect to login page using the correct URL format
            header('Location: ' . URL_ROOT . '/index.php?url=login');
            exit;
        }
        
        // --- ACTIVATED: Initialize the Category Model ---
        // Since Category.php model has been created, we initialize it directly.
        $this->categoryModel = new Category;
    }

    // Action: URL_ROOT/index.php?url=category/index (Lists all categories)
    public function index() {
        $categories = []; // Initialize for safety
        
        if ($this->categoryModel) {
            // --- ACTIVATED: Fetch all categories data ---
            $categories = $this->categoryModel->getCategories();
        }

        $data = [
            'title' => 'Manage Categories',
            'categories' => $categories
        ];

        // Load the view from app/views/dashboard/categories.php
        $this->view('dashboard/categories', $data); 
    }
    
    // --- New Method: Handles adding a new category ---
    // Action: URL_ROOT/index.php?url=category/add
    public function add() {
        // Default data initialization (needed for the form fields)
        $data = [
            'page_title' => 'Add New Category',
            'name' => '',
            'message' => ''
        ];
        
        // Check for POST request (form submission)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and collect data
            $post_data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data['name'] = trim($post_data['name']);
            
            // Simple Validation
            if (empty($data['name'])) {
                $data['message'] = '<div class="alert alert-danger">Category name cannot be empty.</div>';
            } else {
                // Attempt to add category
                if ($this->categoryModel->addCategory($data)) {
                    // Success! Redirect to the list view
                    header('Location: ' . URL_ROOT . '/index.php?url=category/index&success=added');
                    exit;
                } else {
                    $data['message'] = '<div class="alert alert-danger">Something went wrong. Could not add category.</div>';
                }
            }
        }
        
        // Load the view (for GET request or upon validation/db error)
        $this->view('dashboard/categories_add', $data); // Assuming a view named categories_add.php
    }
    
    // --- View Loader Helper (Remains correct) ---
    private function view($view, $data = []) {
        extract($data); 
        
        // Use DIRECTORY_SEPARATOR for robust path construction
        $path_attempted = APP_ROOT . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php'; 
        
        if (file_exists($path_attempted)) { 
            require_once $path_attempted;
        } else {
            die("View does not exist: " . $path_attempted);
        }
    }
}