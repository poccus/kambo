<?php 
session_start();
include_once "../../conf/config.php";
include_once "../../utils/funcoes.php";
$oConexao = Conexao::getInstance();

//Definir formato de arquivo
//header('Content-Type:' . "text/plain");
//header("Content-Type: text/html; charset=ISO-8859-1",true);
header('Content-type: application/json');
date_default_timezone_set("Brazil/East");	
$hoje = date("Y-m-d");
$id = $_GET['id'];
$sql = "SELECT c.id as idconsulta, p.nome as paciente FROM consulta c, paciente p WHERE c.idpaciente = p.id AND DATE(c.start) = '".$hoje."' AND c.situacao = 4 AND c.idprofissional = ".$id;
$resultat = $oConexao->query($sql);

$total = $resultat->rowCount();
if($total > 0){
 	$contexto = '['; 
 	$i = 1;
 
 	while($row = $resultat->fetch(PDO::FETCH_OBJ)){
 		if($total == $i){
 			$contexto .= '{"id":"'.$row->idconsulta.'","paciente":"'.$row->paciente.'"}';
 		}else{
 			$contexto .= '{"id":"'.$row->idconsulta.'","paciente":"'.$row->paciente.'"},';
 		}
 		$i++;
 	}
 	$contexto .= "]";  
 }else{
 	$contexto = "null";
 	//echo $contexto;
 }
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