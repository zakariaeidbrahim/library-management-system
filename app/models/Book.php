<?php
// app/models/Book.php

class Book {
    private $db;

    public function __construct() {
        // Initialize the Database connection
        $this->db = new Database;
    }

    //-------------------------------------------------------------
    // 1. READ: Get all books (for dashboard/books.php)
    //-------------------------------------------------------------
    public function getBooks() {
        $this->db->query('
            SELECT 
                b.*, 
                c.name AS category_name
            FROM 
                books b
            INNER JOIN 
                categories c ON b.category_id = c.id
            ORDER BY 
                b.title ASC
        ');

        return $this->db->resultSet();
    }

    //-------------------------------------------------------------
    // 2. CREATE: Add a new book
    //-------------------------------------------------------------
    public function addBook($data) {
        $this->db->query('
            INSERT INTO books (isbn, title, author, publisher, publication_year, category_id, total_copies, available_copies, description) 
            VALUES (:isbn, :title, :author, :publisher, :publication_year, :category_id, :total_copies, :available_copies, :description)
        ');

        $this->db->bind(':isbn', $data['isbn']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':author', $data['author']);
        $this->db->bind(':publisher', $data['publisher']);
        $this->db->bind(':publication_year', $data['year']); 
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':total_copies', $data['total_copies']);
        $this->db->bind(':available_copies', $data['total_copies']); // Initially, available = total
        $this->db->bind(':description', $data['description']);

        return $this->db->execute();
    }
    
    //-------------------------------------------------------------
    // 3. READ: Get available books (for issue/borrow form)
    //-------------------------------------------------------------
    public function getAvailableBooks() {
        // Fix: Query only books that have at least one copy available
        $this->db->query('
            SELECT 
                id, title, isbn, available_copies 
            FROM 
                books 
            WHERE 
                available_copies > 0 
            ORDER BY 
                title ASC
        ');
        
        return $this->db->resultSet();
    }

    //-------------------------------------------------------------
    // 4. READ: Get all categories (for form dropdowns)
    //-------------------------------------------------------------
    public function getCategories() {
        $this->db->query('SELECT id, name FROM categories ORDER BY name ASC');
        return $this->db->resultSet();
    }

    // -----------------------------------------------------------
    // 5. UPDATE: Decrease stock when a book is issued (NEW)
    // -----------------------------------------------------------
    /**
     * Decreases the available_copies count for a book.
     * @param int $bookId The ID of the book.
     * @return bool
     */
    public function decreaseAvailableCopies($bookId) {
        $this->db->query('
            UPDATE books 
            SET available_copies = available_copies - 1 
            WHERE id = :id AND available_copies > 0
        ');
        $this->db->bind(':id', $bookId);
        
        // This execution must succeed for the issue to be fully complete
        return $this->db->execute();
    }

    // -----------------------------------------------------------
    // 6. UPDATE: Increase stock when a book is returned (NEW)
    // -----------------------------------------------------------
    /**
     * Increases the available_copies count for a book.
     * @param int $bookId The ID of the book.
     * @return bool
     */
    public function increaseAvailableCopies($bookId) {
        $this->db->query('
            UPDATE books 
            SET available_copies = available_copies + 1 
            WHERE id = :id
        ');
        $this->db->bind(':id', $bookId);
        
        return $this->db->execute();
    }

    // -----------------------------------------------------------
    // 7. READ: Get total copies (for Dashboard stats) (NEW)
    // -----------------------------------------------------------
    /**
     * Returns the sum of all 'total_copies' across all books.
     * @return int
     */
    public function getTotalCopies() {
        $this->db->query("SELECT SUM(total_copies) AS count FROM books");
        $row = $this->db->single();
        return $row->count ?? 0;
    }
}