jQuery(document).ready(function($) { 

    $( "#form-token" ).submit(function( event ) {
        var ajaxurl = $("#ajaxurl").val();
        var key = $("#key-input").val();
        var token = $("#token-input").val();
        toggleLoad('tokenInput');
        $.ajax({
            url: ajaxurl, 
            type: 'POST',
            data: {
                'action': 'conecta_trello', 
                'wtp_token': token, 
                'wtp_key': key
            },
            success: function( data ){
               toggleLoad('tokenInput');
               if (data == 'true') {
                     $('#tokenInput').append('<span class="dashicons dashicons-yes-alt"></span>');
               } else{
                    $('#tokenInput').append('<span class="dashicons dashicons-no"></span>');
                    alert('Token inválido');
               }
            }
        });
        return false;
    });

    function loadTriggers() {
        toggleLoad('painel');
        var ajaxurl = $("#ajaxurl").val();
        $.ajax({
            url: ajaxurl, 
            datatype: 'JSON',
            contentType: "application/json; charset=utf-8",
            data: {
                'action': 'load_trigger'
            },
            success: function( retorno ){
                if (retorno != '' && typeof  jQuery.parseJSON(retorno) =='object') {
                    retorno = JSON.parse(retorno);
                    $.each( retorno, function( key, value ) {
                        if ($( "#tabela-acoes tbody tr" ).eq(key).length == 0 )  {
                            addLinha();
                        }
                        $('.selectacao').eq(key).val(value[0]);
                        $('.selectlista').eq(key).val(value[1]);
                    });
                }
            }
        });
        toggleLoad('painel');
        return false;
    }
    function addLinha() {
        $( "#tabela-acoes tbody tr:first-child" ).clone().appendTo( "#tabela-acoes tbody" );
        var qtde =  $( "#tabela-acoes tbody tr" ).length;
        var indice = qtde-1;
        $( "#tabela-acoes tbody tr:last-child td:first-child" ).html(qtde);
        $(".acao-linha a:eq("+indice+")").click( function() {
            removeLinha(indice);
        });
    }
    

    function toggleLoad(id){
        var pluginurl = $("#pluginurl").val();
        id = '#'+id;
        $(id+' .dashicons').remove();
        if ($('#loadGiff').length) {
            $('#loadGiff').remove();
        } else {
            $(id).append('<img src="'+pluginurl+'/img/load.gif" id="loadGiff" />');
        }
    }

    function removeLinha(indice){
        if (confirm('Deseja realmente remover ?')) {
            $( "#tabela-acoes tbody tr" ).eq(indice).remove();
        }
    }

    
    $(".acao-linha a").click( function() {
        removeLinha($(this).index());
    });

    $("#btn-adicionar-linha").click(function() {
      addLinha();
      return false;
    });

    loadTriggers();

});

