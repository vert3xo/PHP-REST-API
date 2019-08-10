<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
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

$post->title = $data['title'];
$post->body = $data['body'];
$post->author = $data['author'];
$post->category_id = $data['category_id'];

// Create post
if ($post->create()) {
    echo json_encode(array('message' => 'Post created'));
    http_response_code(201);
    die();
} else {
    echo json_encode(array('message' => 'Post not created'));
    http_response_code(400);
    die();
}