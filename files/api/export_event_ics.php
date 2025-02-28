<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID do evento inválido.");
}

$eventId = intval($_GET['id']);
$evento = getEventDetails($eventId);

if (!$evento) {
    die("Evento não encontrado.");
}

// Monta os horários de início e fim para o formato ICS (UTC)
// Se existir horário definido, combina data e horário; caso contrário, utiliza data apenas
if (!empty($evento['horario_inicio'])) {
    $startDateTime = date('Ymd\THis\Z', strtotime($evento['data_inicio'] . ' ' . $evento['horario_inicio']));
} else {
    $startDateTime = date('Ymd\THis\Z', strtotime($evento['data_inicio']));
}

if (!empty($evento['horario_fim'])) {
    $endDateTime = date('Ymd\THis\Z', strtotime($evento['data_fim'] . ' ' . $evento['horario_fim']));
} else {
    $endDateTime = date('Ymd\THis\Z', strtotime($evento['data_fim']));
}

$summary = $evento['titulo'];
$description = $evento['descricao'];
$location = isset($evento['local']) ? $evento['local'] : '';
$uid = md5($evento['id'] . $evento['data_inicio'] . $evento['titulo']) . '@' . $_SERVER['HTTP_HOST'];
$dtstamp = date('Ymd\THis\Z');

// Cria o conteúdo do arquivo ICS
$ics = "BEGIN:VCALENDAR\r\n";
$ics .= "VERSION:2.0\r\n";
$ics .= "PRODID:-//Umadat//Evento//EN\r\n";
$ics .= "CALSCALE:GREGORIAN\r\n";
$ics .= "BEGIN:VEVENT\r\n";
$ics .= "UID:" . $uid . "\r\n";
$ics .= "DTSTAMP:" . $dtstamp . "\r\n";
$ics .= "DTSTART:" . $startDateTime . "\r\n";
$ics .= "DTEND:" . $endDateTime . "\r\n";
$ics .= "SUMMARY:" . addslashes($summary) . "\r\n";
$ics .= "DESCRIPTION:" . addslashes($description) . "\r\n";
if (!empty($location)) {
    $ics .= "LOCATION:" . addslashes($location) . "\r\n";
}
$ics .= "END:VEVENT\r\n";
$ics .= "END:VCALENDAR\r\n";

// Define os cabeçalhos para o download do arquivo ICS
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=evento_' . $eventId . '.ics');
echo $ics;
exit;
?>