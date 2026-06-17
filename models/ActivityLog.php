<?php
class ActivityLog {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function add($userId, $action, $description) {
        $sql = "INSERT INTO activity_logs (user_id, action, description) VALUES (?, ?, ?)";
        return $this->db->prepare($sql)->execute([$userId, $action, $description]);
    }

    public function getRecent($limit = 10) {
        $sql = "SELECT l.*, u.nama 
                FROM activity_logs l 
                LEFT JOIN users u ON l.user_id = u.id 
                ORDER BY l.created_at DESC 
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
