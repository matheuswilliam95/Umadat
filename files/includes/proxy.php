<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $query = urlencode($_GET['q']);
    $url = "https://nominatim.openstreetmap.org/search?q=$query&limit=5&format=json&addressdetails=1";
    echo file_get_contents($url);
} else {
    echo json_encode([]);
}
?>