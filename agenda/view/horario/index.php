<?php 
  session_start();
  // INCLUDE TEMPLATE
  include_once "../../conf/config.php";
  include('../../template/head.php');
  include('../../template/header.php');
  
?>

<?php 
  
  $oConexao = new PDO("mysql:host=localhost;dbname=agendar;charset=utf8" , "root", "");

  // include_once "../../../conf/config.php";
  // $oConexao = Conexao::getInstance();

  //SETANDO O TITULO DA PAGINA E SUBTITULO
  // $TITULO_BREADCRUMB = 'Lista';
  // $SUBTITULO_BREADCRUMB = 'DE USUARIO';

  // $modulos    = $_SESSION['modulodescricao'];
  // // VERIFICAR SE É ADMINISTRADOR OU USUARIO COMUM
  // // NIVEIS DE ACESSO -> 1 - ADMINISTRADOR , 2 - AUTOR e 3 - USUARIO COMUM
  // if( $_SESSION['nivelacesso'] == 1 && !in_array('paciente', $modulos) ){
  //   echo "<script>window.location = 'index.php';</script>";
  //   exit();
  // }else if( $_SESSION['nivelacesso'] == 2 && !in_array('paciente', $modulos) ){
  //   echo "<script>window.location = 'index.php';</script>";
  //   exit();
  // }else if( $_SESSION['nivelacesso'] == 3 && !in_array('paciente', $modulos) ){
  //   echo "<script>window.location = 'index.php';</script>";
  //   exit();
  // }

?>

<!-- JAVASCRIPT -->
<script src="../../ajax/paciente/operacao-paciente.js"></script>

