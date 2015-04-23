<?php
date_default_timezone_set('America/Bogota');
$db    = new PDO("mysql:host=localhost;dbname=agendar;charset=utf8" , "root", "");
//$start = $_REQUEST['from'] / 1000;
//$end   = $_REQUEST['to'] / 1000;
//$sql   = sprintf('SELECT * FROM events WHERE `datetime` BETWEEN %s and %s',
//    $db->quote(date('Y-m-d', $start)), $db->quote(date('Y-m-d', $end)));
$rs = $db->query("SELECT c.id, c.start, c.end, p.nome FROM consulta c, paciente p WHERE c.idpaciente = p.id");
$out = array();
while($row = $rs->fetch(PDO::FETCH_OBJ)) {
    $out[] = array(
        'id' => $row->id,
        'title' => $row->nome,
        'url' => '#',
        'start' => strtotime($row->start) . '000',
        'end' => strtotime($row->end) .'000'
    );
}

echo json_encode(array('success' => 1, 'result' => $out));
exit;
?>