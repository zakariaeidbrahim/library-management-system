<?php
// app/controllers/IssueController.php

require_once APP_ROOT . '/app/models/Book.php';
require_once APP_ROOT . '/app/models/Member.php';
require_once APP_ROOT . '/app/models/Issue.php'; 

class IssueController {
    
    protected $bookModel;
    protected $memberModel;
    protected $issueModel;
    
    public function __construct() {
        // 1. Authentication Check
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL_ROOT . '/index.php?url=login');
            exit;
        }
        
        // 2. Initialize Models 
        $this->bookModel = new Book;
        $this->memberModel = new Member;
        $this->issueModel = new Issue;
    }

    //-------------------------------------------------------------
    // --- 1. Borrow Book Action (issue/borrow) ---
    //-------------------------------------------------------------
    public function borrow() {
        
        // Default data for the form (GET request)
        $data = [
            'page_title' => 'Issue/Borrow Book',
            'members' => $this->memberModel->getAllMembers(),
            'available_books' => $this->bookModel->getAvailableBooks(),
            'member_id' => '',
            'book_id' => '',
            'due_date' => date('Y-m-d', strtotime('+7 days')),
            'message' => ''
        ];
        
        // Check for success/error messages after redirect
        if (isset($_GET['success']) && $_GET['success'] == 'issued') {
            $data['message'] = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">Book successfully issued!</div>';
        }
        
        // Check for POST request (form submission)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and validate inputs
            $data['member_id'] = filter_input(INPUT_POST, 'member_id', FILTER_SANITIZE_NUMBER_INT);
            $data['book_id'] = filter_input(INPUT_POST, 'book_id', FILTER_SANITIZE_NUMBER_INT);
            $data['due_date'] = filter_input(INPUT_POST, 'due_date', FILTER_SANITIZE_STRING); // Date format validation is usually done later

            // Simple Validation
            if (empty($data['member_id']) || empty($data['book_id']) || empty($data['due_date'])) {
                $data['message'] = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">Please fill in all required fields.</div>';
            } else {
                // Attempt to issue the book
                if ($this->issueModel->issueBook($data)) {
                    // Success! Redirect to prevent re-submission and show message
                    header('Location: ' . URL_ROOT . '/index.php?url=issue/borrow&success=issued');
                    exit;
                } else {
                    $data['message'] = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">Failed to issue book. Check database or book stock status.</div>';
                }
            }
        }
        
        $this->view('dashboard/issue_borrow', $data); 
    }

    //-------------------------------------------------------------
    // --- 2. View/Manage Borrowed Books Action (issue/index) ---
    //-------------------------------------------------------------
    public function index() {
        
        $issuedBooks = $this->issueModel->getCurrentlyIssuedBooks();
        
        $data = [
            'page_title' => 'Manage Borrowed Books',
            'issued_books' => $issuedBooks,
        ];

        $this->view('issue/index', $data);
    }
    
    //-------------------------------------------------------------
    // --- 3. Return Book Action (issue/returnBook/ID) ---
    //-------------------------------------------------------------
    public function returnBook($params) {
        // Ensure an ID is passed and it is a positive integer
        if (empty($params[0]) || !filter_var($params[0], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
            header('Location: ' . URL_ROOT . '/index.php?url=issue/index&error=invalid_id');
            exit;
        }

        $issueId = (int)$params[0];

        if ($this->issueModel->returnBook($issueId)) {
            // Success: redirect back to the index page
            header('Location: ' . URL_ROOT . '/index.php?url=issue/index&success=returned');
            exit;
        } else {
            // Failure: redirect back with an error
            header('Location: ' . URL_ROOT . '/index.php?url=issue/index&error=return_failed');
            exit;
        }
    }
    
    //-------------------------------------------------------------
    // --- Simple View Loader Helper ---
    //-------------------------------------------------------------
    
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