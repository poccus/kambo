<?php 
session_start();

include_once "../../conf/conexao.php";
include_once "../../utils/funcoes.php";
$oConexao = Conexao::getInstance();

try{

    //executa a instrução de consulta 
    $oConexao->beginTransaction();
    
    if( isset($_POST['id']) ){

        $idusuario    = strip_tags($_POST['id']);

        //excluir o usuário do sistema
        // 3 - excluido do sitema
        $stmt = $oConexao->prepare("UPDATE usuario SET liberado = 3 WHERE idusuario = ?");
        $stmt->bindValue(1, $idusuario);
        $stmt->execute();

        $oConexao->commit();

        //MENSAGEM DE SUCESSO
        $msg['msg']         = 'success';
        $msg['msg_success'] = 'As informações foram deletadas com sucesso';
        echo json_encode($msg);
        die();

    }

    //APAGAR DADOS DE FORMA MULTIPLAS
    $contagem       = count( isset( $_POST['itemselecionado']) );
    $totalexcluido  = 0; 
    if( isset( $_POST['itemselecionado'] )  && $contagem > 0 ){

        foreach ( $_POST['itemselecionado'] as $item ) {

            //excluir o usuário do sistema
            // 3 - excluido do sitema
            $stmt = $oConexao->prepare("UPDATE usuario SET liberado = 3 WHERE idusuario = ?");
            $stmt->bindValue(1, $item);
            $stmt->execute();

            $totalexcluido++;
            
        }

        //CONFIRMAR OS DELETES
        $oConexao->commit();

        if( $totalexcluido >= 1 ){
            //MENSAGEM DE SUCESSO
            $msg['msg']         = 'success';
            $msg['msg_success'] = 'As informações foram deletadas com sucesso';
            echo json_encode($msg);
            die();
        }

    }
    
}catch (PDOException $e){
    $oConexao->rollBack();
    //MENSAGEM DE SUCESSO
    $msg['msg']         = 'error';
    $msg['error']   = 'Error ao tentar efetuar a operação. : '.$e->getMessage();
    echo json_encode($msg);
    die();
}

?>