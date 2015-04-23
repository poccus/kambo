<?php 
session_start();

include_once "../conf/config.php";
include_once "../utils/funcoes.php";
$oConexao = Conexao::getInstance();

try{

    //executa a instrução de consulta 
    $oConexao->beginTransaction();
    
    if( isset($_POST['id']) ){

        $idusuario   = strip_tags($_POST['id']);

        $stmt = $oConexao->prepare("UPDATE usuario SET liberado = 2 WHERE idusuario = ?");
        $stmt->bindValue(1, $idusuario);
        $stmt->execute();
        
        $oConexao->commit();

        echo "As informações foram inativadas com sucesso.";
        die();

    }

    //APAGAR DADOS DE FORMA MULTIPLAS
    $contagem = count( isset( $_POST['itemselecionado']) );

    if( isset( $_POST['itemselecionado'] )  && $contagem > 0 ){

        foreach ( $_POST['itemselecionado'] as $item ) {
            $stmt = $oConexao->prepare("UPDATE usuario SET liberado = 2 WHERE idusuario = ?");
            $stmt->bindValue(1, $item);
            $stmt->execute();
        }

        //CONFIRMAR OS DELETES
        $oConexao->commit();

        echo "As informações foram inativadas com sucesso.";
        die();
    }
    
}catch (PDOException $e){
    $oConexao->rollBack();
    echo $e->getMessage();
    echo "Erro grave ao inativar o usuário, consulte o administrador.&nbsp; <a href='".PORTAL_URL."usuario/index'>voltar para lista</a>";
	die();
}

?>