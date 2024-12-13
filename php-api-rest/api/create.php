<?php
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    http_response_code(200);
    exit();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

include_once '../db/Database.php';
include_once '../models/Bookmark.php';

$database = new Database();
$dbConnection = $database->connect();

$bookmark = new Bookmark($dbConnection);

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['title']) || !isset($data['link'])) {
    http_response_code(422);
    echo json_encode(['message' => 'Error: Missing required parameters title and link in the JSON body.']);
    return;
}

$bookmark->setTitle($data['title']);
$bookmark->setLink($data['link']);

if ($bookmark->create()) {
    echo json_encode(['message' => 'A bookmark was created.']);
} else {
    echo json_encode(['message' => 'Error: A bookmark was not created.']);
}
