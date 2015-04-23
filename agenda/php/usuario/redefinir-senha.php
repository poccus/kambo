<?php 
session_start();

include_once "../conf/config.php";
$oConexao = Conexao::getInstance();

$idusuario						    = strip_tags( $_GET['idusuario'] );
$senhaNova                          = strip_tags( sha1( $_GET['senhaNova'] ) );

try{

    //executa a instrução de consulta 
    $oConexao->beginTransaction();
        
    $stmt = $oConexao->prepare("UPDATE usuario SET senha = ? WHERE idusuario = ?");
    $stmt->bindValue(1, $senhaNova);
    $stmt->bindValue(2, $idusuario);
    $stmt->execute();

    $oConexao->commit();

    $msg['msg']           = 'success';
    $msg['msg_success']   = 'Senha atualizada com sucesso';
    echo json_encode($msg);
    die();
        
    
}catch (PDOException $e){
    $oConexao->rollBack();
    //MENSAGEM DE SUCESSO
    $msg['msg']         = 'error';
    $msg['msg_error']   = 'Error ao tentar efetuar o cadastro. : '.$e->getMessage();
    echo json_encode($msg);
    die();
}

?>