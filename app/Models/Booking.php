<?php
/**
 * =====================================================
 * Booking Model - Booking Management
 * =====================================================
 */

class Booking {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Create new booking
     */
    public function create($data) {
        $sql = "INSERT INTO bookings (booking_code, tour_id, user_id, customer_name, customer_email, customer_phone, travelers, booking_date, total_price, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['booking_code'],
            $data['tour_id'],
            $data['user_id'] ?? null,
            $data['customer_name'],
            $data['customer_email'],
            $data['customer_phone'],
            $data['travelers'],
            $data['booking_date'],
            $data['total_price'],
            $data['notes'] ?? ''
        ]);
    }
    
    /**
     * Get all bookings
     */
    public function getAll() {
        $sql = "SELECT b.*, t.title as tour_title, t.location, u.name as user_name 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.id 
                LEFT JOIN users u ON b.user_id = u.id 
                ORDER BY b.created_at DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Get bookings by user
     */
    public function getByUser($userId) {
        $sql = "SELECT b.*, t.title as tour_title, t.location, t.image 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.id 
                WHERE b.user_id = ? 
                ORDER BY b.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get booking by ID
     */
    public function getById($id) {
        $sql = "SELECT b.*, t.title as tour_title, t.location, t.duration 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.id 
                WHERE b.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Get booking by code
     */
    public function getByCode($code) {
        $sql = "SELECT b.*, t.title as tour_title, t.location 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.id 
                WHERE b.booking_code = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$code]);
        return $stmt->fetch();
    }
    
    /**
     * Update booking status
     */
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
    
    /**
     * Get booking statistics
     */
    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total_bookings,
                SUM(CASE WHEN status = 'confirmed' THEN total_price ELSE 0 END) as total_revenue,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_bookings,
                COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as confirmed_bookings
                FROM bookings";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
    
    /**
     * Get recent bookings
     */
    public function getRecent($limit = 10) {
        $limit = (int)$limit;
        if ($limit < 1) {
            $limit = 1;
        }
        // TiDB doesn't allow bound parameters in LIMIT.
        $sql = "SELECT b.*, t.title as tour_title, t.location 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.id 
                ORDER BY b.created_at DESC 
                LIMIT " . $limit;
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
