<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://nominatim.openstreetmap.org/search?q=São%20Paulo&limit=1&format=json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, "MeuApp/1.0 (seuemail@dominio.com)");

$response = curl_exec($ch);
$error = curl_error($ch);

curl_close($ch);

if ($response) {
    echo "Conexão bem-sucedida! Resposta da API:<br><br>";
    echo $response;
} else {
    echo "Erro ao conectar: $error";
}
?>
