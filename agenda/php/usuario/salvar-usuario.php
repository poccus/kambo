<?php 

session_start();

include_once "../../conf/conexao.php";
include_once "../../utils/funcoes.php";
$oConexao = Conexao::getInstance();

//session usuario
$idusuario                          = $_SESSION['usuario'];

$usuario_nome                       = strip_tags( $_POST['usuario_nome'] );
$usuario_receberemail               = isset( $_POST['usuario_receberemail'] ) ? $_POST['usuario_receberemail'] : 0;
$usuario_liberado                   = isset( $_POST['usuario_liberado'] ) ? $_POST['usuario_liberado'] : 0;
$usuario_celular                    = strip_tags( $_POST['usuario_celular'] );
$usuario_login                      = strip_tags( $_POST['usuario_login'] );
$usuario_email                      = strip_tags( $_POST['usuario_email'] );
$usuario_senha                      = strip_tags( sha1( $_POST['usuario_senha'] ) );
$usuario_perfil                     = $_POST['usuario_perfil'];
$usuario_perfilprofissional         = $_POST['usuario_perfilprofissional']; //o valor será atribuido, caso o perfil do usuário seja igual 2 - Profissional
$usuario_profissional               = $_POST['usuario_profissional']; //o valor será atribuido, caso o perfil do usuário seja igual 3 - Recepcionista

$foto                               = ''; //VAZIO
$pasta                              = "upload/user/";

