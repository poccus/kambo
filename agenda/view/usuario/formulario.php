<?php 
  session_start();

  // INCLUDE TEMPLATE
  include_once "conf/config.php";
  $oConexao = Conexao::getInstance();

  include('template/header.php');
  include('template/menubar.php');
  include('template/asideright.php');

?>
<?php 
// VERIFICACAO DE ACESSO DO MODULO
$moduloid           = $_SESSION['moduloid'];
$moduloapelido      = $_SESSION['moduloapelido'];
$modulopagina       = $_SESSION['modulopagina'];
$submoduloid        = $_SESSION['submoduloid'];
$submoduloapelido   = $_SESSION['submoduloapelido'];
$acaosubmodulo      = $_SESSION['acaousuario'];

// VERIFICAR SE O USUARIO TEM ACESSO A ESTA AREA
// if( !in_array('/usuario', $modulopagina) ){
//   echo "<script>
//       $(document).ready(function(){
//         defaultModal.util.openmodalpermissao({
//         open: {show: true, backdrop: 'static', keyboard: false},
//         title: 'PERMISSÃO - ACESSO NEGADO',
//         loadurl: false,
//         container: 'acesso-negado-area'
//       });
//       });
//     </script>";
//   exit();
// }

?>

<?php
//PARAMETRO POR URL AMIGAVEL NA POSIÇÃO 01 - PADRÃO
$param = Url::getURL( 3 );
$param = $_GET['id'] == '' || $_POST['id'] == '' ? $param : 0; 

