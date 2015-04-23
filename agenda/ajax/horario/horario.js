$(document).ready(function() {

  $('button#salvar_paciente').livequery( "click", function(){

    //contagem de error validate
    var pacienteValido = pacienteValidator();

      if( pacienteValido ){
        var queryString = $('#formpaciente').formSerialize();
        //var arquivos = $('#arquivoA').val();
        //var file           = $('#foto').val();
        // console.log(queryString);
        var options = { 
          data: queryString,
          type: 'POST',
          url: '../../php/paciente/salvar-paciente.php',
          beforeSend: function(){
              $('#myModalProgressBar').modal('show');
              $("#progress").show();
              $("#progress-bar").width('0%');
              $("#progress-bar").html("0%");
          },
          uploadProgress: function(event, position, total, percentComplete){
              $("#progress-bar").width(percentComplete+'%');
              $("#progress-bar").html(percentComplete+'%');
          },
          success: function(data){
              $("#progress-bar").width('100%');
              $("#progress-bar").html('100%');
              $('#myModalProgressBar').modal('hide');
          },
          complete: function(data){
            // console.log(data.responseText);
            //converter o resultado em JSON
            var returnJSON = JSON.parse( data.responseText );

            if(returnJSON.msg == 'error'){
              $('#alerta-retorno').addClass('alert-danger');
              $('#alerta-retorno').show();
              $('#mensagem-retorno').html('Error ao tentar efetuar o cadastro.');
            }else if(returnJSON.msg == 'success' ){
              $('#alerta-retorno').addClass('alert-success');
              $('#alerta-retorno').show();
              $('#mensagem-retorno').html('Cadastro efetuado com sucesso.');
            }

            // $('#myModalProgressBar').modal('hide');
            // $('#prev-modal').hide();
            //$('.modal-backdrop').addClass('display-none');
          },
          error: function(response){
            $('#myModalProgressBar').modal('hide');

            //converter o resultado em JSON
            var returnJSON = JSON.parse( data.responseText );
            if(returnJSON.msg == 'error'){
              $('#alerta-retorno').addClass('alert-danger');
              $('#alerta-retorno').show();
              $('#mensagem-retorno').html('Error ao tentar efetuar o cadastro.');
            }
          }
        };
        //ENVIAR DADOS VIA AJAX
        $('#formpaciente').ajaxSubmit(options);
      }//END IF
    return false;
  });

  

//acoes ------------------------------------------------------------------------

  function onError(args) {
    //console.log( 'onError: ' + args );
    alert("Error: "+args);
  }
  
});

function pacienteValidator(){
  var valido = true;

  $('input#paciente_nome').removeClass('error').next('label.error').remove();
  if($('input#paciente_nome').val() == ''){
    $('input#paciente_nome').addClass('error').after( '<label id="error" class="error">Campo obrigatório!</label>' );
    valido = false;
  }
  $('input#paciente_data_nascimento').removeClass('error').next('label.error').remove();
  if($('input#paciente_data_nascimento').val() == ''){
    $('input#paciente_data_nascimento').addClass('error').after( '<label id="error" class="error">Campo obrigatório!</label>' );
    valido = false;
  }
  $('input#paciente_telefone').removeClass('error').next('label.error').remove();
  if($('input#paciente_telefone').val() == ''){
    $('input#paciente_telefone').addClass('error').after( '<label id="error" class="error">Campo obrigatório!</label>' );
    valido = false;
  }
  $('input#paciente_email').removeClass('error').next('label.error').remove();
  if($('input#paciente_email').val() == ''){
    $('input#paciente_email').addClass('error').after( '<label id="error" class="error">Campo obrigatório!</label>' );
    valido = false;
  }
  return valido;
}




