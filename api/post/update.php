<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Origin, Authorization, X-Requested-With');

require_once '../../config/Database.php';
require_once '../../models/Post.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate post object
$post = new Post($db);

// Get raw posted data
$data = file_get_contents('php://input');
$data = json_decode($data, true);

// Set ID to update
$post->id = $data['id'];

$post->title = $data['title'];
$post->body = $data['body'];
$post->author = $data['author'];
$post->category_id = $data['category_id'];

// Create post
if ($post->update()) {
    echo json_encode(array('message' => 'Post updated'));
    http_response_code(201);
    die();
} else {
    echo json_encode(array('message' => 'Post not updated'));
    http_response_code(400);
    die();
}