try{

    //executa a instrução de consulta 
    $oConexao->beginTransaction();
    
    if( isset($_POST['idusuario']) && $_POST['idusuario'] != '' ){

        $iduser    = $_POST['idusuario'];

        //VERIFICAR A SENHA
        $result = $oConexao->query("SELECT * FROM usuario WHERE idusuario = '$iduser'");
        $dadosUsuario = $result->fetch(PDO::FETCH_ASSOC);

        $online  = $dadosUsuario['online'];

        // VERIFICO SE EXISTE O CAMPO DE FOTO, CASO EXISTA ELE FAZ O UPLOAD DA IMAGEM E FAZ A INSERCAO NO BANNCO
        if(isset($_FILES["foto"])){
            //VERIFICAR A FOTO E UPLOAD
            $foto_arq           = $_FILES["foto"];
            if ($_FILES["foto"]["error"] > 0){
                //MENSAGEM DE ERROR DE UPLOAD
                $msg['msg']         = 'upload';
                $msg['error'] = 'Error ao tentar fazer o upload da imagem, tente novamento mais tarde, erro: '.$foto_arq["error"];
                echo json_encode($msg);
                die();
            }else{
                //VERIFICA SE É UMA IMAGEM
                if(!preg_match("/^image\/(pjpeg|jpeg|png)$/", $foto_arq["type"])){
                    //MENSAGEM DE ERROR DE UPLOAD
                    $msg['msg']         = 'upload';
                    $msg['error'] = 'Por favor, selecione um arquivo de formato válido ou verifique o tamanho de arquivo, tamanho máximo de 2MB';
                    echo json_encode($msg);
                    die();
                }else{
                    // Pega extensão da imagem 
                    preg_match("/\.(png|jpg|jpeg){1}$/i", $foto_arq["name"], $ext_arq);
                    // Gera um nome único para a imagem 
                    $foto = md5(uniqid(time())).".".$ext_arq[1]; 
                    //Move arquivo para a pasta informada
                    move_uploaded_file($foto_arq["tmp_name"],$pasta.$foto);
                    //UPDATE DOS DADOS
                    $stmt = $oConexao->prepare("UPDATE usuario SET foto = ? WHERE idusuario = ?");
                    $stmt->bindValue(1, $foto);
                    $stmt->bindValue(2, $iduser);
                    $stmt->execute();
                }
            }
        }

        $stmt = $oConexao->prepare("UPDATE usuario SET nome = ?, login = ?, email = ?, celular = ?, liberado = ?, perfil = ?, receber_email = ? WHERE idusuario = ?");
        $stmt->bindValue(1, $usuario_nome);
        $stmt->bindValue(2, $usuario_login);
        $stmt->bindValue(3, $usuario_email);
        $stmt->bindValue(4, $usuario_celular);
        $stmt->bindValue(5, $usuario_liberado);
        $stmt->bindValue(6, $usuario_perfil);
        $stmt->bindValue(7, $usuario_receberemail);
        $stmt->bindValue(8, $iduser);
        $stmt->execute();

        $stmtup = $oConexao->prepare("DELETE FROM usuario_profissional WHERE idusuario = ?");
        $stmtup->bindValue(1, $iduser);
        $stmtup->execute();

        //adicionar usuario_profissional, caso o perfil do usuário seja igual 2 - Profissional
        if( $usuario_perfil == 2 ){
            $stmtup = $oConexao->prepare("INSERT INTO usuario_profissional (idusuario, idprofissional) VALUES (?, ?)");
            $stmtup->bindValue(1, $iduser);
            $stmtup->bindValue(2, $usuario_perfilprofissional);
            $stmtup->execute();
        }

        //adicionar usuario_profissional, caso o perfil do usuário seja igual 3 - Recepcionista
        if( $usuario_perfil == 3 ){
            foreach ( $_POST['usuario_profissional_id'] as $item ) {
                if( $item != 0 || $item != null ){
                    $stmtup = $oConexao->prepare("INSERT INTO usuario_profissional (idusuario, idprofissional) VALUES (?, ?)");
                    $stmtup->bindValue(1, $iduser);
                    $stmtup->bindValue(2, $item);
                    $stmtup->execute();
                }
            }
        }

        //DELETAR TODOS OS SUBMODULOS
        $rsDeleteSubModulo = $oConexao->prepare("DELETE FROM modulo_submodulo WHERE idusuario = ?");
        $rsDeleteSubModulo->bindValue(1, $iduser);
        $rsDeleteSubModulo->execute();

        $modid = null;
        //DELETAR TODOS OS MODULOS QUE NÃO SERAM UTILIZADO PELO O USUÁRIO
        foreach ( $_POST['submodulos'] as $item ) {
            $modid          = explode(',', $item);
        }
        $modid          = implode(',', $modid);
        //DELETAR TODOS OS MODULOS QUE NÃO SERAM UTILIZADO PELO O USUÁRIO
        $rsDeleteModulo = $oConexao->query("DELETE FROM modulo_usuario WHERE idusuario = $iduser AND idmodulo NOT IN($modid)");
        // $rsDeleteModulo->bindValue(1, $iduser);
        // $rsDeleteModulo->execute();

        // INSERIR OS MODULOS E SUBMODULOS
        foreach ( $_POST['submodulos'] as $item ) {
            $i          = explode(',', $item);
            $modulo     = $i[0];
            $submodulo  = $i[1];

            $rsSelectModulo = $oConexao->prepare("SELECT count(idmodulo_usuario) total FROM modulo_usuario WHERE idusuario = ? AND idmodulo = ?");
            $rsSelectModulo->bindValue(1, $iduser);
            $rsSelectModulo->bindValue(2, $modulo);
            $rsSelectModulo->execute();
            $totalModulo = $rsSelectModulo->fetch( PDO::FETCH_OBJ )->total;
            if( $totalModulo >= 1 ){
                //UPDATE O MODULO
                $stmt = $oConexao->prepare("UPDATE modulo_usuario SET idmodulo = ?, idusuario = ? WHERE idmodulo = ? AND idusuario = ?");
                $stmt->bindValue(1, $modulo);
                $stmt->bindValue(2, $iduser);
                $stmt->bindValue(3, $modulo);
                $stmt->bindValue(4, $iduser);
                $stmt->execute();
            }else{
                //INSERIR O MODULO
                $stmt = $oConexao->prepare("INSERT INTO modulo_usuario VALUES(NULL, ?, ?)");
                $stmt->bindValue(1, $modulo);
                $stmt->bindValue(2, $iduser);
                $stmt->execute();
            }
            //INSERIR O SUBMODULO
            $stmt = $oConexao->prepare("INSERT INTO modulo_submodulo VALUES(NULL, ?, ?, ?)");
            $stmt->bindValue(1, $modulo);
            $stmt->bindValue(2, $submodulo);
            $stmt->bindValue(3, $iduser);
            $stmt->execute();

        }

        //DELETAR TODOS OS SUBMODULOS_ACAO QUE NÃO SERAM UTILIZADO PELO O USUÁRIO
        $rsDeleteSubModuloAcao = $oConexao->query("DELETE FROM submodulo_acao WHERE idusuario = $iduser");

        // ADICIONAR AS AÇÕES DO SUBMODULO/ACAO
        foreach ( $_POST['submodulos_acao'] as $item ) {
            $i          = explode(',', $item);
            $acao       = $i[0];
            $submodulo  = $i[1];

            //INSERIR O MODULO
            $stmt = $oConexao->prepare("INSERT INTO submodulo_acao VALUES(NULL, ?, ?, ?)");
            $stmt->bindValue(1, $acao);
            $stmt->bindValue(2, $submodulo);
            $stmt->bindValue(3, $iduser);
            $stmt->execute();

        }

        
        $oConexao->commit();

        //MENSAGEM DE SUCESSO
        $msg['msg']         = 'success';
        $msg['msg_success'] = 'Cadastro efetuado com sucesso.';
        echo json_encode($msg);
        die();

    }else{

        // VERIFICO SE EXISTE O CAMPO DE FOTO, CASO EXISTA ELE FAZ O UPLOAD DA IMAGEM E FAZ A INSERCAO NO BANNCO
        if(isset($_FILES["usuario_foto"])){
            //VERIFICAR A FOTO E UPLOAD
            $foto_arq           = $_FILES["usuario_foto"];
            if ($_FILES["usuario_foto"]["error"] > 0){
                //MENSAGEM DE ERROR DE UPLOAD
                $msg['msg']         = 'upload';
                $msg['error'] = 'Error ao tentar fazer o upload da imagem, tente novamento mais tarde, erro: '.$foto_arq["error"];
                echo json_encode($msg);
                die();
            }else{
                //VERIFICA SE É UMA IMAGEM
                if(!preg_match("/^image\/(pjpeg|jpeg|png)$/", $foto_arq["type"])){
                    //MENSAGEM DE ERROR DE UPLOAD
                    $msg['msg']         = 'upload';
                    $msg['error'] = 'Por favor, selecione um arquivo de formato válido ou verifique o tamanho de arquivo, tamanho máximo de 2MB';
                    echo json_encode($msg);
                    die();
                }else{
                    // Pega extensão da imagem 
                    preg_match("/\.(png|jpg|jpeg){1}$/i", $foto_arq["name"], $ext_arq);
                    // Gera um nome único para a imagem 
                    $foto = md5(uniqid(time())).".".$ext_arq[1]; 
                    //Move arquivo para a pasta informada
                    move_uploaded_file($foto_arq["tmp_name"],$pasta.$foto);
                }
            }
        }    

        $stmt = $oConexao->prepare("INSERT INTO usuario (nome, login, email, senha, celular, liberado, perfil, online, foto, receber_email, datacadastro) VALUES (?, ?, ?, ?, ?, ?, ?, 2, ?, ?, now())");
        $stmt->bindValue(1, $usuario_nome);
        $stmt->bindValue(2, $usuario_login);
        $stmt->bindValue(3, $usuario_email);
        $stmt->bindValue(4, $usuario_senha);
        $stmt->bindValue(5, $usuario_celular);
        $stmt->bindValue(6, $usuario_liberado);
        $stmt->bindValue(7, $usuario_perfil);
        $stmt->bindValue(8, $foto);
        $stmt->bindValue(9, $usuario_receberemail);
        $stmt->execute();
        //RETORNAR O ID DO USUARIO
        $iduser = $oConexao->lastInsertId();

        //adicionar usuario_profissional, caso o perfil do usuário seja igual 2 - Profissional
        if( $usuario_perfil == 2 ){
            $stmtup = $oConexao->prepare("INSERT INTO usuario_profissional (idusuario, idprofissional) VALUES (?, ?)");
            $stmtup->bindValue(1, $iduser);
            $stmtup->bindValue(2, $usuario_perfilprofissional);
            $stmtup->execute();
        }

        //adicionar usuario_profissional, caso o perfil do usuário seja igual 3 - Recepcionista
        if( $usuario_perfil == 3 ){
            foreach ( $_POST['usuario_profissional_id'] as $item ) {
                if( $item != 0 || $item != null ){
                    $stmtup = $oConexao->prepare("INSERT INTO usuario_profissional (idusuario, idprofissional) VALUES (?, ?)");
                    $stmtup->bindValue(1, $iduser);
                    $stmtup->bindValue(2, $item);
                    $stmtup->execute();
                }
            }
        }

        //DELETAR TODOS OS SUBMODULOS
        $rsDeleteSubModulo = $oConexao->prepare("DELETE FROM modulo_submodulo WHERE idusuario = ?");
        $rsDeleteSubModulo->bindValue(1, $iduser);
        $rsDeleteSubModulo->execute();

        // INSERIR OS MODULOS E SUBMODULOS
        foreach ( $_POST['submodulos'] as $item ) {
            $i          = explode(',', $item);
            $modulo     = $i[0];
            $submodulo  = $i[1];

            $rsSelectModulo = $oConexao->prepare("SELECT count(idmodulo_usuario) total FROM modulo_usuario WHERE idusuario = ? AND idmodulo = ?");
            $rsSelectModulo->bindValue(1, $iduser);
            $rsSelectModulo->bindValue(2, $modulo);
            $rsSelectModulo->execute();
            $totalModulo = $rsSelectModulo->fetch( PDO::FETCH_OBJ )->total;
            if( $totalModulo >= 1 ){
                //UPDATE O MODULO
                $stmt = $oConexao->prepare("UPDATE modulo_usuario SET idmodulo = ?, idusuario = ? WHERE idmodulo = ? AND idusuario = ?");
                $stmt->bindValue(1, $modulo);
                $stmt->bindValue(2, $iduser);
                $stmt->bindValue(3, $modulo);
                $stmt->bindValue(4, $iduser);
                $stmt->execute();
            }else{
                //INSERIR O MODULO
                $stmt = $oConexao->prepare("INSERT INTO modulo_usuario VALUES(NULL, ?, ?)");
                $stmt->bindValue(1, $modulo);
                $stmt->bindValue(2, $iduser);
                $stmt->execute();
            }
            //INSERIR O SUBMODULO
            $stmt = $oConexao->prepare("INSERT INTO modulo_submodulo VALUES(NULL, ?, ?, ?)");
            $stmt->bindValue(1, $modulo);
            $stmt->bindValue(2, $submodulo);
            $stmt->bindValue(3, $iduser);
            $stmt->execute();

        }//END FOREACH


        //ADICIONAR AS AÇÕES DO SUBMODULO/ACAO
        foreach ( $_POST['submodulos_acao'] as $item ) {
            $i          = explode(',', $item);
            $acao       = $i[0];
            $submodulo  = $i[1];

            $rsSelectSubModulo = $oConexao->prepare("SELECT count(idsubmodulo_acao) total FROM submodulo_acao WHERE idusuario = ? AND idsubmodulo = ? AND acao = ?");
            $rsSelectSubModulo->bindValue(1, $iduser);
            $rsSelectSubModulo->bindValue(2, $submodulo);
            $rsSelectSubModulo->bindValue(3, $acao);
            $rsSelectSubModulo->execute();
            $totalSubModulo = $rsSelectSubModulo->fetch( PDO::FETCH_OBJ )->total;
            if( $totalSubModulo >= 1 ){
                //UPDATE O MODULO
                $stmt = $oConexao->prepare("UPDATE submodulo_acao SET acao = ? WHERE idsubmodulo = ? AND idusuario = ? AND acao = ?");
                $stmt->bindValue(1, $acao);
                $stmt->bindValue(2, $submodulo);
                $stmt->bindValue(3, $iduser);
                $stmt->bindValue(4, $acao);
                $stmt->execute();
            }else{
                //INSERIR O MODULO
                $stmt = $oConexao->prepare("INSERT INTO submodulo_acao VALUES(NULL, ?, ?, ?)");
                $stmt->bindValue(1, $acao);
                $stmt->bindValue(2, $submodulo);
                $stmt->bindValue(3, $iduser);
                $stmt->execute();
            }

        }

        $oConexao->commit();

        //MENSAGEM DE SUCESSO
        $msg['msg'] = 'success';
        $msg['iduser'] = $iduser;

        echo json_encode($msg);
        exit();

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