$(document).ready(function(){

    $("#formUsuario").validate({
        rules:{
            senha:{
                required:true
            },
            confirmacaoSenha:{
                required:true,
                equalTo:senha
            }
        },

        messages:{  
            senha:{
                required:"Por favor, informe a Senha"
            },
            confirmacaoSenha:{
                required:"Por favor, confirme a Senha",
                equalTo:"Senhas Diferentes"
            }
        },
        errorElement : 'div',
        errorPlacement: function(error, element) {
            var placement = $(element).data('error');
            if (placement) {
              $(placement).append(error)
            } else {
              error.insertAfter(element);
            }
          }
    });

});