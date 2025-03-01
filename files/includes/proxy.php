<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $query = urlencode($_GET['q']);
    $url = "https://nominatim.openstreetmap.org/search?q=$query&limit=5&format=json&addressdetails=1";

    // Adicionando User-Agent para evitar bloqueio
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: MeuApp/1.0 (seuemail@dominio.com)\r\n"
        ]
    ];

    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);

    if ($response === FALSE) {
        echo json_encode(["error" => "Erro na requisição à API"]);
    } else {
        echo $response;
    }
} else {
    echo json_encode(["error" => "Parâmetro 'q' ausente"]);
}
?>