<div class="container-fluid">

        <div id="alerta-retorno" class="alert display-n">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <div id="mensagem-retorno"></div>
        </div>

        <div class="row">
          <div class="control-group">
            <div class="col-md-12 margin-bottom-1em">
              <h3 class="block-heading fonte-ubuntu fonte-weight-300 fonte-uppercase"><span>Lista de paciente</span></h3>
            </div>
          </div>
        </div>

        <div class="row">

            <?php //if( $_SESSION['nivelacesso'] == 1 ){ //NIVEL DE ADMINISTRADOR, APENAS ADICIONAR USUARIO ?> 
            <div class="col-md-1 margin-right-2em">
                <a href="../../view/paciente/formulario.php" class="btn btn-success">Novo paciente</a>
            </div>
            <?php //} ?>

            <?php //if( $_SESSION['nivelacesso'] == 1 ){ ?>
            <div class="col-md-5 margin-left-0-5em" id="operacao-excluir-selecionado" style="display: none;">

              <!-- <a href="#" class="btn btn-info margin-left-0-3em" id="operacaoSuccessUnidade" rel="ativar"><i class="glyphicon glyphicon-ok"></i> Ativar</a> -->
              <a href="#" class="btn btn-danger margin-left-0-3em" id="operacaoDangerUnidade" rel="excluir"><i class="glyphicon glyphicon-ban-circle"></i> Excluir</a>
              <!-- <a href="#" class="btn btn-primary margin-left-0-3em" id="operacaoDangerUnidade" rel="resetar_senha"><i class="glyphicon glyphicon-lock"></i> Resetar senha</a> -->
    
            </div>
            <?php //} ?>

            <div class="col-md-3 margin-bottom-0-5em pull-right">
              <form action="" method="post" class="form-horizontal">
                <input type="text" id="search_nome" name="nome" class="form-control pull-right margin-bottom-0-5em" placeholder="Digite aqui sua busca" value="<?=isset($_POST['nome']) ? $_POST['nome'] : ''; ?>">
                <button type="submit" name="busca" id="busca" class="pull-right btn btn-default position-absolute position-right-1em">Buscar</button>
              </form>
              <!-- <i class="glyphicon glyphicon-search"></i> -->
            </div>

            <div class="col-md-12 margin-bottom-1em">
                <a href="../../dashboard.php" id="voltarMenu" class="pull-right">« voltar ao painel</a>
            </div>
        </div>
            
        <form id="form-table" method='post' action='' enctype="multipart/form-data">
            <div class="table-responsive margin-top-0-5em">          
            <table id="tabela-lista-dados" class="table table-striped">
            <thead>
              <tr>
                <?php //if( $_SESSION['nivelacesso'] == 1 ){ ?>
                <th width="1%" scope="col">
                    <!-- <input type="checkbox" name="marcatodos" id="marcartodos" value="all"> -->
                </th>
                <?php //} ?>
                <th width="1%" scope="col">#</th>
                <th width="30%" scope="col">NOME</th>
                <th width="10%" scope="col">DATA NASCIMENTO</th>
                <th width="10%" scope="col">TELEFONE</th>
                <th width="15%" scope="col">EMAIL</th>
                <th width="24%" scope="col"></th>
              </tr>
            </thead>
            <tbody>
            
            <?php    

                //DADOS DA CONSULTA

                $sql = "SELECT id, nome, datanascimento, telefone, email FROM paciente ";
                $dadosPesquisa = isset($_REQUEST['nome']) && $_REQUEST['nome'] != '' ? " WHERE UPPER(nome) LIKE '%" . strtoupper($_REQUEST['nome']) . "%'" : '';
                $orderby = " ORDER BY nome ASC";

                //TOTAL DE PAGINACAO
                $total = 10; 
                $paginacao = isset( $_GET['paginacao'] ) ? $_GET['paginacao'] : 1;
                $inicio = ( $paginacao - 1 ) * $total;
                $limite = " LIMIT $inicio, $total";

                //PEGAR O DADOS DE ACORDO COM A CONSULTA NO BANCO DE DADOS
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
                    <tr><td colspan="5">Nenhum registro encontrado.</td></tr>
            <?php }else{
                  $contador = $inicio;
                  while($row = $resultado->fetch(PDO::FETCH_ASSOC)){
                ?>

                <tr id="tr-id-<?php echo $row['id'];?>">
                  <?php //if( $_SESSION['nivelacesso'] == 1 ){ ?>
                  <th scope="col-md-1">
                      <input type="radio" name="itemselecionado[]" id="itemselecionado-<?php echo $row['id'];?>" value="<?php echo $row['id'];?>">
                  </th>
                  <?php //} ?>  
                  <td><?php echo ($contador + 1); ?></td>
                  <td>
                    <?php //if( $_SESSION['nivelacesso'] == 1 ){//APENAS ADMINISTRADOR PODE EDITAR O ITEM ?> 
                      <a href="../../view/paciente/formulario-visualiza.php?id=<?php echo $row['id'];?>" title="editar item" rel="<?php echo $row['id'];?>">
                        <?php echo $row['nome']; ?>
                      </a>  
                      <?php //}else{ ?>
                        <?php //echo $row['nome']; ?>
                      <?php //}?>
                  </td>
                  <td><?php echo $row['datanascimento'];?></td>
                  <td><?php echo $row['telefone'];?></td>
                  <td><?php echo $row['email'];?></td>
                  <td class="text-right">
                    <a class="btn btn-info" href="../../view/paciente/formulario-visualiza.php?id=<?php echo $row['id'];?>" title="visualizar item" rel="<?php echo $row['id'];?>"><i class="glyphicon glyphicon-zoom-in"></i> Visualizar</a>
                    <a class="btn btn-primary" href="../../view/paciente/formulario.php?id=<?php echo $row['id'];?>" title="editar item" rel="<?php echo $row['id'];?>"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
                  </td>
                </tr>
                
              <?php $contador++; } } ?>
            
            </tbody>
          </table>


          <!-- INICIO DA PAGINACAO -->
          <ul class="pagination pagination-sm no-margin pull-left">
              <?php if ($paginacao == 1): ?>
                  <li class="disabled"><span>Primeira</span></li>
                  <li class="disabled"><span>Anterior</span></li>
              <?php else: ?>
                  <li><a href="?paginacao=1&nome=<?=$_REQUEST['nome']?>"><span>Primeira</span></a></li>
                  <li><a href="?paginacao=<?php print $paginacao-1;?>&nome=<?=$_REQUEST['nome']?>"><span>Anterior</span></a></li>
              <?php endif; ?>
              <!-- mostra até cinco páginas antes da página atual -->
              <?php foreach(array_reverse(range($paginacao-1, $paginacao-5)) as $pagina): ?>
                  <?php if ($pagina > 0): ?>
                      <li><a href="?paginacao=<?php print $pagina; ?>&nome=<?=$_REQUEST['nome']?>"><?php print $pagina; ?></a></li>
                  <?php endif; ?>
              <?php endforeach; ?>
              <!-- mostra a página atual para o usuário -->
             <li class="disabled"><span><?php print $paginacao; ?></span></li>
              <!-- mostra até cinco página após a página atual -->
              <?php foreach( range($paginacao+1, $paginacao+5) as $pagina): ?>
                  <?php if ($pagina < $totaldepaginas): ?>
                  <li><a href="?paginacao=<?php print $pagina; ?>&nome=<?=$_REQUEST['nome']?>"><?php print $pagina; ?></a></li>
                  <?php endif; ?>
              <?php endforeach; ?>
              <!-- mostra os links para a próxima página
              e para a última página da lista -->
              <?php if ($paginacao == $totaldepaginas): ?>
                  <li class="active"><span>Pr&oacute;xima</span></li>
                  <li class="active"><span>&Uacute;ltima</span></li>
              <?php else: ?>
                  <li><a href="?paginacao=<?php print $paginacao+1; ?>&nome=<?=$_REQUEST['nome']?>"><span>Pr&oacute;xima</span></a></li>
                  <li><a href="?paginacao=<?php print $totaldepaginas;?>&nome=<?=$_REQUEST['nome']?>"><span>&Uacute;ltima</span></a></li>
              <?php endif; ?>
          </ul>
          <!-- FIM DA PAGINACAO -->
          <div class="pull-right">
            <?php echo "Mostrando $paginacao de $totaldepaginas com $totalresultado registros.";?>
          </div>

          </div>
        </form>
</div>

<!-- MODAL REMOVER/INATIVAR/RESETAR SENHA-->
<div id="myModalRemove" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
        </div>
        <div class="modal-body">
           <div class="text-center">
              <div class="i-circle warning"><i class="fa fa-warning"></i></div>
              <h4>Atenção!</h4>
              <p>Deseja realmente continuar essa operação?</p>
              <p id="nomeUnidade"></p>
           </div>
           <input type="hidden" value="" id="idUnidade" name="idUnidade" />
        </div>
        <div class="modal-footer">
          <img id="submit-loading" class="display-none margin-right-1em" src="img/ajax-loader.gif" alt="" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Não, Cancelar</button>
          <button id="submit-btn-inativar" type="button" class="btn btn-warning" >Sim, inativar</button>
          <button id="submit-btn-deletar" type="button" class="btn btn-danger" >Sim, deletar</button>
          <button id="submit-btn-resetar-senha" type="button" class="btn btn-primary" >Sim, resetar senha</button>
        </div>
      </div>
  </div>
</div>

<?php
  
  // INCLUDE TEMPLATE
  include('../../template/footer.php');
?>