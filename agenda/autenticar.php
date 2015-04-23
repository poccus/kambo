<?
session_start();
$idsessao = session_id();
$ip = $_SERVER['REMOTE_ADDR'];

include_once "conf/config.php";
$oConexao = Conexao::getInstance();

header("Content-Type: text/html; charset=UTF-8",true);
header('Content-type: application/json');

try{	

	//PEGAR DADOS DE LOGIN
	$login = strip_tags( $_POST['login'] );
	$senha = strip_tags( sha1( $_POST['senha'] ) );
	//SQL DE VERIFICAÇÃO LOGIN EXISTENTE
	$result = $oConexao->query("SELECT * FROM usuario WHERE login = '$login' OR email = '$login'");
	$num = $result->rowCount();
	if ( $num > 0 ){
		// PEGA OS DADOS DO USUARIO, CASO TENHA ACESSO	
  		$dadosUsuario = $result->fetch(PDO::FETCH_ASSOC);

  		// VERFICA SE A SENHA INFORMADA É IGUAL DO USUARIO
  		if ( $senha == $dadosUsuario['senha'] ) {
  			
  			if( $dadosUsuario['liberado'] == 1 ) {
			    
			    $idusuario = $dadosUsuario['idusuario'];

  				//CRIAR O TIMEOUT DA SESSÃO PARA EXPIRAR
  				$_SESSION['timeout'] 		= time();

  				// CRIAR AS SESSOES DO USUARIO
		  		$_SESSION['usuario']		=  $dadosUsuario['idusuario'];
			    $_SESSION['nome'] 			=  $dadosUsuario['nome'];
			    $_SESSION['email'] 			=  $dadosUsuario['email'];
			    $_SESSION['foto'] 			=  $dadosUsuario['foto'];
	// 		    // STATUS ONLINE -> 1 - ONLINE e 2 - OFFLINE 
			    $_SESSION['online'] 		=  1;
	// 		    //ATUALIZANDO O STATUS ONLINE DO USUARIO
			    $result = $oConexao->query("UPDATE usuario SET online = '1' WHERE idusuario = '".$idusuario."'");

			    // NIVEIS DE ACESSO -> 1 - ADMINISTRADOR(TUDO) , 2 - MÉDICO(APENAS PODERÁ VISUALIZAR SUA AGENDA) e 3 - RECEPECIONISTA(TEM ACESSO A TODAS AGENDAS DOS MEDICOS ADICIONADO A ELA)
			    $_SESSION['nivelacesso'] 	=  $dadosUsuario['perfil'];
			    $_SESSION['datacadastro'] 	=  $dadosUsuario['datacadastro'];
			    $_SESSION['alterar_senha'] 	=  sha1($_POST['senha']) == '3494d609fbe6be4ff57c89a0e3db0fc0643e54fc' ? 1 : 0; //SENHA PADRÃO (agendae.net)


			    $result = $oConexao->query("SELECT a.idmodulo, a.nome, a.pagina, a.apelido FROM modulo a
												INNER JOIN modulo_usuario b ON a.idmodulo = b.idmodulo
											WHERE
												b.idusuario = '$idusuario' ORDER BY a.idmodulo");

			    $i = 0;
			    $x = 0;
			    $z = 0;
			    // CRIAR AS SESSOES DOS MODULOS
				while($item = $result->fetch(PDO::FETCH_ASSOC)){
					$_SESSION['moduloid'][$i] 				= $item['idmodulo'];
					$_SESSION['modulopagina'][$i] 			= $item['pagina'];
					$_SESSION['moduloapelido'][$i] 			= $item['apelido'];

					//CRIAR AS SESSOES DO SUBMODULOS
					$resultSubModulo = $oConexao->prepare('SELECT sm.idsubmodulo, sm.apelido, msm.idmodulo FROM submodulo sm INNER JOIN modulo_submodulo msm ON (sm.idsubmodulo = msm.idsubmodulo) WHERE msm.idmodulo = ?  AND msm.idusuario = ?');
					$resultSubModulo->bindValue(1, $item['idmodulo']);
					$resultSubModulo->bindValue(2, $idusuario);
					$resultSubModulo->execute();
					$countSubModulo = $resultSubModulo->rowCount();
					if( $countSubModulo > 0 ){
						while ( $rowSM = $resultSubModulo->fetch(PDO::FETCH_ASSOC) ) {
							$_SESSION['submoduloid'][$x] 		= $rowSM['idsubmodulo'];
							$_SESSION['submodulo'][$x] 			= $rowSM['idsubmodulo'];
							$_SESSION['submoduloapelido'][$x] 	= $rowSM['apelido'];
							$x++;

							//CRIAR AS SESSOES DO SUBMODULOS_ACAO
							$rsSubModuloAcao = $oConexao->prepare('SELECT acao, idsubmodulo FROM submodulo_acao WHERE idsubmodulo = ? AND idusuario = ?');
							$rsSubModuloAcao->bindValue(1, $rowSM['idsubmodulo']);
							$rsSubModuloAcao->bindValue(2, $idusuario);
							$rsSubModuloAcao->execute();
							$totalSubModuloAcao = $rsSubModuloAcao->rowCount();
							if( $totalSubModuloAcao > 0 ){
								while ( $rowSMA = $rsSubModuloAcao->fetch(PDO::FETCH_ASSOC) ) {
									$_SESSION['acao_submoduloid'][$z] 		= $rowSMA['idsubmodulo'];
									$_SESSION['acaousuario'][$z] 			= $rowSMA['acao'].'/'.$rowSM['apelido'];
									$z++;
								}
							}
						}
					}
					$i++;
				}

				//BUSCAR NAVEGADOR E SO
			    $useragent = $_SERVER['HTTP_USER_AGENT'];
				if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
					$browser_version=$matched[1];
					$browser = 'IE';
				} elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$useragent,$matched)) {
					$browser_version=$matched[1];
					$browser = 'Opera';
				} elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
					$browser_version=$matched[1];
					$browser = 'Firefox';
				} elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)) {
					$browser_version=$matched[1];
					$browser = 'Chrome';
				} elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
					$browser_version=$matched[1];
					$browser = 'Safari';
				} else {
					$browser_version = 0;
					$browser= 'Desconhecido';
				}
				$separa = explode(";", $useragent);
				$so = $separa[1];

				//DELETAR CONEXOES DO USUARIO LOGADO
				$delusuariolog = $oConexao->prepare("DELETE FROM usuario_log WHERE idusuario = ?");
				$delusuariolog->bindValue(1, $dadosUsuario['idusuario']);
				$delusuariolog->execute();

			    //INSERIR O LOGIN DO USUARIO
			    $insertusuariolog = $oConexao->prepare("INSERT INTO usuario_log(idusuario, datalogin, datalogout, ipusuario, idsessao, navegador, SO) VALUES(?, NOW(), NULL, ?, ?, ?, ?)");
			    $insertusuariolog->bindValue(1, $dadosUsuario['idusuario']);
			    $insertusuariolog->bindValue(2, $ip);
			    $insertusuariolog->bindValue(3, $idsessao);
			    $insertusuariolog->bindValue(4, $browser.' '.$browser_version);
			    $insertusuariolog->bindValue(5, $so);
			    $insertusuariolog->execute();

			    //URL ANTERIOR
			    $urlanterior = $_POST['urlanterior'];

				//MENSAGEM DE SUCESSO
		        $msg['msg']         	= 'success';
		        if( $urlanterior != '' ){
		        	$msg['url_dashboard'] 	= $urlanterior;
		        }else{
		        	$msg['url_dashboard'] 	= PORTAL_URL.'view/agenda/#!';
		    	}
		        echo json_encode($msg);
		        exit();

  			}else{
  				//MENSAGEM DE ERRO
		        $msg['msg']         = 'error';
		        $msg['msg_error'] 	= utf8_encode('Login bloqueado, por favor verifique a situação com o administrador.');
		        $msg['msg_error_number']	= 1;
		        echo json_encode($msg);
		        exit();
  			}
  		}else{
  			//MENSAGEM DE ERRO
	        $msg['msg']         		= 'error';
	        $msg['msg_error'] 			= utf8_encode('A Senha informada está incorreta');
	        $msg['msg_error_number']	= 2;
	        echo json_encode($msg);
	        exit();
  		}
	}else{
		//MENSAGEM DE ERRO
        $msg['msg']         = 'error';
        $msg['msg_error']   = utf8_encode('O Usuário informado está incorreto');
        $msg['msg_error_number']	= 3;
        echo json_encode($msg);
        exit();
	}

}catch (PDOException $e){
	//MENSAGEM DE ERRO
    $msg['msg']         = 'error';
    $msg['msg_error']   = 'Error ao tentar efetuar o login. : '.$e->getMessage();
    $msg['msg_error_number']	= 0;
    echo json_encode($msg);
    exit();
}
?>