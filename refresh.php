<?php
// Hata raporlamayı tamamen kapat
error_reporting(0);
ini_set('display_errors', 0);
?>
<?php
$clientId = file_get_contents('/www/wwwroot/dinle.mehmetgokkaya.com/client_id.txt');

if (isset($_POST['track_ids'])) {
    $track_ids = json_decode($_POST['track_ids']);
    $results = [];

    // Track ID'lerini virgülle birleştir
    $ids = implode(',', $track_ids);
    $api_url = 'https://api-v2.soundcloud.com/tracks?ids=' . $ids . '&client_id=' . $clientId;
    $response = file_get_contents($api_url);
    $tracks = json_decode($response);

    foreach ($tracks as $track) {
        if (isset($track->id)) {
            $transcoding_url = $track->media->transcodings[1]->url ?? null;
            $stream_url = $transcoding_url ? getStreamUrl($transcoding_url, $clientId) : null;

            if ($stream_url) {
                $result = [
                    'id' => $track->id,
                    'title' => htmlspecialchars($track->title),
                    'artwork_url' => $track->artwork_url ?? 'default.jpg',
                    'stream_url' => $stream_url
                ];
                $results[] = $result;
            }
        }
    }

    echo json_encode($results);
}

function getStreamUrl($transcoding_url, $client_id) {
    $stream_response = file_get_contents($transcoding_url . '?client_id=' . $client_id);
    $stream_data = json_decode($stream_response);
    return $stream_data->url ?? null;
}
?>