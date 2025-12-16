<?php
// app/models/Category.php

class Category {
    private $db;

    public function __construct() {
        // Initialize the Database connection
        $this->db = new Database;
    }

    //-------------------------------------------------------------
    // 1. READ: Get all categories (For dropdowns/list pages)
    //-------------------------------------------------------------
    /**
     * Fetches all categories.
     * @return array List of categories (objects).
     */
    public function getCategories(): array { 
        $this->db->query('SELECT * FROM categories ORDER BY name ASC');
        
        // Execute the query and return the results
        if ($this->db->execute()) {
            return $this->db->resultSet();
        }
        return [];
    }

    //-------------------------------------------------------------
    // 2. READ: Get category count (For DashboardController)
    //-------------------------------------------------------------
    /**
     * Returns the total count of distinct categories.
     * @return int
     */
    public function getCategoryCount(): int { // ADDED THIS METHOD
        $this->db->query("SELECT COUNT(id) AS count FROM categories");
        $row = $this->db->single();
        return $row->count ?? 0;
    }
    
    //-------------------------------------------------------------
    // 3. READ: Get single category by ID
    //-------------------------------------------------------------
    /**
     * Fetches a single category by ID.
     * @param int $id The category ID.
     * @return object|bool The category object or false if not found.
     */
    public function getCategoryById($id) {
        $this->db->query('SELECT * FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);
        
        if ($this->db->execute()) {
            return $this->db->single();
        }
        return false;
    }

    //-------------------------------------------------------------
    // 4. CREATE: Add a new category
    //-------------------------------------------------------------
    /**
     * Adds a new category to the database.
     * @param array $data Contains 'name' of the category.
     * @return bool True on success, false on failure.
     */
    public function addCategory($data): bool {
        $this->db->query('INSERT INTO categories (name) VALUES (:name)');
        $this->db->bind(':name', $data['name']);
        
        return $this->db->execute();
    }
    
    //-------------------------------------------------------------
    // 5. UPDATE: Update an existing category
    //-------------------------------------------------------------
    /**
     * Updates the name of an existing category.
     * @param array $data Contains 'id' and 'name'.
     * @return bool True on success, false on failure.
     */
    public function updateCategory($data): bool {
        $this->db->query('UPDATE categories SET name = :name WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);

        return $this->db->execute();
    }

    //-------------------------------------------------------------
    // 6. DELETE: Delete a category
    //-------------------------------------------------------------
    /**
     * Deletes a category by ID.
     * NOTE: Database constraints should handle books tied to this category.
     * @param int $id The category ID.
     * @return bool True on success, false on failure.
     */
    public function deleteCategory($id): bool {
        $this->db->query('DELETE FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }
}