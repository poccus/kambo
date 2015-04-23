<?php 
session_start();
include_once "../../conf/conexao.php";
include_once "../../conf/funcoes.php";
header('Content-type: application/json');
date_default_timezone_set("Brazil/East");	
$id = $_GET['id'];

$slct = $oConexao->query("SELECT * FROM paciente WHERE id = ".$id);
 $contexto = '['; 
while( $row  = $slct->fetch(PDO::FETCH_ASSOC) ){
	$data =  data_volta( $row["datanascimento"] );
	$contexto .= '{"id":"'.$row["id"].'","nome": "'.$row["nome"].'","datanascimento":"'.$data.'","telefone":"'.$row["telefone"].'","email":"'.$row["email"].'"}';
}
$contexto .= "]";   
echo $contexto;
?>