$(document).ready(function(){

    //MARCAR TODOS OS INPUTS CHECKBOX DE UMA LISTA, E EXIBIR O BOTAO DE EXCLUIR
    $('#marcartodos').click( function(event) {
        if(this.checked) { // check select status
            $('input[name="itemselecionado[]"]').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"              
            });
            //mostrar os campo de excluir os item selecionados
            $('#operacao-excluir-selecionado').show();
            $('#rmv-li-slc').removeClass('disabled');
        }else{
            $('input[name="itemselecionado[]"]').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });
            $('#operacao-excluir-selecionado').hide();  
            $('#rmv-li-slc').addClass('disabled');      
        }
    });

    //FUNCAO PARA MOSTRAR O BOTAO DE EXCLUIR ITEM SELECIONADOS, CASO ESTEJA MARCADO PELO MENOS UM ITEM
    $('input[name="itemselecionado[]"]').click( function() {
        var count = 0;
        $('input[name="itemselecionado[]"]').each(function() { 
            if(this.checked == true){
                count++;
            }             
        });
        if(count > 0){
            // $('#operacao-excluir-selecionado').show();
            $('#rmv-li-slc').removeClass('disabled');
        }else{
            // $('#operacao-excluir-selecionado').hide();
            $('#rmv-li-slc').addClass('disabled');
        }  
    });


    //MARCAR TODOS OS MODULOS
    $('#marcartodosmodulos').click( function(event) {
        if(this.checked) { // check select status
            $('input[name="modulos[]"]').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"              
            });
        }else{
            $('input[name="modulos[]"]').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });  
        }
    });

    //MARCAR TODAS AS CATEGORIA DE MODULOS DE ACESSO
    // $('#marcartodossubmodulos').click( function(event) {
    //     if(this.checked) { // check select status
    //         $('input[name="submodulos[]"]').each(function() { //loop through each checkbox
    //             this.checked = true;  //select all checkboxes with class "checkbox1"              
    //         });
    //     }else{
    //         $('input[name="submodulos[]"]').each(function() { //loop through each checkbox
    //             this.checked = false; //deselect all checkboxes with class "checkbox1"                      
    //         });  
    //     }
    // });


    //funcao para habilitar a redefinição de senha do usuário
    $('a#redefinir-senha-usuario').livequery('click', function(){
        defaultModal.util.openmodal({
            open: {show: true, backdrop: true, keyboard: false},
            title: 'Redefinir senha do usuário',
            loadurl: true,
            container: PORTAL_URL+'usuario/redefinir-senha',
            submitlabel: 'Redefinir senha',
            submitoptions : 'btn-success',
            submitnewid: 'submit-btn-redefinir-senha'
        });
        //atribuindo o id do usuario para editar a senha do mesmo
        idnovasenha = $(this).attr('rel');
        return false;
    });

    $('#submit-btn-redefinir-senha').livequery('click', function(){
        var data = $('#formRedefinirSenha').formSerialize();
        var dataAdicional = '&idusuario='+idnovasenha;

        //limpar aviso dos campos
        $('#senhaNova').removeClass('error');
        $('#senhaNova').nextAll().remove();
        $('#senhaNovaRepita').removeClass('error');
        $('#senhaNovaRepita').nextAll().remove();

        if( $('#senhaNova').val() == ''  ||  $('#senhaNovaRepita').val() == '' ){
            $('#senhaNova').addClass('error');
            $('#senhaNova').after( '<label for="senhaNova" class="error">O campo não pode ser vazio</label>' );
            $('#senhaNova').focus();
            $('#senhaNovaRepita').addClass('error');
            $('#senhaNovaRepita').after( '<label for="senhaNova" class="error">O campo não pode ser vazio</label>' );
        }else if( $('#senhaNova').val() !=  $('#senhaNovaRepita').val() ){
            $('#senhaNovaRepita').addClass('error');
            $('#senhaNovaRepita').after( '<label for="senhaNova" class="error">Senha diferentes, por favor repita a senha corretamente</label>' );
            $('#senhaNovaRepita').focus();
        }else{
            $('#default-modal-loading').show();
            $('#default-modal-submit-redefinir-senha').attr('disabled', true);
            $('#default-modal-cancelar').attr('disabled', true);
            data = data + dataAdicional;
            projetouniversal.util.getjson({
                url : PORTAL_URL+"usuario/php/redefinir-senha",
                method : 'GET',
                contentType : "application/json",
                data : data,
                success : onSuccessRedefinirSenha,
                error : onError
            });
        }
        return false;
    });
    function onSuccessRedefinirSenha(obj){
        $('#default-modal-loading').hide();
        defaultModal.util.closemodal();
        if(obj.msg == 'error'){
            aler('Error ao tentar efetuar o cadastro.');
        }else if(obj.msg == 'success' ){
            alert(obj.msg_success+', Por favor faça o seu login novamente no sistema');
            window.location.href = PORTAL_URL+'logout';
        }
        $('#submit-btn-redefinir-senha').attr('disabled', false);
        $('#default-modal-cancelar').attr('disabled', false);
    }
    //---------------------------------------------------------------------------

    //FUNCAO DE RETORNO DE ERRO DO AJAX
    function onError(args) {
      console.log( 'onError: ' + args );
    }


});

// FUNCAO PARA SELECIONAR TODOS OS CHECKBOX
function selecionarTodosCheckBox(objeto){ 
    alert('teste');            
    var elementos = document.getElementsByClassName(""+objeto);
    for(var i=0;i<elementos .length;i++){
        elementos[i].checked = 1;
    }   
}

