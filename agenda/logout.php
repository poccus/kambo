<?
session_start();

include_once "conf/config.php";

if ( isset( $_SESSION['usuario'] ) ) {	$sessao = $_SESSION['usuario']; }else{ $sessao = 0; }

$urlanterior = $_POST['urlanterior'];
$idsessao = session_id();

$oConn       = Conexao::getInstance();
$sair = $oConn->prepare("DELETE FROM usuario_log WHERE idusuario = ? AND idsessao = ?");
$sair->bindValue(1, $sessao);
$sair->bindValue(2, $idsessao);
$sair->execute();

$atualizar = $oConn->prepare("UPDATE usuario SET online = 2 WHERE idusuario = ?");
$atualizar->bindValue(1, $sessao);
$atualizar->execute();
session_unset();
session_destroy();

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title><?=TITULOSISTEMA?></title>

        <!-- BEGIN META -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
		<?php 
			echo "<script 'text/javascript' src='".ASSETS."js/libs/jquery/jquery-1.11.2.min.js'></script>";
			echo "<script 'text/javascript' src='".UTILS_FOLDER."livequery.js'></script>";
			echo "<script 'text/javascript' src='".UTILS_FOLDER."utils.js'></script>";
		?>
     </head>
     <body>
     	<?php 
     		echo "<script 'text/javascript'>postToURL('".PORTAL_URL."login', {urlanterior: '$urlanterior'});</script>";
     	?>
     </body>
</html>