<?php 
session_start();

include_once "../conf/config.php";
include_once "../utils/funcoes.php";
$oConexao = Conexao::getInstance();

try{

    //executa a instrução de consulta 
    $oConexao->beginTransaction();
    
    //ATIVAR DADOS DE FORMA MULTIPLAS
    $contagem = count( isset( $_POST['itemselecionado']) );

    if( isset( $_POST['itemselecionado'] )  && $contagem > 0 ){

        foreach ( $_POST['itemselecionado'] as $item ) {
            $stmt = $oConexao->prepare("UPDATE usuario SET liberado = 1 WHERE idusuario = ?");
            $stmt->bindValue(1, $item);
            $stmt->execute();
        }

        //CONFIRMAR OS DELETES
        $oConexao->commit();

        echo "As informações foram ativadas com sucesso.";
        die();
    }
    
}catch (PDOException $e){
    $oConexao->rollBack();
    echo $e->getMessage();
    echo "Erro grave ao ativar o usuário, consulte o administrador.&nbsp; <a href='".PORTAL_URL."usuario/index'>voltar para lista</a>";
	die();
}

?>