function checkAll(o){
    var boxes = document.getElementsByTagName("input");
    for (var x=0;x<boxes.length;x++){   
        var obj = boxes[x];
        if (obj.type == "checkbox"){
            if (obj.name!="chkAll") obj.checked = o.checked;
            }
        }
}
           
// FUNCAO PARA RETIRAR TODOS OS CHECK DOS BOXS SELECIONADOS
function retirarTodosCheckBoxSelecionados(objeto){
    var elementos = document.getElementsByClassName(""+objeto);
    for(var i=0;i<elementos.length;i++){
        elementos[i].checked = 0;   
    }   
}

// FUNCAO PARA VALIDAR EMAIL
function IsEmail(email){
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ( !expr.test(email) ){
        return false;
	}else{
		return true;
	}
}


//FUNCAO PARA FECHAR JANELA DO MODAL
function fecharModal(janela){
    $('#'+janela).modal('hide');
    return false;
}

//FUNCAO PARA REMOVER OS ACENTOS
function removeAcento(s){
    var r = s.toLowerCase();
    r = r.replace(new RegExp("\\s", 'g'),"");
    r = r.replace(new RegExp("[àáâãäå]", 'g'),"a");
    r = r.replace(new RegExp("æ", 'g'),"ae");
    r = r.replace(new RegExp("ç", 'g'),"c");
    r = r.replace(new RegExp("[èéêë]", 'g'),"e");
    r = r.replace(new RegExp("[ìíîï]", 'g'),"i");
    r = r.replace(new RegExp("ñ", 'g'),"n");                            
    r = r.replace(new RegExp("[òóôõö]", 'g'),"o");
    r = r.replace(new RegExp("œ", 'g'),"oe");
    r = r.replace(new RegExp("[ùúûü]", 'g'),"u");
    r = r.replace(new RegExp("[ýÿ]", 'g'),"y");
    r = r.replace(new RegExp("\\W", 'g'),"");
    return r;
}

function validarinput(id, msg){
    $(id).nextAll().remove();
    if( $(id).val() == '' || $(id).val() == null ){
        $(id).addClass('error');
        $(id).after('<label for="'+id+'" class="error">'+msg+'</label>');
        return false;
    }else{
        $(id).removeClass('error');
        $(id).nextAll().remove();
        return true;
    }
}//end function


function number_format (number, decimals, dec_point, thousands_sep) {
// * example 1: number_format(1234.56);
// * returns 1: '1,235'
// * example 2: number_format(1234.56, 2, ',', ' ');
// * returns 2: '1 234,56'
// * example 3: number_format(1234.5678, 2, '.', '');
// * returns 3: '1234.57'
// * example 4: number_format(67, 2, ',', '.');
// * returns 4: '67,00'
// * example 5: number_format(1000);
// * returns 5: '1,000'
// * example 6: number_format(67.311, 2);
// * returns 6: '67.31'
// * example 7: number_format(1000.55, 1);
// * returns 7: '1,000.6'
// * example 8: number_format(67000, 5, ',', '.');
// * returns 8: '67.000,00000'
// * example 9: number_format(0.9, 0);
// * returns 9: '1'
// * example 10: number_format('1.20', 2);
// * returns 10: '1.20'
// * example 11: number_format('1.20', 4);
// * returns 11: '1.2000'
// * example 12: number_format('1.2000', 3);
// * returns 12: '1.200'
// * example 13: number_format('1 000,50', 2, '.', ' ');
// * returns 13: '100 050.00'
// Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
    };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
} 


//funcao para converser valor monetário em float
function parseCurrency( valor ) {
    valor = valor.replace( '.' , '');
    valor = valor.replace( '.' , '');
    valor = valor.replace( '.' , '');
    valor = valor.replace( ',' , '.');
    return valor;
}

//funcao para converter float em valor monetário
function formatarValorFloaTParaMonetario(valor){
  valor = parseFloat(  valor  );
  valor = number_format(valor, 2, ',', '.');
  return valor;
}


function postToURL(path, params, method) {
  method = method || "post"; // Set method to post by default, if not specified.

  // The rest of this code assumes you are not using a library.
  // It can be made less wordy if you use one.
  var form = document.createElement("form");
  form.setAttribute("method", method);
  form.setAttribute("action", path);

  var addField = function( key, value ){
      var hiddenField = document.createElement("input");
      hiddenField.setAttribute("type", "hidden");
      hiddenField.setAttribute("name", key);
      hiddenField.setAttribute("value", value );

      form.appendChild(hiddenField);
  }; 

  for(var key in params) {
      if(params.hasOwnProperty(key)) {
          if( params[key] instanceof Array ){
              for(var i = 0; i < params[key].length; i++){
                  addField( key, params[key][i] )
              }
          }
          else{
              addField( key, params[key] ); 
          }
      }
  }

  document.body.appendChild(form);
  form.submit();
}


//calculo de diferença de horas retorno em secundos
function diferencaDeHorario(hora1, hora2){

  var today = new Date();
  var dd = today.getDate(); 
  var mm = today.getMonth()+1; 
  var yyyy = today.getFullYear();
  
  var data1 = new Date(mm+'/'+dd+'/'+yyyy+' '+hora1);
  var data2 = new Date(mm+'/'+dd+'/'+yyyy+' '+hora2);
  var sec = (data2.getTime()/1000.0) - (data1.getTime()/1000.0);

  return Math.round(sec);
}