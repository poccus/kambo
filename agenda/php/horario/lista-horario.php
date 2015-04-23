<?php 

session_start();


include_once "../../conf/conexao.php";
include_once "../../conf/funcoes.php";
// $oConexao = Conexao::getInstance();

//Definir formato de arquivo
//header('Content-Type:' . "text/plain");
// header("Content-Type: text/html; charset=ISO-8859-1",true);
// header('Content-type: application/json');

$sql = "SELECT id, nome, datanascimento, telefone, email FROM paciente ORDER BY nome ASC";
	
//SQ

$oConexao->beginTransaction();
$result = $oConexao->prepare($sql);
$result->execute();
$oConexao->commit();
$num = $result->rowCount();

$msg = array();
$unidadeArray = array();

if($num > 0){
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
}

?>