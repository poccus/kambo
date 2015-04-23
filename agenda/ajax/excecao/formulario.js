$(document).ready(function () {

  /* busca de médico */
  $('.selectpicker').selectpicker();

  /* mascaras */
  $('#excecao_inicio').inputmask("99:99");
  $('#excecao_fim').inputmask("99:99");

  /* variaveis */
  var $optionsubmit = null;

  /* enviar formulário */
  $("#createform").validate({
      rules: {
        excecao_inicio: {required: true},
        excecao_fim: {required: true}
      },
      messages: {
        excecao_inicio:  "O Hora de início é obrigatório",
        excecao_fim:  "O Hora fim é obrigatório"
      },
      //função para enviar após a validação
      submitHandler: function( form ){
        var $diferencahorario =  diferencaDeHorario( $('#excecao_inicio').val(), $('#excecao_fim').val() );
        if( $('#excecao_profissional').selectpicker().val() == '' || $('#excecao_profissional').selectpicker().val() == null ){
          $('#return-status').addClass('alert-danger');
          $('#return-status').html('<i class="glyphicon glyphicon-info-sign"></i> Por favor selecione um Profissional');  
          $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
        }else if( $diferencahorario <= 0 ){
          $('#return-status').addClass('alert-danger');
          $('#return-status').html('<i class="glyphicon glyphicon-info-sign"></i> O horário de atendimento final não pode ser menor que o horário de atendimento inicial');
          $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
        }else{
          $('#return-feedback').hide();
          $('#msg-feedback').html('');
          $('.preload-submit').show();
          projetouniversal.util.getjson({
            url : PORTAL_URL+"php/excecao/salvar-excecao",
            data : $(form).serialize(),
            success : onSuccessSend,
            error : onError
          });
        }//end if
        function onSuccessSend(obj){
          switch( $optionsubmit ){
            case '_save':
              /* salvar e voltar para listagem */
              if( obj.msg == 'success' ){
                $('.preload-submit').hide();
                //enviar paramentros a url de listagem com mensagem
                postToURL(PORTAL_URL+'view/excecao/index', {id: obj.id, feedback: 'Cadastro efetuado com sucesso', type: 'success'});
              }else if( obj.msg == 'error' ){
                $('.preload-submit').hide();
                postToURL(PORTAL_URL+'view/excecao/index', {error: obj.error, feedback: 'Ocorreu um erro ao realizar a operação', type: 'error'});
              }
            break;
          }
        }//end function
        return false;
      }
  });

  /* enviar e editar */
  $('button#enviarformulario').livequery( "click", function(){
    $optionsubmit = $(this).val();
  });


  /* deletar item */
  //deletar item selecionado
  $('button#deletaritem').livequery( "click", function(){
    bootbox.dialog({
      title: "Confirmação",
      message: "Você deseja realmente excluir a exceção selecionada?",
      buttons: {
        main: {
          label: "Não",
          className: "btn-default"
        },
        danger: {
          label: "Sim",
          className: "btn-danger",
          callback: function() {
            $('body').modalmanager('loading');
            $idexcecao = $('#idexcecao').val(); 
            if( $idexcecao != '' || $idexcecao != null ){
              projetouniversal.util.getjson({
                url : PORTAL_URL+"php/excecao/deletar-excecao",
                data : {id: $idexcecao},
                success : onSuccessDelete,
                error : onError
              });
            }//end if
            function onSuccessDelete(obj){
              if( obj.msg == 'success' ){
                postToURL(PORTAL_URL+'view/excecao/index', {feedback: 'As informações foram deletadas com sucesso', type: 'success'});
              }else if( obj.msg == 'error' ){
                postToURL(PORTAL_URL+'view/excecao/index', {error: obj.error, feedback: '', type: 'error'});
              }
            }
          }
        }
      }
    });
    return false; 
  });


  /* erro do envio ajax */
  function onError(args) {
    console.log( 'onError: ' + args );
  }
  
});
