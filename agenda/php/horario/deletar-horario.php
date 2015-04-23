<?php 

session_start();

include_once "../../conf/conexao.php";
include_once "../../conf/funcoes.php";
// $oConexao = Conexao::getInstance();

try{

    //executa a instrução de consulta 
    $oConexao->beginTransaction();
    
    // if( isset($_POST['id']) ){

    //     $idcategoria    = strip_tags($_POST['id']);

    //     $stmt = $oConexao->prepare("UPDATE usuario SET status = 0 WHERE id = ?");
    //     $stmt->bindValue(1, $idcategoria);
    //     $stmt->execute();
        
    //     $oConexao->commit();

    //     echo "As informações foram inativadas com sucesso.";
    //     die();

    // }

    //APAGAR DADOS DE FORMA MULTIPLAS
    $contagem = count( isset( $_POST['itemselecionado']) );

    $pacienteNaoDeletado = 0;

    if( isset( $_POST['itemselecionado'] )  && $contagem > 0 ){

        foreach ( $_POST['itemselecionado'] as $item ) {

            $result = $oConexao->prepare("SELECT DISTINCT(idpaciente) AS idpaciente FROM consulta WHERE idpaciente = ?");
            $result->bindValue(1, $item);
            $result->execute();
            $num = $result->rowCount();

            if (!($num > 0)) {
                $stmt = $oConexao->prepare("DELETE FROM paciente WHERE id = ?");
                $stmt->bindValue(1, $item);
                $stmt->execute();
            }else{
                $pacienteNaoDeletado ++;
            }

        }

        //CONFIRMAR OS DELETES
        $oConexao->commit();

        if ($pacienteNaoDeletado > 0) {
            echo "0//As informações não foram deletadas, pois existe registro de consulta do paciente.";
        }else{
            echo "1//As informações foram deletadas com sucesso.";
        }

        die();
    }
    
}catch (PDOException $e){
    $oConexao->rollBack();
    echo $e->getMessage();
    echo "Erro grave ao deletar o paciente, consulte o administrador.&nbsp; <a href='../../view/paciente/index.php'>voltar para lista</a>";
	die();
}

?>