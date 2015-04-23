<?php
include_once "../../conf/config.php";
$oConexao = Conexao::getInstance();
?>

<?php
//header("Content-Type: text/html; charset=UTF-8", true);
//header("Content-Type: application/json");
//  session_start();
//  include"conexao.php";
?>

<?php
date_default_timezone_set('America/Bogota');
//$db    = new PDO("mysql:host=localhost;dbname=agendar;charset=utf8" , "root", "");
//$start = $_REQUEST['from'] / 1000;
//$end   = $_REQUEST['to'] / 1000;
//$sql   = sprintf('SELECT * FROM events WHERE `datetime` BETWEEN %s and %s',
//    $db->quote(date('Y-m-d', $start)), $db->quote(date('Y-m-d', $end)));
$profissional = $_GET["profissional"];
//$data = $_GET["data"];
if($profissional == ""){
	/*$rs = $oConexao->query("SELECT c.id, c.start, c.end, p.nome FROM consulta c, paciente p WHERE c.idpaciente = p.id AND c.situacao != 6 AND c.start >= '".$start."' AND c.start <= '".$end."'");
	$out = array();

	while($row = $rs->fetch(PDO::FETCH_OBJ)) {
    	$out[] = array(
        	'id' => $row->id,
        	'title' => $row->nome,
        	'color' => '#444555',
        	'description' => '',
        	'start' => $row->start,
        	'end' => $row->end
    	);
	}

	echo json_encode($out);
	exit;	*/
}else{
    $start = $_GET["start"];
    $end = $_GET["end"];
	$rs = $oConexao->query("SELECT c.id, c.start, c.end, p.nome, c.situacao FROM consulta c, paciente p WHERE c.idpaciente = p.id AND c.situacao != 6 AND c.idprofissional = ".$profissional);
	$out = array();
    $color = "";
	while($row = $rs->fetch(PDO::FETCH_OBJ)) {
        if($row->situacao == "1" || $row->situacao == "2")
            $color = "#3333FF";
        if($row->situacao == "3" || $row->situacao == "4")
            $color = "#606060";
        if($row->situacao == "5")
            $color = "#4C9900";
    	$out[] = array(
        	'id' => $row->id,
        	'title' => $row->nome,
        	'description' => '',
        	'start' => $row->start,
        	'end' => $row->end,
            'color' => $color
    	);
	}

	echo json_encode($out);
	exit;	
}

?>
