<?php 
  session_start();
  include_once "conf/config.php";
  //include_once "php/utils/funcoes.php";
  $oConexao = Conexao::getInstance();
  
  include('template/header.php');
  include('template/menubar.php');
  include('template/asideright.php');	

?>


<div class="content clearfix">
	
</div><!-- END CONTENT -->

<?php include('template/footer.php'); ?>