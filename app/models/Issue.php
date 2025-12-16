<?php
// app/models/Issue.php

// Ensure the Book model is loaded, as we need to update book counts.
require_once APP_ROOT . '/app/models/Book.php'; 
// --- NOUVEAU : Charger le Trait pour le logging ---
require_once APP_ROOT . '/app/traits/LoggerTrait.php'; 

class Issue {
    // --- NOUVEAU : Utiliser le Trait de Logging ---
    use LoggerTrait; 
    
    private $db;
    private $bookModel; 

    public function __construct() {
        $this->db = new Database;
        $this->bookModel = new Book; 
    }

    //-------------------------------------------------------------
    // 1. ISSUE BOOK
    //-------------------------------------------------------------
    /**
     * Enregistre un nouvel emprunt et met à jour le stock.
     */
    public function issueBook($data) {
        $this->db->query('
            INSERT INTO issues (book_id, member_id, due_date, status) 
            VALUES (:book_id, :member_id, :due_date, :status)
        ');
        
        $this->db->bind(':book_id', $data['book_id']);
        $this->db->bind(':member_id', $data['member_id']);
        $this->db->bind(':due_date', $data['due_date']);
        $this->db->bind(':status', 'issued');
        
        // Execute the insertion
        if ($this->db->execute()) {
            // SUCCESS: Mise à jour du stock
            if ($this->bookModel->decreaseAvailableCopies($data['book_id'])) {
                 // Log SUCCESS (Transaction critique enregistrée)
                $this->log("Livre emprunté avec succès. Stock mis à jour.", 'info', [
                    'action' => 'ISSUE_SUCCESS',
                    'book_id' => $data['book_id'],
                    'member_id' => $data['member_id'],
                ]);
                return true;
            } else {
                 // Log WARNING/ERROR (Insertion OK, mais stock KO)
                $this->log("ERREUR CRITIQUE: Emprunt DB OK, mais mise à jour du stock échouée.", 'error', [
                    'action' => 'STOCK_UPDATE_FAIL',
                    'book_id' => $data['book_id'],
                ]);
                return false;
            }
        }
        
        // Log FAILURE (Erreur d'insertion dans la DB)
        $this->log("Échec de l'emprunt du livre. Erreur d'insertion DB.", 'error', [
            'action' => 'ISSUE_DB_FAIL',
            'book_id' => $data['book_id'],
            'member_id' => $data['member_id'],
        ]);
        return false;
    }
    
    //-------------------------------------------------------------
    // 2. GET CURRENT ISSUES 
    //-------------------------------------------------------------
    public function getCurrentlyIssuedBooks() {
        $this->db->query("
            SELECT 
                i.id AS issue_id, 
                i.issue_date, 
                i.due_date,
                b.title AS book_title, 
                b.isbn,
                m.first_name, 
                m.last_name,
                m.email,
                i.member_id,
                i.book_id
            FROM issues i
            INNER JOIN books b ON i.book_id = b.id
            INNER JOIN members m ON i.member_id = m.id
            WHERE i.status = 'issued'
            ORDER BY i.due_date ASC;
        ");
        
        return $this->db->resultSet();
    }

    //-------------------------------------------------------------
    // 3. GET ISSUED COUNT 
    //-------------------------------------------------------------
    public function getIssuedCount(): int { // Ajout du type hint ': int'
        $this->db->query("SELECT COUNT(*) AS count FROM issues WHERE status = 'issued'");
        $row = $this->db->single();
        return $row->count ?? 0;
    }

    //-------------------------------------------------------------
    // 4. GET OVERDUE ISSUES 
    //-------------------------------------------------------------
    public function getOverdueIssues(): array { // Ajout du type hint ': array'
        $this->db->query("
            SELECT 
                i.id AS issue_id, 
                i.due_date,
                b.title AS book_title, 
                m.first_name, 
                m.last_name
            FROM issues i
            INNER JOIN books b ON i.book_id = b.id
            INNER JOIN members m ON i.member_id = m.id
            WHERE i.status = 'issued'
            AND i.due_date < NOW()
            ORDER BY i.due_date ASC;
        ");
        return $this->db->resultSet();
    }
    
    //-------------------------------------------------------------
    // 5. RETURN BOOK
    //-------------------------------------------------------------
    /**
     * Enregistre le retour d'un livre et met à jour le stock.
     */
    public function returnBook($issueId) {
        // 1. Get the book_id before updating the status
        $this->db->query("SELECT book_id FROM issues WHERE id = :id AND status = 'issued'");
        $this->db->bind(':id', $issueId);
        $result = $this->db->single();

        if ($result && $result->book_id) {
            $bookId = $result->book_id;

            // 2. Update the issue record (set status to 'returned' and log return date)
            $this->db->query("
                UPDATE issues 
                SET status = 'returned', return_date = NOW() 
                WHERE id = :id AND status = 'issued'
            ");
            $this->db->bind(':id', $issueId);
            
            if ($this->db->execute()) {
                // 3. SUCCESS: Now, increase the available copies count
                if ($this->bookModel->increaseAvailableCopies($bookId)) {
                     // Log SUCCESS (Transaction critique enregistrée)
                    $this->log("Livre retourné avec succès. Stock mis à jour.", 'info', [
                        'action' => 'RETURN_SUCCESS',
                        'issue_id' => $issueId,
                        'book_id' => $bookId,
                    ]);
                    return true;
                } else {
                     // Log WARNING/ERROR (Retour DB OK, mais stock KO)
                    $this->log("ERREUR CRITIQUE: Retour DB OK, mais mise à jour du stock échouée.", 'error', [
                        'action' => 'RETURN_STOCK_FAIL',
                        'issue_id' => $issueId,
                    ]);
                    return false;
                }
            }
        }
        
        // Log FAILURE (Problème de lecture de l'Issue ID ou erreur DB)
        $this->log("Échec du retour de livre. ID introuvable ou erreur DB.", 'error', [
            'action' => 'RETURN_FAIL',
            'issue_id' => $issueId,
        ]);
        return false;
    }
}