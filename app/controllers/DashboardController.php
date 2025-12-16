<?php
// app/controllers/DashboardController.php

// --- FIX 1: Require individual Model files for better MVC separation ---
require_once APP_ROOT . '/app/models/Book.php';
require_once APP_ROOT . '/app/models/Issue.php';
require_once APP_ROOT . '/app/models/Member.php';
require_once APP_ROOT . '/app/models/Category.php'; 

class DashboardController {
    
    // --- FIX 2: Use individual model properties ---
    protected $bookModel;
    protected $issueModel; 
    protected $memberModel; 
    protected $categoryModel; 
    
    public function __construct() {
        // --- Authentication Check ---
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL_ROOT . '/index.php?url=login');
            exit;
        }
        
        // --- FIX 3: Initialize individual Models ---
        $this->bookModel = new Book;
        $this->issueModel = new Issue;
        $this->memberModel = new Member;
        $this->categoryModel = new Category; 
    }

    public function index() {
        
        // 1. Fetch the main data counts
        $totalBooks = $this->bookModel->getTotalCopies(); // Assuming this counts all copies
        $totalCategories = $this->categoryModel->getCategoryCount();
        $totalMembers = $this->memberModel->getMemberCount();
        $issuedBooks = $this->issueModel->getIssuedCount(); // Total currently borrowed books

        // 2. Fetch the NEW Overdue Data
        $overdueIssues = $this->issueModel->getOverdueIssues(); // List of overdue issues (for the table)
        $overdueCount = count($overdueIssues); // Count of overdue issues (for the stat card)
        
        // 3. Package the data for the view
        $data = [
            'page_title' => 'Dashboard Overview', // Using 'page_title' for consistency
            'username' => $_SESSION['username'] ?? 'Administrator',
            
            // Stats Card Data
            'totalBooks' => $totalBooks,
            'totalCategories' => $totalCategories,
            'totalMembers' => $totalMembers,
            'issuedBooks' => $issuedBooks,
            
            // --- FIX 4: Pass Overdue Data to the view ---
            'overdueCount' => $overdueCount,      
            'overdueIssues' => $overdueIssues,    
        ];

        // 4. Load the dashboard view
        $this->view('dashboard/index', $data);
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