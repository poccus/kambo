<?php
session_start();

include_once "../../conf/conexao.php";
$msg = array();
header('Content-type: application/json');

$id                     = $_GET['id'];
$data                   = $_GET['data'];

try{
	$stmt = $oConexao->query("SELECT p.nome as nome, p.email as email FROM consulta c, paciente p WHERE c.idpaciente = p.id AND c.idprofissional = ".$id." AND DATE(c.start) = '".$data."'");
	if($stmt->rowCount()>0){
		$tabela = "";
    	$tabela .= '<table width="100%">';
    	$tabela .= '<tr><td>Paciente</td><td>Email</td></tr>';
	 	while($row = $stmt->fetch(PDO::FETCH_OBJ)){
			$tabela .= '<tr><td>'.$row->nome.'</td><td>'.$row->email.'</td></tr>';
		}
		$tabela .= '</table>';
		$msg['msg'] = 'success';
		$msg['resultado'] = $tabela;
		echo json_encode($msg);
		die();
	}else{
		$msg['msg'] = 'nenhum';
		//$msg['resultado'] = $tabela;
		echo json_encode($msg);
		die();
	}
}catch (PDOException $e){

    //MENSAGEM DE SUCESSO
    $msg['msg'] = 'error';
    $msg['exception'] = $e->getMessage();
    echo json_encode($msg);
	die();
}
?>