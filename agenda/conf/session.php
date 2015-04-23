<?php 

//time duration session
function sessionOn(){
    //VERIFICAÇÃO DE SESSION COM TIMEOUT PARA EXPIRAR
    // 30 minutos em segundos
    $inactive_session     = 1800;
    $urlanterior             = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if( isset($_SESSION['usuario']) ){
      $session_life = time() - $_SESSION['timeout'];
      if( $session_life > $inactive_session ){
        echo "<script 'text/javascript'>postToURL('".PORTAL_URL."logout', {urlanterior: '$urlanterior'});</script>";
      }
    }else{
      $_SESSION['timeout'] = time();
    }
}

?>