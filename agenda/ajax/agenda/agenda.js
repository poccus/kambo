$(document).ready(function() {

  var $bootstrapmodal = $('#bootstrap-modal');
  var hoje = new Date();
  renderCalendar(hoje);
  
  //define o datapicker do filtro por data
  $( "#data_pesquisa" ).inputmask("99/99/9999");
  $( "#calendariodatapesquisa" ).datepicker({
    dateFormat: 'dd/mm/yy',
    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    onSelect: function(dateText, inst) {
      var tmp = dateText.split("/");
      dataAgenda = tmp[2]+"-"+tmp[1]+"-"+tmp[0];
      if($("#profissionalagenda").val()==""){
        alert("Selecione primeiramente um médico");
      }else{
        var date    = moment.tz(dateText, "America/Porto_Acre|America/Rio_Branco").format();
        $("#calendar").fullCalendar( 'gotoDate', dataAgenda );
        //renderCalendar(dataAgenda);
      }
    }
  });
  //filtro por data e médico
  function retornaSourceFiltroData(profissional, data){
    var start_source = {
      type:'GET',
      data: {profissional:profissional, data: data, filter:'false'},
      url: PORTAL_URL+'php/agenda/consultas.php',//?profissional='+$("#profissionalagenda").val(),
      backgroundColor: 'red'
    };
    return start_source;
  }
  //filtro por médico
  function retornaSource(profissional){
    var start_source = {
      type:'GET',
      data: {profissional:profissional,filter:'false'},
      url: PORTAL_URL+'php/agenda/consultas.php',//?profissional='+$("#profissionalagenda").val(),
      backgroundColor: 'red'
    };
    return start_source;
  }
  //funcao que muda o profissional e aplica o filtro para o profissional escolhido
  $( "#profissionalagenda" ).change(function() {
    $('#calendar').fullCalendar('removeEventSource', retornaSource($(this).val()));
    $('#calendar').fullCalendar('addEventSource', retornaSource($(this).val()));
    $('#calendar').fullCalendar('rerenderEvents'); 
    if($(this).val()!=""){
      $('#profissional-slc h1').html( $('#profissionalagenda option:selected').text() );
      $('#profissional-slc').delay(300).fadeIn();
      carregaSalaEspera($(this).val()); 
    }else{
      $("#salaespera").html("");
    }
    return false;
  });
  //funcao de retorno de erro do projeto uitl
  function onError(args) {
    //console.log( 'onError: ' + args );
    alert("Error: "+args);
  }
  //funcao para somar minutos a hora
  function somaMinutos(hora, minutos){
    var tmpH = hora.split(":");
    var hm = parseInt(tmpH[0])*60;
    var totalm = parseInt(tmpH[1])+hm; 
    var total = totalm+minutos;
    var resto = total%60;
    var hora = Math.floor(total/60);
    if(hora < 10) hora = "0"+hora;
    if(resto < 10) resto = "0"+resto;
    return hora+":"+resto;
  }
  //carrega o calendario com as consultas
  function renderCalendar(dataAgenda){
    //var hoje = new Date();
    var url = PORTAL_URL+'php/agenda/consultas.php';
    $('#calendar').fullCalendar({
      header: {
      center: 'prev,next today',
      left: 'title',
      right: 'month,agendaWeek,agendaDay'
    },
    titleFormat: '[Agendamento de ] DD [de] MMMM [de] YYYY',
    // columnFormat: {
    //     month: 'ddd',    
    //     week: 'ddd d/M', 
    //     day: 'dddd d/M'  
    // },
    defaultView: 'agendaWeek',
    minTime: '08:00',
    maxTime: '21:00',
    slotMinutes: '45',
    allDaySlot:false,
    lang: 'pt-br',
    businessHours:[
      {
        start: '08:00', // a start time (10am in this example)
        end: '12:00', // an end time (12pm in this example)
        dow: [ 1,2,3,4,5,6]
      },
      {
        start: '14:00', // a start time (10am in this example)
        end: '18:00', // an end time (12pm in this example)
        dow: [ 1,2,3,4,5,6]
      }
      ],
      ignoreTimezone : true,
      lazyFetching:false,
      defaultDate: dataAgenda,
      axisFormat:'H:mm',
      editable: false,
      eventLimit: true, // allow "more" link when too many events
      eventClick: function(calEvent, jsEvent, view) {
        var id = calEvent.id;
        $('body').modalmanager('loading');
        projetouniversal.util.gethtml({
          url: PORTAL_URL+'view/agenda/modal-detalhes/',
          data: {id: id},
          success: onSuccessDetails,
          error: onError
        });
        /*function onSuccessDetails(obj){
          $bootstrapmodal.html(obj);
          $bootstrapmodal.modal();
        }*/
        //retornaDados(calEvent.id);
        //$('#detalhesConsulta').modal('show');
      },
      dayClick: function(date, jsEvent, view) {

        var date    = moment(date, "America/Rio_Branco").format('L hh:mm');
        var tmpdate = date.split(" ");

        if($("#profissionalagenda").val() == ""){
          $('#return-status').addClass('alert-danger');
          $('#return-status').html('<i class="glyphicon glyphicon-remove"></i> Selecione primeiramente um Profissional');
          $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
        }else{
          $('body').modalmanager('loading');
          var data_consulta = tmpdate[0];
          var horario = tmpdate[1];
          var profissional = $("#profissionalagenda").val();
          projetouniversal.util.gethtml({
            url: PORTAL_URL+'view/agenda/modal-nova-consulta',
            data: {profissional: profissional, data_consulta: data_consulta, horario: horario},
            success: onSuccessNewAgenda,
            error: onError
          });
        }

      },
      eventSources:[retornaSource($("#profissionalagenda").val())],
      viewDisplay: function (view) {
      
      },
      loading: function(bool) {
        $('#loading').toggle(bool);
      }
    });
    return false;
  }
  //chama o modal e seta algumas propiedades
  function onSuccessNewAgenda(obj){
    $bootstrapmodal.html("");
    $bootstrapmodal.html(obj);
    $bootstrapmodal.modal();
     $('input#nome').autocomplete({  
       serviceUrl: PORTAL_URL+"php/agenda/lista-pacientes.php",
       minChars:3,
       maxHeight:150,
       onSelect: function (suggestion) {
        $("#paciente").val(suggestion.id);
        $("#telefone").val(suggestion.telefone);
        $("#email").val(suggestion.email);
        //alert(suggestion.id);
       }
     });
     //define o datapicker da data da consulta
    $( "input#data_consulta" ).inputmask("99/99/9999");
    $( "input#telefone" ).inputmask("(99)9999-9999[9]");
    $( "input#data_consulta" ).datepicker({
      dateFormat: 'dd/mm/yy',
      dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
      dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
      dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
      monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
      monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
      onSelect: function(dateText, inst) {
        //var tmp = dateText.split("/");
        //dataAgenda = tmp[2]+"-"+tmp[1]+"-"+tmp[0];
      }
    });
    //mascara para o horário
    $( "#horario" ).inputmask("99:99");
  }

  //chama o modal e seta algumas propiedades
  function onSuccessDetails(obj){
    $bootstrapmodal.html("");
    $bootstrapmodal.html(obj);
    $bootstrapmodal.modal({width: 800});
  }
  //Monta a tela com os detalhes da consulta
  function retornaDados(id){
    $('#conteudo').html('');
    $.ajax({
      type: 'GET',
      data: 'id='+id,
      url: PORTAL_URL+'php/agenda/load-paciente.php',
      beforeSend: function() {
        $("#carregando").html("<img src='img/loader.gif' width='18' heigth='18'>Carregando"); //Carregando
      },
      error: function() {
        $("#carregando").html("Há algum problema com a fonte de dados");
      },
      success: function(retorno){
        $("#carregando").html('');
        $('#conteudo').html(retorno);
      }
    });
    return false;
  }


  $('a#remarcar').livequery( "click", function(){
    var idconsulta = $("#idconsulta").val();
    $('body').modalmanager('loading');
    projetouniversal.util.gethtml({
      url: PORTAL_URL+'view/agenda/modal-remarcacao',
      data: {consulta: idconsulta},
      success: onSuccessRemarcar,
      error: onError
    });
  });
  $('a#remarcarFiltro').livequery( "click", function(){
    var idconsulta = $(this).attr('rel');
    $('body').modalmanager('loading');
    projetouniversal.util.gethtml({
      url: PORTAL_URL+'view/agenda/modal-remarcacao',
      data: {consulta: idconsulta},
      success: onSuccessRemarcar,
      error: onError
    });
  });
  function onSuccessRemarcar(obj){
    //if(obj.msg == "success"){
      $bootstrapmodal.html("");
      $bootstrapmodal.html(obj);
      $bootstrapmodal.modal();
      $( "input#dataRemarcacao" ).inputmask("99/99/9999");
      $( "input#dataRemarcacao" ).datepicker({
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        onSelect: function(dateText, inst) {
          var tmp = dateText.split("/");
          dataAgenda = tmp[2]+"-"+tmp[1]+"-"+tmp[0];
          carregaHorariosData($("#idprofissionalr").val(), dataAgenda);
        }
      });
      //mascara para o horário
      $( "#horarioRemarcacao" ).inputmask("99:99");
      $('body').modalmanager('removeLoading');
   // }else{

    //}
  }

  

  //salva o agendamento
  $('a#salvarAgendamento').livequery( "click", function(){
    $(".preload-submit").show();
    var nome = $('#nome').val();
    var paciente = $('#paciente').val();
    var telefone = $('#telefone').val();
    var email = $('#email').val();
    var profissional = $( "#profissional" ).val();
    var horario = $('#horario').val();
    var data_consulta = $('#data_consulta').val();
    var duracao = $('#duracao').val();
    projetouniversal.util.getjson({
      url : PORTAL_URL+"php/agenda/salvar-agendamento.php",
      method : 'GET',
      contentType : "application/json",
      data : {paciente: paciente, nome: nome, telefone: telefone, email: email, profissional: profissional, horario: horario, data_consulta: data_consulta, duracao: duracao},
      success: function(data){salvarAgendamento(data, profissional);},
      error : onError
    });
    return false;
  });
  function salvarAgendamento(obj, profissional){
    if(obj.msg == "success"){
      $bootstrapmodal.modal('hide');
      $(".preload-submit").hide();
      $('#calendar').fullCalendar('removeEventSource', retornaSource($("#profissionalagenda").val()));
      $('#calendar').fullCalendar('addEventSource', retornaSource($("#profissionalagenda").val()));
      $('#calendar').fullCalendar('rerenderEvents');
      $('body').modalmanager('removeLoading');
      $('#return-status').removeClass('alert-danger');
      $('#return-status').removeClass('alert-success');
      $('#return-status').addClass('alert-success');
      $('#return-status').html('<span class="glyphicon glyphicon-ok"></span> Consulta marcada com sucesso.');
      $('#return-status').show();
      $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
      carregaSalaEspera(profissional);
    }else{
      alert("Erro ao agendar");
    }
  }

  $('a#salvarRemarcacao').livequery( "click", function(){
    $('body').modalmanager('loading');
    var idconsulta = $('#idconsulta').val();
    var profissional = $('#idprofissionalr').val();
    var dataRemarcacao = $('#dataRemarcacao').val();
    var horarioRemarcacao = $('#horarioRemarcacao').val();
    var duracaoRemarcacao = $('#duracaoRemarcacao').val();
    projetouniversal.util.getjson({
      url : PORTAL_URL+"php/agenda/salvar-remarcacao.php",
      method : 'GET',
      contentType : "application/json",
      data : {idconsulta: idconsulta, dataRemarcacao: dataRemarcacao, horarioRemarcacao: horarioRemarcacao, duracaoRemarcacao: duracaoRemarcacao},
      //success : salvarRemarcacao,
      success: function(data){salvarRemarcacao(data, profissional);},
      error : onError
    });
  });
  function salvarRemarcacao(obj, profissional){
    if(obj.msg == "success"){
      $bootstrapmodal.modal('hide');
      $('#calendar').fullCalendar('removeEventSource', retornaSource($("#profissionalagenda").val()));
      $('#calendar').fullCalendar('addEventSource', retornaSource($("#profissionalagenda").val()));
      $('#calendar').fullCalendar('rerenderEvents');
      $('body').modalmanager('removeLoading');
      $('#return-status').show();
      $('#return-status').addClass('alert-success');
      $('#return-status').html('<span class="glyphicon glyphicon-ok"></span> Consulta remarcada com sucesso.');
      $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
      if($("#profissionalagenda").val()!="")
        carregaSalaEspera($("#profissionalagenda").val());
      //carregaSalaEspera(profissional);
      /*var profissional = $( "#profissional option:selected" ).val();
      $("#profissionalagenda").val(profissional);
      $('#calendar').fullCalendar ('removeEvents');
      var start_source1 = {
        type:'GET',
        data: {profissional:$("#profissionalagenda").val(),filter:'true'},
        url: PORTAL_URL+'php/agenda/consultas.php?profissional='+$("#profissionalagenda").val(),
        backgroundColor: 'red'
      };
      $('#calendar').fullCalendar('addEventSource', start_source1).fullCalendar( 'refetchEvents' );
      //mostraListaConsultas(profissional);
      $(".notificacao-loading").hide();
      //alert("Agendado com sucesso");*/
    }else{
      alert("Erro ao agendar");
    }
  }
  //funcao para carregar os horarios da consulta de acordo com o profissional escolhido
  // carregaHorarios(1);
  function carregaHorarios(idprofissional){
    $.getJSON(PORTAL_URL+'php/agenda/lista-horarios.php?idprofissional='+idprofissional, function (dados){ 
      var tempo = 0;
      if (dados.length > 0){ 
        var option = '<option>Selecione o Horário</option>'; 
        $.each(dados, function(i, obj){ 
          tempo = obj.tempo;
          option += '<option value="'+obj.horario+'">'+obj.horario+'</option>'; 
        }) 
        //$('#mensagem').html('<span class="mensagem">Total de paises encontrados.: '+dados.length+'</span>'); 
        $('#horario').html(option).show(); 
        $("#duracao").val(tempo);
      }else{ 
        //Reset(); 
        //$('#mensagem').html('<span class="mensagem">Não foram encontrados paises!</span>'); 
      } 
    });
  }
  //funcao para carregar o horario no formulario de remarcacao de consulta, retorna os horários livres
  function carregaHorariosData(idprofissional, dataConsulta){
    //alert(idprofissional+" "+dataConsulta );
    $.getJSON(PORTAL_URL+'php/agenda/lista-horarios-data.php?id='+idprofissional+'&data='+dataConsulta, function (dados){ 
      var tempo = 0;
      if (dados.length > 0){ 
        var option = '<option>Selecione o Horário</option>'; 
        $.each(dados, function(i, obj){ 
          //alert(obj);
          //tempo = obj.tempo;
          option += '<option value="'+obj+'">'+obj+'</option>'; 
        }) 
        //$('#mensagem').html('<span class="mensagem">Total de paises encontrados.: '+dados.length+'</span>'); 
        //alert(option);
        $('select#horarioRemarcacao').html(option).show(); 
        //$("#duracao").val(tempo);
      }else{ 
        //Reset(); 
        //$('#mensagem').html('<span class="mensagem">Não foram encontrados paises!</span>'); 
      } 
    });
  }
  //implementa o botao chegou para atualizar o status da consulta e popular a sala de espera
  $('a#chegou').livequery( "click", function(){
    $('body').modalmanager('loading');
    var mydata = $(this).attr('data-rel');
    var id = $(this).attr('rel');
    var tipo = "chegou";
    projetouniversal.util.getjson({
      url : PORTAL_URL+"php/agenda/atualizar-agendamento.php",
      method : 'GET',
      contentType : "application/json",
      data : {id: id, tipo: tipo},
      success: function(data){chegou(data, mydata);},
      error : onError
    });
    return false;
  });
  function chegou(obj, mydata){
    if(obj.msg == "success"){
      $('#calendar').fullCalendar('removeEventSource', retornaSource($("#profissionalagenda").val()));
      $('#calendar').fullCalendar('addEventSource', retornaSource($("#profissionalagenda").val()));
      $('#calendar').fullCalendar('rerenderEvents');
      $('body').modalmanager('removeLoading');
      $('#return-status').show();
      $('#return-status').addClass('alert-success');
      $('#return-status').html('<span class="glyphicon glyphicon-ok"></span> Status atualizado com sucesso.');
      if($("#profissionalagenda").val()!="")
        carregaSalaEspera(obj.profissional);
      $bootstrapmodal.modal('hide');
      $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
    }else{
      alert("Erro ao atualizar");
    }
    return false;
  }

  //implementa o botao confirmar atendimento para atualizar o status da consulta
  $('a#confirmaragendamento').livequery( "click", function(){
    $('body').modalmanager('loading');
    var mydata = $(this).attr('data-rel');
    var id = $(this).attr('rel');
    var tipo = "confirmaragendamento";
    projetouniversal.util.getjson({
      url : PORTAL_URL+"php/agenda/atualizar-agendamento.php",
      method : 'GET',
      contentType : "application/json",
      data : {id: id, tipo: tipo},
      success : confirmaragendamento,
      //success: function(data){chegou(data, mydata);},
      error : onError
    });
    return false;
  });
  function confirmaragendamento(obj){
    if(obj.msg == "success"){
      $('#calendar').fullCalendar('removeEventSource', retornaSource($("#profissionalagenda").val()));
      $('#calendar').fullCalendar('addEventSource', retornaSource($("#profissionalagenda").val()));
      $('#calendar').fullCalendar('rerenderEvents');
      $('body').modalmanager('removeLoading');
      $('#return-status').show();
      $('#return-status').removeClass('alert-danger');
      $('#return-status').removeClass('alert-success');
      $('#return-status').addClass('alert-success');
      $('#return-status').html('<span class="glyphicon glyphicon-ok"></span> Consulta confirmada com sucesso');
      $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
      $bootstrapmodal.modal('hide');
    }else{
      alert("Erro ao atualizar");
    }
    return false;
  }

  //implementa o botao confirmar atendimento para atualizar o status da consulta
  $('a#confirmaratendimento').livequery( "click", function(){
    $('body').modalmanager('loading');
    var mydata = $(this).attr('data-rel');
    var id = $(this).attr('rel');
    var tipo = "confirmaratendimento";
    projetouniversal.util.getjson({
      url : PORTAL_URL+"php/agenda/atualizar-agendamento.php",
      method : 'GET',
      contentType : "application/json",
      data : {id: id, tipo: tipo},
      success : confirmaratendimento,
      error : onError
    });
    return false;
  });
  function confirmaratendimento(obj){
    if(obj.msg == "success"){
      $('#calendar').fullCalendar('removeEventSource', retornaSource($("#profissionalagenda").val()));
      $('#calendar').fullCalendar('addEventSource', retornaSource($("#profissionalagenda").val()));
      $('#calendar').fullCalendar('rerenderEvents');
      $('body').modalmanager('removeLoading');
      $('#return-status').show();
      $('#return-status').removeClass('alert-danger');
      $('#return-status').removeClass('alert-success');
      $('#return-status').addClass('alert-success');
      $('#return-status').html('<span class="glyphicon glyphicon-ok"></span> O paciente começou o atendimento');
      $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
      if($("#profissionalagenda").val()!="")
        carregaSalaEspera(obj.profissional);
    }else{
      alert("Erro ao atualizar");
    }
    return false;
  }

  //implementa o botao confirmar atendimento para atualizar o status da consulta
  $('a#cancelar').livequery( "click", function(){
    $('body').modalmanager('loading');
    var mydata = $(this).attr('data-rel');
    var id = $(this).attr('rel');
    var tipo = "cancelar";
    projetouniversal.util.getjson({
      url : PORTAL_URL+"php/agenda/atualizar-agendamento.php",
      method : 'GET',
      contentType : "application/json",
      data : {id: id, tipo: tipo},
      success : cancelar,
      //success: function(data){chegou(data, mydata);},
      error : onError
    });
    return false;
  });
  function cancelar(obj){
    if(obj.msg == "success"){
      $('#calendar').fullCalendar('removeEventSource', retornaSource($("#profissionalagenda").val()));
      $('#calendar').fullCalendar('addEventSource', retornaSource($("#profissionalagenda").val()));
      $('#calendar').fullCalendar('rerenderEvents');
      $('#return-status').show();
      $('#return-status').addClass('alert-success');
      $('#return-status').html('<span class="glyphicon glyphicon-ok"></span> Consulta cancelada com sucesso');
      $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
      $bootstrapmodal.modal('hide');
      if($("#profissionalagenda").val()!="")
        carregaSalaEspera(obj.profissional); 
      $('body').modalmanager('removeLoading');
    }else{
      alert("Erro ao atualizar");
    }
    return false;
  }

  //implementa o botao enviar email de lembrete da consulta
  $('a#enviaremail').livequery( "click", function(){
    $('body').modalmanager('loading');
    //var mydata = $(this).attr('data-rel');
    var id = $(this).attr('rel');
    //var tipo = "enviaremail";
    projetouniversal.util.getjson({
      url : PORTAL_URL+"php/agenda/enviar-email.php",
      method : 'GET',
      contentType : "application/json",
      data : {id: id},
      success : enviarEmail,
      //success: function(data){chegou(data, mydata);},
      error : onError
    });
    return false;
  });
  function enviarEmail(obj){
    if(obj.msg == "success"){
      //$('#calendar').fullCalendar('removeEventSource', retornaSource($("#profissionalagenda").val()));
      //$('#calendar').fullCalendar('addEventSource', retornaSource($("#profissionalagenda").val()));
      //$('#calendar').fullCalendar('rerenderEvents');
      $('#return-status').show();
      $('#return-status').addClass('alert-success');
      $('#return-status').html('<span class="glyphicon glyphicon-ok"></span> Consulta cancelada com sucesso');
      $bootstrapmodal.modal('hide');
      $('body').modalmanager('removeLoading');
      $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
    }else{
      alert("Erro ao atualizar");
    }
    return false;
  }

  //funcoes para listar a sala de espera
 function carregaSalaEspera(profissional){
  projetouniversal.util.getjson({
      url : PORTAL_URL+"php/agenda/lista-salaespera.php",
      method : 'GET',
      contentType : "application/json",
      data : { id: profissional },
      success : listaSalaEspera,
      error : onError
    });
 }

 function listaSalaEspera(obj){
    $("#salaespera").html("");
    var itens = "";
    if(obj != null){
      var mydata = new Date();
      itens += '<div class="table-responsive margin-top-0-5em">';
      itens += "<table id='tabela-lista-dados' class='table table-striped fontsize11px'><thead><tr><th scope='col'>#</th><th scope='col'>Paciente</th><th scope='col'>&nbsp;</th></tr></thead><tbody>";
      for(var i = 0; i < obj.length; i++){
        itens += "<tr><td>"+(i+1)+"</td>";
        itens += "<td>"+obj[i].paciente+"</td>";
        itens += "<td><a href='#' class='btn btn-success btn-xs' rel="+obj[i].id+" title='Confirmar Atendimento' id='confirmaratendimento'><i class='glyphicon glyphicon-thumbs-up'></i></a> <a href='#' class='btn btn-danger btn-xs' rel="+obj[i].id+" title='Cancelar consulta' id='cancelar'><i class='glyphicon glyphicon-remove'></i></a></td></tr>";
      }
      itens += "</tbody></table>";
      itens += '</div>';
      $("#salaespera").html(itens);  
    }else{
      $("#salaespera").html("Nenhum paciente.");  
    }
  }

  $('a#filtrobuscapaciente').livequery( "click", function(){
    $('body').modalmanager('loading');
    var nome = $("#filtronome").val();
    projetouniversal.util.gethtml({
          url: PORTAL_URL+'view/agenda/modal-consultas-paciente/',
          data: {nome: nome},
          success: listaConsultasPaciente,
          error: onError
        });
  });

  function listaConsultasPaciente(obj){
    $bootstrapmodal.html("");
    $bootstrapmodal.html(obj);
    $bootstrapmodal.modal({width:780});
    $('body').modalmanager('removeLoading');
  }

});