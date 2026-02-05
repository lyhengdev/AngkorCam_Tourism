<?php
/**
 * =====================================================
 * Tour Model - Tour Management
 * =====================================================
 */

class Tour {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Get all active tours
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM tours WHERE status = 'active' ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    /**
     * Get featured tours (for homepage)
     */
    public function getFeatured($limit = 6) {
        $stmt = $this->db->prepare("SELECT * FROM tours WHERE status = 'active' ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get tour by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tours WHERE id = ? AND status = 'active'");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Get tour by slug
     */
    public function getBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM tours WHERE slug = ? AND status = 'active'");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
    
    /**
     * Create new tour
     */
    public function create($data) {
        $sql = "INSERT INTO tours (title, slug, location, category, price, duration, description, highlights, included, excluded, image, available_seats) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            slugify($data['title']),
            $data['location'],
            $data['category'] ?? 'Cultural',
            $data['price'],
            $data['duration'],
            $data['description'] ?? '',
            $data['highlights'] ?? '',
            $data['included'] ?? '',
            $data['excluded'] ?? '',
            $data['image'] ?? null,
            $data['available_seats'] ?? 15
        ]);
    }
    
    /**
     * Update tour
     */
    public function update($id, $data) {
        $sql = "UPDATE tours SET 
                title = ?, slug = ?, location = ?, category = ?, price = ?, 
                duration = ?, description = ?, highlights = ?, included = ?, 
                excluded = ?, image = ?, available_seats = ?
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            slugify($data['title']),
            $data['location'],
            $data['category'],
            $data['price'],
            $data['duration'],
            $data['description'],
            $data['highlights'] ?? '',
            $data['included'] ?? '',
            $data['excluded'] ?? '',
            $data['image'] ?? null,
            $data['available_seats'],
            $id
        ]);
    }
    
    /**
     * Delete tour
     */
    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE tours SET status = 'inactive' WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Get tours by location
     */
    public function getByLocation($location) {
        $stmt = $this->db->prepare("SELECT * FROM tours WHERE location = ? AND status = 'active'");
        $stmt->execute([$location]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get tours by category
     */
    public function getByCategory($category) {
        $stmt = $this->db->prepare("SELECT * FROM tours WHERE category = ? AND status = 'active'");
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    }
    
    /**
     * Search tours
     */
    public function search($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->db->prepare("SELECT * FROM tours WHERE (title LIKE ? OR description LIKE ? OR location LIKE ?) AND status = 'active'");
        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }
}
