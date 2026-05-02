<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

class News
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Retrieve all news articles with author name, newest first.
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT n.*, u.name AS author_name
            FROM news n
            JOIN users u ON n.created_by = u.id
            ORDER BY n.published_at DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Retrieve a single news article by ID.
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT n.*, u.name AS author_name
            FROM news n
            JOIN users u ON n.created_by = u.id
            WHERE n.id = :id LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Create a new news article.
     */
    public function create(string $title, string $content, int $createdBy): bool
    {
        $stmt = $this->db->prepare("INSERT INTO news (title, content, created_by) VALUES (:title, :content, :created_by)");
        return $stmt->execute([
            ':title'      => trim($title),
            ':content'    => trim($content),
            ':created_by' => $createdBy,
        ]);
    }

    /**
     * Update an existing news article.
     */
    public function update(int $id, string $title, string $content): bool
    {
        $stmt = $this->db->prepare("UPDATE news SET title = :title, content = :content WHERE id = :id");
        return $stmt->execute([
            ':title'   => trim($title),
            ':content' => trim($content),
            ':id'      => $id,
        ]);
    }

    /**
     * Delete a news article by ID.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM news WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Count total news articles.
     */
    public function count(): int
    {
        $stmt = $this->db->query("SELECT COUNT(id) FROM news");
        return (int) $stmt->fetchColumn();
    }
}
