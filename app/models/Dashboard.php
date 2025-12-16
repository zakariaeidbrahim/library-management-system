<?php
// app/models/Dashboard.php

class Dashboard {
    private $db;

    public function __construct() {
        // Initialize the Database connection
        $this->db = new Database;
    }

    /**
     * Private helper function to get a simple COUNT(*) from any table.
     * @param string $table The name of the table to count rows from.
     * @return int The count of rows, or 0 on failure.
     */
    private function getCount($table) {
        // Use double quotes for the query string to allow concatenation
        $this->db->query("SELECT COUNT(*) AS count FROM {$table}");
        
        if ($this->db->execute()) {
            // Retrieve the single result object and return the 'count' property
            return $this->db->single()->count;
        }
        return 0;
    }

    /**
     * Get the total number of books in the inventory.
     * @return int
     */
    public function getTotalBooks() {
        return $this->getCount('books');
    }

    /**
     * Get the total number of distinct categories.
     * @return int
     */
    public function getTotalCategories() {
        return $this->getCount('categories');
    }

    /**
     * Get the total number of registered members.
     * @return int
     */
    public function getTotalMembers() {
        // Assumes you have a 'members' table
        return $this->getCount('members'); 
    }

    /**
     * Get the total number of currently issued books.
     * @return int
     */
    public function getIssuedBookCount() {
        // Assumes a 'issues' table and a 'status' column
        $this->db->query('SELECT COUNT(*) AS count FROM issues WHERE status = :status');
        $this->db->bind(':status', 'issued'); // Adjust status based on your schema
        
        if ($this->db->execute()) {
            return $this->db->single()->count;
        }
        return 0;
    }
}