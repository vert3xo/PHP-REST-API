<?php
class Post {
    private $conn;
    private $table = 'posts';

    // Post properties
    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;

    }

    // Get Posts
    public function read() {
        // Create query
        $query = 'SELECT
                categories.name AS category_name,
                posts.id,
                posts.category_id,
                posts.title,
                posts.body,
                posts.author,
                posts.created_at
            FROM ' . $this->table . ' LEFT JOIN
                    categories ON posts.category_id = categories.id
                ORDER BY
                    posts.created_at DESC';

        // Prepare statement
        $stmt = $this->conn->prepare($query);
        
        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get single post
    public function read_single() {
        $query = 'SELECT
        categories.name AS category_name,
        posts.id,
        posts.category_id,
        posts.title,
        posts.body,
        posts.author,
        posts.created_at
    FROM '. $this->table . ' LEFT JOIN
            categories ON posts.category_id = categories.id
        WHERE posts.id=?
        LIMIT 0,1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->author = $row['author'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
    }

    // Create post
    public function create() {
        // Create query
        $query = 'INSERT INTO ' . $this->table . ' SET title=:title, body=:body, author=:author, category_id=:category_id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Simple data cleaning
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // Bind data
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':category_id', $this->category_id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            // Print error message
            printf("Error: %s\n", $stmt->error);
            return false;
        }
    }

    // Update post
    public function update() {
        // Create query
        $query = 'UPDATE ' . $this->table . ' SET title=:title, body=:body, author=:author, category_id=:category_id WHERE id=:id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Simple data cleaning
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // If error occurs print then print error message
        printf("Error: %s\n", $stmt->error);
        return false;
    }

    // Delete psot
    public function delete() {
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id=?';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Simple data cleaning
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // If error occurs print then print error message
        printf("Error: %s\n", $stmt->error);
        return false;
    }
}