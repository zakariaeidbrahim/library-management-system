<?php
// app/controllers/BookController.php

// --- FIX 1: Require necessary models ---
require_once APP_ROOT . '/app/models/Book.php';
require_once APP_ROOT . '/app/models/Issue.php'; 
require_once APP_ROOT . '/app/models/Category.php'; // Assuming getCategories() is in Category or Book model

class BookController {

    protected $bookModel;
    protected $issueModel; // NEW: Property for Issue Model
    protected $categoryModel; // Assuming category model is separate

    public function __construct() {
        // Authentication check
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL_ROOT . '/index.php?url=login');
            exit;
        }

        // Initialize Models 
        $this->bookModel = new Book;
        $this->issueModel = new Issue; // NEW: Initialize Issue Model
        // Assuming Category model is either included in Book model or initialized here
        // $this->categoryModel = new Category; 
    }

    //-------------------------------------------------------------
    // URL: URL_ROOT/index.php?url=book/index
    // Lists all books AND currently issued books
    //-------------------------------------------------------------
    public function index() {
        // 1. Get main book data
        $books = $this->bookModel->getBooks();

        // 2. Get Issued Book Data (NEW)
        $issuedBooks = $this->issueModel->getCurrentlyIssuedBooks(); 

        $data = [
            'page_title' => 'Manage Books', // Changed 'title' to 'page_title' for consistency
            'books' => $books,
            'issued_books' => $issuedBooks, // <-- PASS THE ISSUE DATA to the view
        ];

        // Load dashboard/books.php
        $this->view('dashboard/books', $data);
    }

    //-------------------------------------------------------------
    // URL: URL_ROOT/index.php?url=book/add
    // Add a new book
    //-------------------------------------------------------------
    public function add() {

        // Assuming getCategories() method is in the Book Model for simplicity
        $categories = $this->bookModel->getCategories(); 

        $data = [
            'page_title'    => 'Add New Book',
            'categories'    => $categories,
            'isbn'          => '',
            'title'         => '',
            'author'        => '',
            'publisher'     => '',
            'year'          => '',
            'category_id'   => '',
            'total_copies'  => '',
            'description'   => '',
            'message'       => ''
        ];

        // Handle POST form submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Use FILTER_UNSAFE_RAW instead of FILTER_SANITIZE_STRING 
            // if you are handling HTML/XSS protection in the view or model.
            // Using FILTER_SANITIZE_STRING for consistency with original code:
            $post_data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = array_merge($data, [
                'isbn'          => trim($post_data['isbn'] ?? ''),
                'title'         => trim($post_data['title'] ?? ''),
                'author'        => trim($post_data['author'] ?? ''),
                'publisher'     => trim($post_data['publisher'] ?? ''),
                'year'          => trim($post_data['year'] ?? ''),
                'category_id'   => trim($post_data['category_id'] ?? ''),
                'total_copies'  => trim($post_data['total_copies'] ?? ''),
                'description'   => trim($post_data['description'] ?? ''),
            ]);

            // Validation
            if (
                empty($data['title']) ||
                empty($data['author']) ||
                empty($data['total_copies']) ||
                empty($data['category_id'])
            ) {
                $data['message'] = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    Please fill in required fields (Title, Author, Copies, Category).</div>';
            
            } elseif (!is_numeric($data['total_copies']) || $data['total_copies'] < 1) {
                $data['message'] = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    Total copies must be a number greater than 0.</div>';

            } else {
                // Attempt to add the book
                if ($this->bookModel->addBook($data)) {
                    header('Location: ' . URL_ROOT . '/index.php?url=book/index&success=added');
                    exit;
                } else {
                    $data['message'] = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                        Something went wrong. Could not add book.</div>';
                }
            }
        }

        // Load dashboard/books_add.php
        $this->view('dashboard/books_add', $data);
    }
    
    // ... (Add edit, delete methods here if needed) ...

    // View loader helper
    private function view($view, $data = []) {
        extract($data);

        $path = APP_ROOT . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php';

        if (file_exists($path)) {
            require_once $path;
        } else {
            die("View does not exist: " . $path);
        }
    }
}