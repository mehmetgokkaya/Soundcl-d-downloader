<?php
// SoundCloud sayfasının URL'si
$url = 'https://soundcloud.com';

// Sayfanın içeriğini al
$html = file_get_contents($url);

// JavaScript dosyalarını bul
preg_match_all('/https:\/\/a-v2\.sndcdn\.com\/assets\/[^\s]+\.js/', $html, $matches);

// İlk client_id değerini saklamak için bir değişken
$client_id = null;

// Her bir JavaScript dosyasını kontrol et
foreach ($matches[0] as $js_url) {
    // JavaScript dosyasını indir
    $js_content = file_get_contents($js_url);
    
    // client_id değerini bul
    if (preg_match('/client_id\s*:\s*["\']([^"\']+)["\']/', $js_content, $client_id_match)) {
        $client_id = $client_id_match[1]; // İlk bulunan client_id'yi al
        break; // İlk bulduğumuzda döngüden çık
    }
}

// Eğer client_id bulunduysa, bir metin dosyasına yaz
if ($client_id) {
    // Dosyadaki mevcut veriyi silip yeni client_id'yi yaz
    file_put_contents('client_id.txt', $client_id);
    echo "Yeni client_id yazıldı: $client_id";
} else {
    echo "client_id bulunamadı.";
}
?>
