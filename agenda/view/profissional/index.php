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

  //SETANDO O TITULO DA PAGINA E SUBTITULO
  // $TITULO_BREADCRUMB = 'Lista';
  // $SUBTITULO_BREADCRUMB = 'DE USUARIO';

  // $modulos    = $_SESSION['modulodescricao'];
  // // VERIFICAR SE É ADMINISTRADOR OU USUARIO COMUM
  // // NIVEIS DE ACESSO -> 1 - ADMINISTRADOR , 2 - AUTOR e 3 - USUARIO COMUM
  // if( $_SESSION['nivelacesso'] == 1 && !in_array('medico', $modulos) ){
  //   echo "<script>window.location = 'index.php';</script>";
  //   exit();
  // }else if( $_SESSION['nivelacesso'] == 2 && !in_array('medico', $modulos) ){
  //   echo "<script>window.location = 'index.php';</script>";
  //   exit();
  // }else if( $_SESSION['nivelacesso'] == 3 && !in_array('medico', $modulos) ){
  //   echo "<script>window.location = 'index.php';</script>";
  //   exit();
  // }

?>

<div class="content clearfix">
  
  <?php if( isset($_POST['type']) ){ 
    $alert_type = $_POST['type'] == 'success' ? 'alert-success' : 'alert-danger';
    $alert_icon = $_POST['type'] == 'success' ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>'; 
  ?>
  <div class="col-sm-12">
    <div id="return-feedback" class="alert <?=$alert_type?>">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <div id="msg-feedback"><?=$alert_icon?>  <?=$_POST['feedback']?> <?=$_POST['error']?></div>
    </div>
  </div><!-- FEEDBACK MESSAGE -->
  <?php } ?>

  <aside class="col-sm-2">
    <h2>Profissional</h2>
    <p class="help-text">Busca por:</p>
    <form id="filtro" name="filtrolista" method="post" action="" enctype="multipart/form-data">
      <div class="form-group">
        <select name="filtrotipo" class="selectpicker form-control input-sm">
          <option value="nome" <?=isset($_REQUEST['filtrotipo']) && $_REQUEST['filtrotipo'] == 'nome' ? "selected='true'" : ''?> >Nome</option>
          <option value="telefone" <?=isset($_REQUEST['filtrotipo']) && $_REQUEST['filtrotipo'] == 'telefone' ? "selected='true'" : ''?>>Telefone</option>
          <option value="crm" <?=isset($_REQUEST['filtrotipo']) && $_REQUEST['filtrotipo'] == 'crm' ? "selected='true'" : ''?>>CRM</option>
        </select>
      </div>
      <div class="form-group">
        <input name="filtrocampo" id="filtrocampo" class="form-control input-sm" type="text" placeholder="Busca" value="<?=isset($_REQUEST['filtrocampo']) ? $_REQUEST['filtrocampo'] : ''; ?>">
        <button type="submit" name="filtrobusca" id="filtrobusca" class="btn btn-default btn-sm relative-btn pull-right"><i class="glyphicon glyphicon-search"></i></button>
      </div>
    </form>
  </aside><!-- ASIDE BAR  -->

  <div class="col-sm-10"><!-- BEGIN PANEL -->

    <div class="row options">
      <div id="check" class="pull-left">
          <!-- Single button -->
          <div class="btn-group">
            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <i class="glyphicon glyphicon-cog"></i> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li id="rmv-li-slc" class="disabled"><a id="rmv-item-slc" href="#">Remover selecionados</a></li>
            </ul>
          </div>
      </div>
      <div id="actions" class="list-commands pull-right">
          <a href="<?=PORTAL_URL?>view/profissional/formulario" class="btn btn-success btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Adicionar</a>
      </div>
    </div><!-- END ACTIONS -->

    <div class="row options">
        <form id="form-table" method='post' action='' enctype="multipart/form-data">
            <div class="table-responsive margin-top-0-5em">          
            <table id="tabela-lista-dados" class="table table-striped">
            <thead>
              <tr>
                <?php //if( $_SESSION['nivelacesso'] == 1 ){ ?>
                <th width="1%" scope="col">
                    <input type="checkbox" name="marcatodos" id="marcartodos" value="all">
                </th>
                <?php //} ?>
                <th width="1%" scope="col">#</th>
                <th width="39%" scope="col">Nome</th>
                <th width="20%" scope="col">Registro</th>
                <th width="15%" scope="col">Telefone</th>
                <th width="15%" scope="col">E-mail</th>
                <th width="10%" scope="col"></th>
              </tr>
            </thead>
            <tbody>
            <?php    
                
                //DADOS DA CONSULTA
                $sql = "SELECT idprofissional, nome, crm, telefone, email FROM profissional ";
                $dadosPesquisa .= isset($_REQUEST['filtrocampo']) && $_REQUEST['filtrocampo'] != '' && $_REQUEST['filtrotipo'] == 'nome' ? " WHERE UPPER(nome) LIKE '%" . strtoupper($_REQUEST['filtrocampo']) . "%'" : '';
                $dadosPesquisa .= isset($_REQUEST['filtrocampo']) && $_REQUEST['filtrocampo'] != '' && $_REQUEST['filtrotipo'] == 'telefone' ? " WHERE telefone LIKE '%" . strtoupper($_REQUEST['filtrocampo']) . "%'" : '';
                $dadosPesquisa .= isset($_REQUEST['filtrocampo']) && $_REQUEST['filtrocampo'] != '' && $_REQUEST['filtrotipo'] == 'crm' ? " WHERE crm LIKE '%" . strtoupper($_REQUEST['filtrocampo']) . "%'" : '';
                $orderby = " ORDER BY nome ASC";

                //TOTAL DE PAGINACAO
                $total = 10; 
                $paginacao = isset( $_GET['paginacao'] ) ? $_GET['paginacao'] : 1;
                $inicio = ( $paginacao - 1 ) * $total;
                $limite = " LIMIT $inicio, $total";

                //PEGAR O DADOS DE ACORDO COM A CONSULTA NO BANCO DE DADOS
                // echo $sql.$dadosPesquisa.$orderby.$limite;
                $resultado = $oConexao->query($sql.$dadosPesquisa.$orderby.$limite);

                //PEGAR O TOTAL DE DADOS NA TABELA
                $totalresultado = $oConexao->query($sql.$dadosPesquisa)->rowCount();

                //CALCULAR O TOTAL DE PAGINAS
                $totaldepaginas = ceil( $totalresultado / $total );

                // Calcula os intervalos iniciais e finais
                // para saber quais registros vamos mostrar
                $fim = $total * $paginacao;
                $inicio = ($fim - $total);

                //CONTAGEM DE DADOS
                $contador = 0;

            ?>
            <?php if($totalresultado <= 0 || $totalresultado == NULL){ ?>
                    <tr><td colspan="7">Nenhum registro encontrado.</td></tr>
            <?php }else{
                  $contador = $inicio;
                  while($row = $resultado->fetch(PDO::FETCH_ASSOC)){
                ?>

                <tr id="tr-id-<?=$row['id']?>">
                  <?php //if( $_SESSION['nivelacesso'] == 1 ){ ?>
                  <th scope="col-xs-1">
                      <input type="checkbox" name="itemselecionado[]" id="itemselecionado-<?=$row['idprofissional']?>" value="<?=$row['idprofissional']?>">
                  </th>
                  <?php //} ?>  
                  <td><?=$contador+1?></td>
                  <td>
                    <?php //if( $_SESSION['nivelacesso'] == 1 ){//APENAS ADMINISTRADOR PODE EDITAR O ITEM ?> 
                      <a href="<?=PORTAL_URL?>view/profissional/formulario/<?=$row['idprofissional']?>" title="editar item" rel="<?=$row['idprofissional']?>">
                        <?=$row['nome']?>
                      </a>  
                      <?php //}else{ ?>
                        <?php //echo $row['nome']; ?>
                      <?php //}?>
                  </td>
                  <td><?=$row['crm']?></td>
                  <td><?=$row['telefone']?></td>
                  <td><?=$row['email']?></td>
                  <td class="text-right">
                    <a id="detalhe-item" class="btn btn-info btn-sm" href="javascript:;" title="visualizar item" rel="<?=$row['idprofissional']?>"><i class="glyphicon glyphicon-zoom-in"></i></a>
                  </td>
                </tr>
                
              <?php $contador++; } } ?>
            
            </tbody>
          </table>
          <?php if($totalresultado >= 1 || $totalresultado != NULL){ ?>
          <!-- INICIO DA PAGINACAO -->
          <ul class="pagination pagination-sm no-margin pull-right">
              <?php if ($paginacao == 1): ?>
                  <li class="disabled"><span>Primeira</span></li>
                  <li class="disabled"><span>Anterior</span></li>
              <?php else: ?>
                  <li><a href="?paginacao=1&filtrotipo=<?=$_REQUEST['filtrotipo']?>"><span>Primeira</span></a></li>
                  <li><a href="?paginacao=<?php print $paginacao-1;?>&filtrotipo=<?=$_REQUEST['filtrotipo']?>"><span>Anterior</span></a></li>
              <?php endif; ?>
              <!-- mostra até cinco páginas antes da página atual -->
              <?php foreach(array_reverse(range($paginacao-1, $paginacao-5)) as $pagina): ?>
                  <?php if ($pagina > 0): ?>
                      <li><a href="?paginacao=<?php print $pagina; ?>&filtrotipo=<?=$_REQUEST['filtrotipo']?>"><?php print $pagina; ?></a></li>
                  <?php endif; ?>
              <?php endforeach; ?>
              <!-- mostra a página atual para o usuário -->
             <li class="disabled"><span><?php print $paginacao; ?></span></li>
              <!-- mostra até cinco página após a página atual -->
              <?php foreach( range($paginacao+1, $paginacao+5) as $pagina): ?>
                  <?php if ($pagina < $totaldepaginas): ?>
                  <li><a href="?paginacao=<?php print $pagina; ?>&filtrotipo=<?=$_REQUEST['filtrotipo']?>"><?php print $pagina; ?></a></li>
                  <?php endif; ?>
              <?php endforeach; ?>
              <!-- mostra os links para a próxima página
              e para a última página da lista -->
              <?php if ($paginacao == $totaldepaginas): ?>
                  <li class="active"><span>Pr&oacute;xima</span></li>
                  <li class="active"><span>&Uacute;ltima</span></li>
              <?php else: ?>
                  <li><a href="?paginacao=<?php print $paginacao+1; ?>&filtrotipo=<?=$_REQUEST['filtrotipo']?>"><span>Pr&oacute;xima</span></a></li>
                  <li><a href="?paginacao=<?php print $totaldepaginas;?>&filtrotipo=<?=$_REQUEST['filtrotipo']?>"><span>&Uacute;ltima</span></a></li>
              <?php endif; ?>
          </ul>
          <!-- FIM DA PAGINACAO -->
          <div class="pull-left text-pagination">
            <?php echo "Mostrando $paginacao de $totaldepaginas com $totalresultado registros.";?>
          </div>
          <?php } ?> 

          </div>
        </form>
    </div><!-- END LIST TABLE -->

  </div><!-- END PANEL -->
        
</div><!-- END CONTENT -->

<!-- INCLUDE JAVASCRIPT -->
<script src="<?=PORTAL_URL?>ajax/profissional/lista.js"></script>

<?php  include('template/footer.php'); ?>