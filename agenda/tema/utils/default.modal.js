//validacao para plugin SELECT2
var defaultModal = {};

(function($) {

  //public function
  defaultModal.util = {
    
    openmodal : function(args){
      
      args.modallarge ? 'not' : 'yes';
      if( args.modallarge == 'yes' ){
        $('#default-modal .modal-dialog').addClass('modal-lg');  
      }
      $('#default-modal').modal(args.open);
      $('#default-modal .modal-header .modal-title').html(args.title);
      if( args.loadurl == false ){
        $('#default-modal .modal-body #default-modal-container').html( $('#'+args.container).html() );
      }else{
        $('#default-modal .modal-body #default-modal-container').html('');
        $("#default-modal .modal-body #loading-modal").show();
        $('#default-modal .modal-body #default-modal-container').load(args.container , function(responseTxt, statusTxt, xhr){
            if(statusTxt == "success")
              $("#default-modal .modal-body #loading-modal").hide();
              $('#default-modal .modal-body #default-modal-container').show();
            if(statusTxt == "error")
              console.log("Error: " + xhr.status + ": " + xhr.statusText);
        });
      }
      args.submitdisplay ? 'not' : 'yes';
      if( args.submitdisplay == 'not' ){
        $('#default-modal .modal-footer #default-modal-submit').css('display', 'none');
      }else{
        $('#default-modal .modal-footer #default-modal-submit').html('');
        $('#default-modal .modal-footer #default-modal-submit').html(args.submitlabel);
        $('#default-modal .modal-footer #default-modal-submit').addClass(args.submitoptions);
        $('#default-modal .modal-footer #default-modal-submit').attr('id', ''+args.submitnewid);
      }
    },//end variable

    closemodal : function(args){
      
      $('#default-modal').modal('hide');

    },
    openmodalpermissao : function(args){
      
      $('#default-modal-permissao').modal(args.open);
      $('#default-modal-permissao .modal-header .modal-title').html(args.title);
      if( args.loadurl == false ){
        $('#default-modal-permissao .modal-body #default-modal-container').html( $('#'+args.container).html() );
      }else{
        $('#default-modal-permissao .modal-body #default-modal-container').load(args.container);
      }

    },
    openmodalduplicidadelogin : function(args){

      $('#default-modal-permissao').modal(args.open);
      $('#default-modal-permissao .modal-header .modal-title').html(args.title);
      if( args.loadurl == false ){
        $('#default-modal-permissao .modal-body #default-modal-container').html( $('#'+args.container).html() );
      }else{
        $('#default-modal-permissao .modal-body #default-modal-container').load(args.container);
      }
      $('#default-modal-permissao .modal-footer #default-modal-submit').hide();
      
    }
    //end variable

  }//end function


 })( jQuery );   