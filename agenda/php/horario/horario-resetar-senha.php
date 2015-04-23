<?php 

session_start();

include_once "../../conf/conexao.php";
include_once "../../conf/funcoes.php";
// $oConexao = Conexao::getInstance();

try{

    //executa a instrução de consulta 
    $oConexao->beginTransaction();
    
    if( isset($_POST['id']) ){

        $idUsuario    = strip_tags($_POST['id']);

        $stmt = $oConexao->prepare("UPDATE usuario SET senha = ? WHERE id = ?");
        $stmt->bindValue(1, sha1('specialites'));
        $stmt->bindValue(2, $idUsuario);
        $stmt->execute();
        
        $oConexao->commit();

        echo "As informações foram alteradas com sucesso.";
        die();

    }

    //APAGAR DADOS DE FORMA MULTIPLAS
    $contagem = count( isset( $_POST['itemselecionado']) );

    if( isset( $_POST['itemselecionado'] )  && $contagem > 0 ){

        foreach ( $_POST['itemselecionado'] as $item ) {
            $stmt = $oConexao->prepare("UPDATE usuario SET senha = ? WHERE id = ?");
            $stmt->bindValue(1, sha1('specialites'));
            $stmt->bindValue(2, $item);
            $stmt->execute();
        }

        //CONFIRMAR OS DELETES
        $oConexao->commit();

        echo "As senhas foram reiniciadas com sucesso.";
        die();
    }
    
}catch (PDOException $e){
    $oConexao->rollBack();
    echo $e->getMessage();
    echo "Erro grave ao reiniciar as senhas dos usuário, consulte o administrador.&nbsp; <a href='../../view/usuario/index.php'>voltar para lista</a>";
	die();
}

?>