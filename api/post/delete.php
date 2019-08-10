<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
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

// Create post
if ($post->delete()) {
    echo json_encode(array('message' => 'Post deleted'));
    http_response_code(200);
    die();
} else {
    echo json_encode(array('message' => 'Post not deleted'));
    http_response_code(400);
    die();
}