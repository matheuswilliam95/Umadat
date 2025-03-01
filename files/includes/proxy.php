<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $query = urlencode($_GET['q']);
    $url = "https://nominatim.openstreetmap.org/search?q=$query&limit=5&format=json&addressdetails=1";

    // Inicializa cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desativa verificação SSL
    curl_setopt($ch, CURLOPT_USERAGENT, "MeuApp/1.0 (seuemail@dominio.com)");

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($http_code != 200 || $response === false) {
        echo json_encode(["error" => "Erro ao acessar a API"]);
    } else {
        echo $response;
    }

    curl_close($ch);
} else {
    echo json_encode(["error" => "Parâmetro 'q' ausente"]);
}
?>