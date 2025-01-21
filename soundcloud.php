<?php
$client_id = file_get_contents('/client_id.txt');
$api_url = "https://api-v2.soundcloud.com/mixed-selections";
$limit = 10;
$offset = 0;

// API isteğini gönder
$response = file_get_contents("$api_url?client_id=$client_id&limit=$limit&offset=$offset");
$data = json_decode($response, true);

$playlists = $data['collection'] ?? [];

header('Content-Type: application/json');
echo json_encode($playlists);
?>
