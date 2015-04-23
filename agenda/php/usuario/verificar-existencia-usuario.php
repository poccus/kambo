<?php 
session_start();

include_once "../../conf/conexao.php";
include_once "../../utils/funcoes.php";
$oConexao = Conexao::getInstance();

$login						= strip_tags($_POST['login']);

try{

    //executa a instrução de consulta 
    $oConexao->beginTransaction();
    
    $result = $oConexao->query("SELECT * FROM usuario WHERE (login = '$login' OR email = '$login') AND liberado < 3");
    $result->execute();
    $rows = $result->fetchAll();
    $rowCount = $result->rowCount();

    //MENSAGEM DE SUCESSO
    $msg['msg'] = 'success';
    $msg['loginexistente'] = $rowCount;

    echo json_encode($msg);

}catch (PDOException $e){
    $oConexao->rollBack();
    //MENSAGEM DE ERRO
    $msg['msg'] = 'error';
    $msg['msg_error'] = $e->getMessage();
    echo json_encode($msg);
	die();
}

?>