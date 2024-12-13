<?php
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    http_response_code(200);
    exit();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');

include_once '../db/Database.php';
include_once '../models/Bookmark.php';

$database = new Database();
$dbConnection = $database->connect();

$bookmark = new Bookmark($dbConnection);

$data = json_decode(file_get_contents("php://input"));

if (!$data || !isset($data->id)) {
    http_response_code(422);
    echo json_encode(['message' => 'Error: Missing required parameter id in the JSON body.']);
    return;
}

$bookmark->setId($data->id);

if ($bookmark->delete()) {
    echo json_encode(['message' => 'A bookmark was deleted.']);
} else {
    echo json_encode(['message' => 'Error: A bookmark was not deleted.']);
}
