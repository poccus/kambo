<?php 

session_start();

include_once "../../conf/conexao.php";

//Definir formato de arquivo
//header('Content-Type:' . "text/plain");
//header("Content-Type: text/html; charset=ISO-8859-1",true);
header('Content-type: application/json');
date_default_timezone_set("Brazil/East");	
$hoje = date("Y-m-d");
$id = $_GET['id'];

$rs = $oConexao->query("SELECT * FROM excecao WHERE idprofissional = ".$id." AND data = '".$hoje."'");
$rw = $rs->fetch(PDO::FETCH_OBJ);
$n = $rs->rowCount();

if($n > 0){
	$sql = "SELECT * FROM horario WHERE idprofissional = ".$id." AND idexcecao = ".$rw->id;
}else{
	$sql = "SELECT * FROM horario WHERE idprofissional = ".$id." AND idexcecao is null";
}


$resultat = $oConexao->query($sql);

 $contexto = '['; 
 $i = 1;
 $total = $resultat->rowCount();
 while($row = $resultat->fetch(PDO::FETCH_OBJ)){
 	$rs = $oConexao->query("SELECT c.id as idconsulta, p.nome as paciente, p.id as idpaciente, c.situacao FROM consulta c, paciente p WHERE c.idpaciente = p.id AND DATE(c.start) = '".$hoje."' AND c.idhorario = '".$row->id."' AND c.situacao != 6 AND c.idprofissional = ".$id);
 	$dados = $rs->fetch(PDO::FETCH_ASSOC);
 	if($rs->rowCount()>0){
 		$idconsulta = $dados["idconsulta"];
 		$paciente = $dados["paciente"];
 		$idpaciente = $dados["idpaciente"];
 		$situacao = $dados["situacao"];
 		if($situacao == 1){
 			//$situacao = "<i class='event glyphicon glyphicon-share btn-warning'></i>";
 			$situacao = "<span class='label label-warning pull-left margin-top-0-3em'>Agendado</span>";
 		}
 		if($situacao == 2){
 			//$situacao = "<i class='event glyphicon glyphicon-cloud btn-warning'></i>";
 			$situacao = "<span class='label label-warning pull-left margin-top-0-3em'>Agendado online</span>";
 		}
 		if($situacao == 3){
 			//$situacao = "<i class='event glyphicon glyphicon-check btn-success'></i>";
 			$situacao = "<span class='label label-success pull-left margin-top-0-3em'>Atendido</span>";
 		}
 		if($situacao == 4){
 			//$situacao = "<i class='event glyphicon glyphicon-ok-circle'></i>";
 			$situacao = "<span class='label label-primary pull-left margin-top-0-3em'>Confirmado</span>";
 		}
 		if($situacao == 5){
 			//$situacao = "<i class='event glyphicon glyphicon-remove btn-danger'></i>";
 			$situacao = "<span class='label label-danger pull-left margin-top-0-3em'>Cancelado</span>";
 		}

 	}else{
 		$idconsulta = "";
 		$idpaciente = "";
 		$paciente = "";
 		$situacao = "";

 	}
 	if($total == $i){
 		$contexto .= '{"id":"'.$idconsulta.'","idpaciente": "'.$idpaciente.'","horario":"'.$row->descricao.'","paciente":"'.$paciente.'","situacao":"'.$situacao.'"}';
 	}else{
 		$contexto .= '{"id":"'.$idconsulta.'","idpaciente": "'.$idpaciente.'","horario":"'.$row->descricao.'","paciente":"'.$paciente.'","situacao":"'.$situacao.'"},';
 	}
 	$i++;
 }
 $contexto .= "]";  
 echo $contexto;

/*$sql = "SELECT h.id AS idhorario, h.descricao AS horario, c.id AS idconsulta, c.situacao, c.obs, p.nome AS paciente
FROM horario h
LEFT JOIN consulta c ON h.id = c.idhorario
INNER JOIN paciente p ON p.id = c.idpaciente
WHERE DATE(c.start) = '".$hoje."' AND h.idmedico = ".$id;
	
//SQL
$result = $oConexao->query($sql);
$num = $result->rowCount();

$msg = array();

if($num > 0){
	$unidadeArray = array();
	// PEGA OS DADOS RETORNADOS	
  	while ( $unidades = $result->fetch(PDO::FETCH_ASSOC) ) {
  		//ADICIONAR OS DADOS NO ARRARY
		$unidadeArray[] = $unidades;
  	}
  	//MOSTRAR DADOS EM FORMATO JSON
	echo json_encode($unidadeArray);

}else{
	$msg['msg'] = 'error';
	echo json_encode($msg);
	die();
}*/

?>