if( $param != null || $param != '' || $param != NULL ){

  $id = $param;
  $result = $oConexao->query("SELECT idusuario, nome, login, email, senha, celular, liberado, perfil, online, foto, receber_email, date_format(datacadastro, '%d/%m/%Y %h:%i') as datacadastro FROM usuario WHERE idusuario = '$id'");
  $dadosUsuario = $result->fetch(PDO::FETCH_ASSOC);

  $idusuario                      = $dadosUsuario['idusuario'];
  $usuario_nome                   = $dadosUsuario['nome'];
  $usuario_foto                   = $dadosUsuario['foto'];
  $usuario_login                  = $dadosUsuario['login'];
  $usuario_email                  = $dadosUsuario['email'];
  $usuario_celular                = $dadosUsuario['celular'];
  $usuario_liberado               = $dadosUsuario['liberado'];
  $usuario_perfil                 = $dadosUsuario['perfil'];
  $usuario_receber_email          = $dadosUsuario['receber_email'];
  $usuario_datacadastro           = $dadosUsuario['datacadastro'];
  
  //ATTRIBUIR MODULOS
  $result = $oConexao->query("SELECT * FROM modulo_usuario WHERE idusuario = '$id'");
  $arrayModulos = array();
  while ( $dadosModulos = $result->fetch(PDO::FETCH_ASSOC) ) {
    array_push($arrayModulos, $dadosModulos['idmodulo']);
  }
  //VERIFICA SE O ARRAY ESTÁ VAZIO
  if($arrayModulos == null){
    $arrayModulos[] = '';
  }

  $rsEditSubModulo = $oConexao->prepare("SELECT sb.idsubmodulo, sb.nome as submodulo, sb.idmodulo ,m.nome as modulo, m.idusuario FROM submodulo sb
                                          INNER JOIN modulo m       ON ( sb.idmodulo = m.idmodulo )
                                          INNER JOIN modulo_submodulo msb ON ( sb.idsubmodulo = msb.idsubmodulo )
                                        WHERE
                                          msb.idusuario = ?");
  $rsEditSubModulo->bindValue(1, $id);
  $rsEditSubModulo->execute();
  $arraySubModulos = array();
  while ( $dataSubModulo = $rsEditSubModulo->fetch(PDO::FETCH_ASSOC) ) {
    array_push($arraySubModulos, $dataSubModulo['idsubmodulo']);
  }
  //VERIFICA SE O ARRAY ESTÁ VAZIO
  if($arraySubModulos == null){
    $arraySubModulos[] = '';
  }

  $rsEditSubModuloAcao = $oConexao->prepare("SELECT acao, idsubmodulo, idusuario FROM submodulo_acao WHERE idusuario = ?");
  $rsEditSubModuloAcao->bindValue(1, $id);
  $rsEditSubModuloAcao->execute();
  $arraySubModulosAcao = array();
  while ( $dataSubModuloAcao = $rsEditSubModuloAcao->fetch(PDO::FETCH_ASSOC) ) {
    array_push($arraySubModulosAcao, $dataSubModuloAcao['acao'].','.$dataSubModuloAcao['idsubmodulo']);
  }
  //VERIFICA SE O ARRAY ESTÁ VAZIO
  if($arraySubModulosAcao == null){
    $arraySubModulosAcao[] = '';
  }

  //pegar dados do perfil de profissional ou recepcionista
  if( $usuario_perfil == 2 || $usuario_perfil == 3  ){
    $rsProfissional = $oConexao->prepare("SELECT idusuario, idprofissional FROM usuario_profissional WHERE idusuario = ?");
    $rsProfissional->bindValue(1, $id);
    $rsProfissional->execute();
    $arrayProfissional = array();
    while ( $rowProfissional = $rsProfissional->fetch(PDO::FETCH_ASSOC) ) {
      array_push($arrayProfissional, $rowProfissional['idprofissional']);
    }
  }else{
    $arrayProfissional[] = '';
  }
}
?>
<div class="form-content clearfix" id="container-dashboard">

  <div class="main-formulario clearfix">
    <h4 class="pull-left">Adicionar usuário</h4>
    <h5 class="go-back pull-right">
      <a href="<?=PORTAL_URL?>view/usuario/index" class="pull-right">« voltar à lista</a>
      <?=$usuario_datacadastro != '' ? '<br><i class="pull-right text-muted">Usuário cadastrado em '.$usuario_datacadastro.'</i>' : '' ?>
    </h5>
  </div>

  <div id="return-feedback" class="alert display-n">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <div id="msg-feedback"></div>
  </div>

  <form id="createform" name="createform" class="form-horizontal" method="post" action="" enctype="multipart/form-data">

    <input type="hidden" id="idusuario" name="idusuario" value="<?=$idusuario?>">

    <fieldset class="clear">
      <div class="section">Informações básicas</div>
      
      <div class="form-group">
        <label class="col-sm-1 control-label" for="usuario_nome">Nome *</label>
        <div class="col-sm-11 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe o nome do usuário" id="usuario_nome" name="usuario_nome" tabindex="1" value="<?=$usuario_nome?>">
        </div>
      </div><!-- END INPUT -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="usuario_foto">Foto</label>
        <div class="col-sm-11 controls">
          <input type="file" id="usuario_foto" name="usuario_foto" class="fileArquivo" tabindex="2">
          <p class="help-block">Arquivos válidos: (.jpg, .jpeg, .png)</p>
        </div>
      </div><!-- END INPUT -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="usuario_liberado"></label>
        <div class="col-sm-4 controls">
          <label class="checkbox-inline" for="usuario_liberado"><input type="checkbox" id="usuario_liberado" name="usuario_liberado" tabindex="3" value="1" <?=$usuario_liberado == '1' || $usuario_liberado == '' ? 'checked="true"' : '' ?> > Ativo</label>
        </div>
      </div><!-- END INPUTS -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="usuario_receberemail"></label>
        <div class="col-sm-11 controls">
          <label class="checkbox-inline" for="usuario_receberemail"><input type="checkbox" id="usuario_receberemail" name="usuario_receberemail" tabindex="4" value="1" <?=$usuario_receber_email == '1' || $usuario_receber_email == '' ? 'checked="true"' : '' ?> > Receber e-mail? Sim, gostaria de receber dados da minha conta e quaisquer atividade que envolva minha conta.</label>
        </div>
      </div><!-- END INPUTS -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="usuario_celular">Celular </label>
        <div class="col-sm-5 controls">
          <input type="text" class="form-control input-sm" placeholder="(99)9999-9999" id="usuario_celular" name="usuario_celular" tabindex="5" value="<?=$usuario_celular?>">
        </div>
      </div><!-- END INPUT -->

    </fieldset><!-- END FIELDSET -->


    <fieldset class="clear">
      <div class="section">Dados de acesso</div>
      
      <div class="form-group">
        <label class="col-sm-1 control-label" for="usuario_login">Login *</label>
        <div class="col-sm-5 controls">
          <input type="text" rel="login" class="form-control input-sm" placeholder="Informe o login" id="usuario_login" name="usuario_login" tabindex="6" value="<?=$usuario_login?>">
          <img id="preload-login" class="display-n position-absolute position-preload-right-input" src="<?=PORTAL_URL?>imagens/ajax.gif">
        </div>

        <label class="col-sm-1 control-label" for="usuario_email">E-mail *</label>
        <div class="col-sm-5 controls">
          <input type="text" rel="email" class="form-control input-sm" placeholder="Informe o e-mail" id="usuario_email" name="usuario_email" tabindex="7" value="<?=$usuario_email?>">
          <img id="preload-email" class="display-n position-absolute position-preload-right-input" src="<?=PORTAL_URL?>imagens/ajax.gif">
        </div>
      </div><!-- END INPUTS -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="usuario_senha">Senha *</label>
        <div class="col-sm-5 controls">
          <input type="password" class="form-control input-sm" placeholder="Informe a senha" id="usuario_senha" name="usuario_senha" tabindex="8" value="" <?=$idusuario != '' || $idusuario != null ? 'disabled="disabled"' : '' ?>>
        </div>

        <label class="col-sm-1 control-label" for="usuario_confirmasenha">Repita *</label>
        <div class="col-sm-5 controls">
          <input type="password" class="form-control input-sm" placeholder="Repita a senha" id="usuario_confirmasenha" name="usuario_confirmasenha" tabindex="9" value="" <?=$idusuario != '' || $idusuario != null ? 'disabled="disabled"' : '' ?>>
        </div>
      </div><!-- END INPUTS -->

    </fieldset><!-- END FIELDSET -->

    <fieldset class="clear">
      <div class="section">Perfil de acesso</div>
      
      <div class="form-group">
        <label class="col-sm-1 control-label" for="usuario_perfil">Perfil *</label>
        <div class="col-sm-5 controls">
         <select class="form-control input-sm" id="usuario_perfil" name="usuario_perfil" tabindex="10">
          <option value=""></option>
          <option value="1" <?=$usuario_perfil == 1 ? 'selected="true"' : '' ?> >Administrador</option>
          <option value="2" <?=$usuario_perfil == 2 ? 'selected="true"' : '' ?> >Profissional</option>
          <option value="3" <?=$usuario_perfil == 3 ? 'selected="true"' : '' ?> >Recepcionista</option>
        </select>
        </div>
      </div><!-- END INPUTS -->

      <?php if( $usuario_perfil == 2 && $idusuario != null ){ $opcampodisplay = ''; }else{ $opcampodisplay = 'display-n'; } ?>
      <div id="addperfilprofissional" class="form-group <?=$opcampodisplay?>">
        <div class="col-sm-12"><hr class="ruler-lg"></div>
        <label class="col-sm-1 control-label" for="usuario_perfil">Profissional</label>
        <div class="col-sm-5 controls">
          <select class="form-control input-sm" id="usuario_perfilprofissional" name="usuario_perfilprofissional">
            <option value=""></option>
            <?php $result = $oConexao->prepare("SELECT idprofissional, nome FROM profissional"); $result->execute();
                  while( $dados = $result->fetch(PDO::FETCH_ASSOC) ){
            ?>
            <option value="<?=$dados['idprofissional']?>" <?php if( in_array( $dados['idprofissional'], $arrayProfissional) && $usuario_perfil == 2 ){echo 'selected="true"';}?> ><?=$dados['nome']?></option>
            <?php } ?>
          </select>
        </div>
      </div><!-- END INPUTS -->

      <?php if( $usuario_perfil == 3 && $idusuario != null ){ $opcampodisplay = ''; }else{ $opcampodisplay = 'display-n'; } ?>
      <div id="addperfilrecepcionista" class="form-group <?=$opcampodisplay?>">
        <div class="col-sm-12"><hr class="ruler-lg"></div>
        <label class="col-sm-1 control-label" for="usuario_senha">Profissional</label>
        <div class="col-sm-5 controls">
          <select class="form-control input-sm" id="usuario_profissional" name="usuario_profissional" tabindex="1">
            <option value=""></option>
            <?php $result = $oConexao->prepare("SELECT idprofissional, nome FROM profissional"); $result->execute();
                  while( $dados = $result->fetch(PDO::FETCH_ASSOC) ){
            ?>
            <option value="<?=$dados['idprofissional']?>" <?php if( in_array( $dados['idprofissional'], $arrayProfissional) && $usuario_perfil == 3 ){echo 'disabled="disabled"';}?>><?=$dados['nome']?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-sm-2">
          <a id="add-item-perfil-rcp" class="btn btn-success btn-sm" href="javascript:;" title="adcionar item">Adicionar</a>
        </div>
        <div class="col-sm-4">
          <p class="help-block">Adicione o profissional que o perfil selecionado terá acesso</p>
        </div>
      </div><!-- END INPUTS -->

      <?php if( $usuario_perfil == 3 && $idusuario != null ){ ?>
        <?php 
          $rsPF = $oConexao->prepare("SELECT p.nome, p.idprofissional FROM profissional p INNER JOIN usuario_profissional up ON (p.idprofissional = up.idprofissional) WHERE idusuario = ?");
          $rsPF->bindValue(1, $idusuario);
          $rsPF->execute();
          while( $rowPF = $rsPF->fetch(PDO::FETCH_ASSOC) ){
        ?>
        <div id="perfilrecepcionista" class="form-group">
          <div class="col-sm-12"><hr class="ruler-lg"></div>
          <div class="col-sm-1"></div>
          <div class="col-sm-5 controls">
            <label id="nameprofissional" class="control-label"><?=$rowPF['nome']?></label>
            <input type="hidden" value="<?=$rowPF['idprofissional']?>" id="usuario_profissional_id" name="usuario_profissional_id[]">
          </div>
          <div class="col-sm-2">
            <a id="save-item-perfil-rcp" class="btn btn-success btn-sm pull-left display-n" href="javascript:;" title="salvar item" rel="<?=$row['idusuario']?>"><i class="glyphicon glyphicon-ok "></i></a>
            <a id="edit-item-perfil-rcp" class="btn btn-info btn-sm pull-left" href="javascript:;" title="editar item"><i class="glyphicon glyphicon-pencil"></i></a>
            <a id="delete-item-perfil-rcp" class="btn btn-danger btn-sm pull-left margin-left-10px" href="javascript:;" title="excluir item"><i class="glyphicon glyphicon-trash"></i></a>
          </div>
        </div><!-- END INPUTS -->
        <?php } ?>
      <?php } ?>

      <div id="perfilrecepcionista" class="form-group display-n">
        <div class="col-sm-12"><hr class="ruler-lg"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-5 controls">
          <label id="nameprofissional" class="control-label"></label>
          <input type="hidden" value="" id="usuario_profissional_id" name="usuario_profissional_id[]">
        </div>
        <div class="col-sm-2">
          <a id="save-item-perfil-rcp" class="btn btn-success btn-sm pull-left display-n" href="javascript:;" title="salvar item" rel="<?=$row['idusuario']?>"><i class="glyphicon glyphicon-ok "></i></a>
          <a id="edit-item-perfil-rcp" class="btn btn-info btn-sm pull-left" href="javascript:;" title="editar item"><i class="glyphicon glyphicon-pencil"></i></a>
          <a id="delete-item-perfil-rcp" class="btn btn-danger btn-sm pull-left margin-left-10px" href="javascript:;" title="excluir item"><i class="glyphicon glyphicon-trash"></i></a>
        </div>
      </div><!-- END INPUTS -->

    </fieldset><!-- END FIELDSET -->


    <fieldset class="clear">
      <div class="section">Módulos de acesso</div>
      
      <div class="form-group">
        <div class="col-sm-12 controls">
          <label class="checkbox-inline"><input type="checkbox" id="marcartodossubmodulos" name="marcartodossubmodulos" value="0">Marcar todos</label>
        </div>
      </div><!-- END INPUT -->

      <div class="form-group">
        <div class="col-sm-12 controls">        
          <?php 
            //pegando dados do modulo de acesso do usuário, para listar os usuário de acordo com a sua permissão de acesso do modulo.
            $mod_id         = implode(',', $moduloid);
            $mod_id_usuario = $_SESSION['usuario'];
            $rsModulo = $oConexao->query("SELECT mu.idmodulo as idmodulo, m.nome as modulo, m.icon, m.apelido, u.idusuario FROM usuario u 
                                            INNER JOIN modulo_usuario mu ON ( u.idusuario = mu.idusuario ) 
                                            INNER JOIN modulo m ON ( mu.idmodulo = m.idmodulo  )
                                            WHERE mu.idmodulo in($mod_id) AND mu.idusuario = $mod_id_usuario AND m.liberado = 1");
            $countRsModulo = $rsModulo->rowCount();
            if( $countRsModulo > 0 ){
            while ( $rowModulo = $rsModulo->fetch(PDO::FETCH_ASSOC) ) {
          ?>
          <div class="col-sm-12 padding-0">
            <h4><i class="<?=$rowModulo['icon']?>"></i> <?=utf8_encode($rowModulo['modulo'])?></h4>
            <?php 
              $rsSubModulo = $oConexao->prepare("SELECT * FROM submodulo WHERE idmodulo = ?");
              $rsSubModulo->bindValue(1, $rowModulo['idmodulo']);
              $rsSubModulo->execute();
              $countRsSubmodulo = $rsSubModulo->rowCount();
              if( $countRsSubmodulo > 0 ){
                while( $rowSubModulo = $rsSubModulo->fetch(PDO::FETCH_ASSOC) ){
            ?>
            <div class="col-sm-12 padding-0">
              <label class="checkbox-inline"><input type="checkbox" id="father-submodulo-acao" name="submodulos[]" value="<?=$rowSubModulo['idmodulo']?>,<?=$rowSubModulo['idsubmodulo']?>" <?php if( in_array($rowSubModulo['idsubmodulo'], $arraySubModulos) ){echo "checked='true'";}?>><strong><?=utf8_encode($rowSubModulo['nome'])?></strong></label>
              <?php if( $rowSubModulo['excecao'] != 1 ){ ?>
              <div class="control-group">
                <label class="checkbox-inline"><input type="checkbox" id="sun-submodulo_acao" name="submodulos_acao[]" value="1,<?=$rowSubModulo['idsubmodulo']?>" <?php if( in_array('1,'.$rowSubModulo['idsubmodulo'], $arraySubModulosAcao) ){echo "checked='true'";}?>> Visualizar</label>
                <label class="checkbox-inline"><input type="checkbox" id="sun-submodulo_acao" name="submodulos_acao[]" value="2,<?=$rowSubModulo['idsubmodulo']?>" <?php if( in_array('2,'.$rowSubModulo['idsubmodulo'], $arraySubModulosAcao) ){echo "checked='true'";}?>> Adicionar</label>
                <label class="checkbox-inline"><input type="checkbox" id="sun-submodulo_acao" name="submodulos_acao[]" value="3,<?=$rowSubModulo['idsubmodulo']?>" <?php if( in_array('3,'.$rowSubModulo['idsubmodulo'], $arraySubModulosAcao) ){echo "checked='true'";}?>> Editar</label>
                <label class="checkbox-inline"><input type="checkbox" id="sun-submodulo_acao" name="submodulos_acao[]" value="4,<?=$rowSubModulo['idsubmodulo']?>" <?php if( in_array('4,'.$rowSubModulo['idsubmodulo'], $arraySubModulosAcao) ){echo "checked='true'";}?>> Excluir</label>
                <hr class="ruler-lg">
              </div>
              <?php }//END IF ?>
            </div>
            <?php }//END WHILE ?>
            <?php }//END IF ?>
          </div>
          <?php }//END WHILE ?>
          <?php }//END IF ?>
        </div>
      </div><!-- END INPUT -->

    </fieldset><!-- END FIELDSET -->

    <fieldset class="clear form-actions margin-bottom-0">
        <button id="enviarformulario" type="submit" class="btn btn-primary" value="_save">Salvar</button>
        <button id="enviareditar" type="submit" class="btn btn-default" value="_continue">Salvar e continuar editando</button> 
        <button id="enviaradicionar" type="submit" class="btn btn-default" value="_addanother">Salvar e adicionar outro</button>
        <?php if( $idusuario != '' || $idusuario != NULL ){ ?>
        <button id="deletaritem" type="button" class="btn btn-danger pull-right" value="_delete"><i class="glyphicon glyphicon-trash"></i> Excluir</button>
        <?php } ?>
        <img class="preload-submit display-n" src="<?=PORTAL_URL?>imagens/load.gif"> 
    </fieldset><!-- END FIELDSET -->

  </form>


</div>  

<!-- INCLUDE JAVASCRIPT -->
<script src="<?=PORTAL_URL?>ajax/usuario/formulario.js"></script>
<?php include('template/footer.php'); ?>

<?php 
// if( $param != null || $param != '' || $param != NULL ){
// //VARIAVEIS DE LOGIN E HISTORICO DE ACESSO
//   $idsessao       = session_id();
//   $pagina_historico   = 'Usuários';
//   $apelido_historico  = 'usuarios';
//   $operacao_historico = 'Editar';
//   $ip_historico   = $_SERVER['REMOTE_ADDR'];
// }else{
//   //VARIAVEIS DE LOGIN E HISTORICO DE ACESSO
//   $idsessao       = session_id();
//   $pagina_historico   = 'Usuários';
//   $apelido_historico  = 'usuarios';
//   $operacao_historico = 'Adicionar';
//   $ip_historico   = $_SERVER['REMOTE_ADDR'];
// }
?>