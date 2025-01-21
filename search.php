<?php
// Hata raporlamayı tamamen kapat
error_reporting(0);
ini_set('display_errors', 0);
?>
<?php
$clientId = file_get_contents('/client_id.txt');
if (isset($_POST['search'])) {
    $search = urlencode($_POST['search']);
    $clientcode = $clientId;
    $api_url = 'https://api-v2.soundcloud.com/search/tracks?q=' . $search . '&facet=genre&limit=50&client_id=' . $clientcode . '&app_version=d8c55ad';
    $response = file_get_contents($api_url);
    $tracks = json_decode($response)->collection;

    $result = [];
    foreach ($tracks as $track) {
        $transcoding_url = $track->media->transcodings[1]->url ?? null; 
        $stream_url = $transcoding_url ? getStreamUrl($transcoding_url, $clientcode) : null;
        if ($stream_url) {
            $result[] = [
                'title' => htmlspecialchars($track->title),
                'artwork_url' => $track->artwork_url ?? 'default.jpg',
                'stream_url' => $stream_url,
                'id' => $track->id
            ];
        }
    }
    echo json_encode($result);
}
function getStreamUrl($transcoding_url, $client_id)
{
    $stream_response = file_get_contents($transcoding_url . '?client_id=' . $client_id);
    $stream_data = json_decode($stream_response);
    return $stream_data->url ?? null;
}
if (isset($_POST['track_ids'])) {
    $track_ids = json_decode($_POST['track_ids']);
    $results = [];

    foreach ($track_ids as $trackId) {
        $clientcode = $clientId;
        $api_url = 'https://api-v2.soundcloud.com/tracks/' . $trackId . '?client_id=' . $clientcode;
        $response = file_get_contents($api_url);
        $track = json_decode($response);

        if (isset($track->id)) {
            $transcoding_url = $track->media->transcodings[1]->url ?? null;
            $stream_url = $transcoding_url ? getStreamUrl($transcoding_url, $clientcode) : null;

            if ($stream_url) {
                $result = [
                    'id' => $track->id, // ID'yi ekleyin
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
