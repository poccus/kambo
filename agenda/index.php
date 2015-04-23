<?php 

  session_start();

  //ADICIONAR A CONEXAO E URL AMIGAVEL
  include_once "conf/Url.php";
  include_once "conf/config.php";

  //FUNCOES
  include_once "conf/session.php";
  include_once "utils/funcoes.php";
  
  //INSTANCIA A CONEXAO
  $oConexao = Conexao::getInstance();

  // VERIFICAR O NIVEL DE ACESSO DO USUARIO
  // if( isset( $_SESSION['nivelacesso']) ){
  //   if($_SESSION['nivelacesso'] == 1){
  //      $nivelacesso =  'Administrador';
  //   }else if($_SESSION['nivelacesso'] == 2){
  //       $nivelacesso =  'Autor';
  //   }else if($_SESSION['nivelacesso'] == 3){
  //       $nivelacesso =  'Usuário Comum';
  //   }
  // }

  // // VERIFICAR SE O USUARIO ESTÁ ONLINE OU OFFLINE
  // if( isset( $_SESSION['online'] ) ){
  //   if($_SESSION['online'] == 1){
  //      $online =  'Online';
  //   }else if($_SESSION['online'] == 2){
  //       $online =  'Offline';
  //   }
  // }

?>

<?php   

    $modulo                 = Url::getURL( 0 );
    $pastamodulo            = Url::getURL( 1 );
    $arquivomodulo          = Url::getURL( 2 );
    $parametromodulo        = Url::getURL( 3 );

    // echo "modulo: $modulo <br/>";
    // echo "pasta: $pastamodulo <br/>";
    // echo "arquivo: $arquivomodulo <br/>";
    // echo "parametro: $parametromodulo <br/>";

    if($modulo == ''){
        $modulo = "login";
        require $modulo . ".php";
        sessionOn();
        exit();
    }else{
      //VERIFICA SE O ARQUIVO EXISTE E EXIBI
      if( file_exists( $modulo.".php" ) ){
          include $modulo.".php";
          sessionOn();
          exit();
      }
    }

    if( $modulo == 'index.php' || $modulo == 'index' ||  $modulo == ''  || $modulo == null  ){
        
        $modulo = "dashboard";
        include $modulo . ".php";
        sessionOn();
        exit();

    }else if( $modulo == 'dashboard.php' || $modulo == 'dashboard'  ){
      
      $modulo = "dashboard";
      include $modulo . ".php";
      sessionOn();
      exit();

    }else{

        //VERIFICAÇÃO DE MODULOS
        if( $pastamodulo == 'index.php' || $pastamodulo == 'index' || $pastamodulo == '' || $pastamodulo == null  ){

            //VERIFICA SE O ARQUIVO EXISTE E EXIBI
            if( file_exists( $pastamodulo.'/'. "index.php" ) ){
                include_once $pastamodulo.'/'. "index.php";
                sessionOn();
                exit();
            }else{
                include_once "404.php";
                sessionOn();
                exit();
            }

        }else{
            if( $arquivomodulo == '' || $arquivomodulo == null ){

                //VERIFICA SE O ARQUIVO EXISTE E EXIBI
                if( file_exists( $modulo.'/'.$pastamodulo.'/'. "index.php" ) ){
                    include_once $modulo.'/'.$pastamodulo.'/'. "index.php";
                    sessionOn();
                    exit();
                }else{
                    include_once "404.php";
                    sessionOn();
                    exit();
                }


            }else{
                //VERIFICA SE O ARQUIVO EXISTE E EXIBI
                if( file_exists( $modulo.'/'.$pastamodulo.'/'.$arquivomodulo. ".php" ) ){
                    include $modulo.'/'.$pastamodulo.'/'.$arquivomodulo. ".php";
                    sessionOn();
                    exit();
                }else{
                    include "404.php";
                    sessionOn();
                    exit();
                }
            }
        }//END IF

    }//END IF

?>  