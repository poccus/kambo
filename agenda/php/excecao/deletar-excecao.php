<?php 
session_start();

include_once "../../conf/conexao.php";
include_once "../../utils/funcoes.php";
$oConexao = Conexao::getInstance();

try{

    //executa a instrução de consulta 
    $oConexao->beginTransaction();

    //APAGAR DADOS DE FORMA MULTIPLAS
    $contagem = count( isset( $_POST['itemselecionado']) );

    if( isset( $_POST['id'] ) ){

        $idexcecao = $_POST['id'];

        $stmt = $oConexao->prepare("DELETE FROM excecao WHERE id = ?");
        $stmt->bindValue(1, $idexcecao);
        $stmt->execute();

        //CONFIRMAR OS DELETES
        $oConexao->commit();
        
        $msg['msg'] = 'success';
        echo json_encode($msg);
        die();
        
    }


    if( isset( $_POST['itemselecionado'] )  && $contagem > 0 ){

        foreach ( $_POST['itemselecionado'] as $item ) {

            $stmt = $oConexao->prepare("DELETE FROM excecao WHERE id = ?");
            $stmt->bindValue(1, $item);
            $stmt->execute();

        }

        //CONFIRMAR OS DELETES
        $oConexao->commit();

        $msg['msg'] = 'success';
        echo json_encode($msg);
        die();

    }
    
}catch (PDOException $e){
    $oConexao->rollBack();
    //mensagem de erro
    $msg['msg'] = 'error';
    $msg['error'] = $e->getMessage();
    echo json_encode($msg);
    exit();
}

?>