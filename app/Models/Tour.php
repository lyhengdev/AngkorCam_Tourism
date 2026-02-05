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
     * Get filtered tours with pagination
     */
    public function getFiltered($filters, $page = 1, $perPage = 12) {
        $params = [];
        $where = $this->buildWhere($filters, $params);

        $orderBy = $this->resolveSort($filters['sort'] ?? 'newest');
        $offset = max(0, ($page - 1) * $perPage);

        $sql = "SELECT * FROM tours $where ORDER BY $orderBy LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Count filtered tours
     */
    public function countFiltered($filters) {
        $params = [];
        $where = $this->buildWhere($filters, $params);

        $sql = "SELECT COUNT(*) AS total FROM tours $where";
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $row = $stmt->fetch();
        return (int)($row['total'] ?? 0);
    }

    /**
     * Get distinct locations
     */
    public function getLocations() {
        $stmt = $this->db->query("SELECT DISTINCT location FROM tours WHERE status = 'active' ORDER BY location ASC");
        return $stmt->fetchAll();
    }

    /**
     * Get distinct categories
     */
    public function getCategories() {
        $stmt = $this->db->query("SELECT DISTINCT category FROM tours WHERE status = 'active' ORDER BY category ASC");
        return $stmt->fetchAll();
    }
    
    /**
     * Get featured tours (for homepage)
     */
    public function getFeatured($limit = 6) {
        $limit = (int)$limit;
        if ($limit < 1) {
            $limit = 1;
        }
        // TiDB doesn't allow bound parameters in LIMIT.
        $sql = "SELECT * FROM tours WHERE status = 'active' ORDER BY created_at DESC LIMIT " . $limit;
        $stmt = $this->db->query($sql);
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

    private function buildWhere($filters, &$params) {
        $conditions = ["status = 'active'"];

        $keyword = trim($filters['q'] ?? '');
        if ($keyword !== '') {
            $conditions[] = "(title LIKE :q OR description LIKE :q OR location LIKE :q)";
            $params[':q'] = '%' . $keyword . '%';
        }

        $category = trim($filters['category'] ?? '');
        if ($category !== '') {
            $conditions[] = "category = :category";
            $params[':category'] = $category;
        }

        $location = trim($filters['location'] ?? '');
        if ($location !== '') {
            $conditions[] = "location = :location";
            $params[':location'] = $location;
        }

        $duration = trim($filters['duration'] ?? '');
        if ($duration !== '' && ctype_digit($duration)) {
            $conditions[] = "duration = :duration";
            $params[':duration'] = (int)$duration;
        }

        $minPrice = $filters['min_price'] ?? '';
        if ($minPrice !== '' && is_numeric($minPrice)) {
            $conditions[] = "price >= :min_price";
            $params[':min_price'] = (float)$minPrice;
        }

        $maxPrice = $filters['max_price'] ?? '';
        if ($maxPrice !== '' && is_numeric($maxPrice)) {
            $conditions[] = "price <= :max_price";
            $params[':max_price'] = (float)$maxPrice;
        }

        return "WHERE " . implode(' AND ', $conditions);
    }

    private function resolveSort($sort) {
        $map = [
            'price_asc' => 'price ASC',
            'price_desc' => 'price DESC',
            'duration_asc' => 'duration ASC',
            'duration_desc' => 'duration DESC',
            'newest' => 'created_at DESC'
        ];

        return $map[$sort] ?? $map['newest'];
    }
}
