<?php
// app/models/Member.php

class Member {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    

    ## 📊 Read Methods

    // --- 1. READ: Get total number of members (Fix for DashboardController) ---
    /**
     * Returns the total count of registered members.
     * @return int
     */
    public function getMemberCount(): int {
        // SQL to count all rows in the members table
        $this->db->query("SELECT COUNT(id) AS count FROM members");
        
        // Fetch the single row result
        $row = $this->db->single();
        
        // Return the count, defaulting to 0
        return $row->count ?? 0;
    }
    
    // --- 2. READ: Get all members ---
    public function getAllMembers(): array {
        $this->db->query('SELECT * FROM members ORDER BY last_name ASC');
        
        if ($this->db->execute()) {
            return $this->db->resultSet();
        }
        return [];
    }

    // --- 3. READ: Get single member by ID (NEW) ---
    /**
     * Fetches a single member record by ID.
     * @param int $id The member's ID.
     * @return object|bool The member object or false if not found.
     */
    public function getMemberById($id) {
        $this->db->query('SELECT * FROM members WHERE id = :id');
        $this->db->bind(':id', $id);
        
        if ($this->db->execute()) {
            return $this->db->single();
        }
        return false;
    }

    

    ## ✍️ Write Methods

    // --- 4. CREATE: Add new member ---
    /**
     * Adds a new member to the database.
     * @param array $data Contains member details.
     * @return bool True on success, false on failure.
     */
    public function addMember($data): bool {
        $this->db->query('
            INSERT INTO members (first_name, last_name, email, phone, address, registration_date) 
            VALUES (:first_name, :last_name, :email, :phone, :address, NOW())
        ');
        
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        
        return $this->db->execute();
    }
    
    // --- 5. UPDATE: Update member details (NEW) ---
    /**
     * Updates an existing member's details.
     * @param array $data Contains id, first_name, last_name, email, phone, address.
     * @return bool True on success, false on failure.
     */
    public function updateMember($data): bool {
        $this->db->query('
            UPDATE members SET 
                first_name = :first_name, 
                last_name = :last_name, 
                email = :email, 
                phone = :phone, 
                address = :address 
            WHERE id = :id
        ');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        
        return $this->db->execute();
    }
    
    // --- 6. DELETE: Delete a member (NEW) ---
    /**
     * Deletes a member from the database.
     * @param int $id The member's ID.
     * @return bool True on success, false on failure.
     */
    public function deleteMember($id): bool {
        // NOTE: Ensure your database handles foreign key constraints (e.g., preventing deletion 
        // if the member has outstanding borrowed books).
        $this->db->query('DELETE FROM members WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
}