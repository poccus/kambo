$(document).ready(function(){

	var $bootstrapmodal 	= $('#bootstrap-modal');
	var $itemSelecionado 	= [];

	//mostrar mensagem
	$('#return-feedback').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );

	//mostrar calendário
	$('#calendario-excecao').datepicker({ todayBtn: true, todayHighlight: true, language: 'pt-BR', format: "dd/mm/yyyy" });

	//adicionar evento ao clicar na data selecionada do calendário
	$('#calendario-excecao').datepicker().on('changeDate', function (ev) {
		var $data = moment($("#calendario-excecao").datepicker("getDate")).format('DD/MM/YYYY');

		$('body').modalmanager('loading');
		projetouniversal.util.gethtml({
          url : PORTAL_URL+'view/excecao/formulario/',
          data : {excecao_data: $data},
          success : onSuccessDetails,
          error : onError
        });
        function onSuccessDetails(obj){
        	$bootstrapmodal.html(obj);
        	$bootstrapmodal.modal({width: 500});
        }
		return false;
	});

	//editar os dados
	$('a#edit-item-slc').livequery( "click", function(){
		var $idexcecao = $(this).attr('rel');
		$('body').modalmanager('loading');
		projetouniversal.util.gethtml({
          url : PORTAL_URL+'view/excecao/formulario/',
          data : {id: $idexcecao},
          success : onSuccessDetails,
          error : onError
        });
        function onSuccessDetails(obj){
        	$bootstrapmodal.html(obj);
        	$bootstrapmodal.modal({width: 500});
        	$('#excecao_data').removeAttr('readonly');
        	$('#excecao_datetimepicker').datepicker({ language: 'pt-BR', format: "dd/mm/yyyy" }).on('changeDate', function(ev){ $(this).datepicker('hide');});
        }
		return false;
	});

	//deletar item selecionado
	$('a#rmv-item-slc').livequery( "click", function(){
		bootbox.dialog({
			title: "Confirmação",
			message: "Você deseja realmente excluir as exceções selecionadas?",
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
						$itemSelecionadoa = [];
				        $('input[name="itemselecionado[]"]').each(function() { 
				            if(this.checked == true){
				            	$itemSelecionado.push( $(this).val() );
				            }             
				        });
				        projetouniversal.util.getjson({
				          url : PORTAL_URL+"php/excecao/deletar-excecao",
				          data : $('#form-table').serialize(),
				          success : onSuccessDelete,
				          error : onError
				        });
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