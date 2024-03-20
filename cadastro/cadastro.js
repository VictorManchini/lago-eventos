$.validator.addMethod("emailCorreto", function(){

    let regexEmail = /^([a-zA-Z0-9._%\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,})$/
    let emailInformado = document.getElementById("emailInformado").value;
    
    if(regexEmail.test(emailInformado)){
        return true;
    }else{
        return false;
    }
});


$(document).ready(function(){

    $("#formUsuario").validate({
        rules:{
            nome:{
                required:true,
            },
            emailInformado:{
                required:true,
                emailCorreto:true
            },
            senha:{
                required:true
            },
            senhaConfirmacao:{
                required:true,
                equalTo:senha
            }
        },

        messages:{
            nome:{
                required:"Por favor, informe o seu nome",
            },
            emailInformado:{
                required:"Por favor, informe o E-mail",
                emailCorreto:"E-mail inválido"
            },
            senha:{
                required:"Por favor, informe a Senha"
            },
            senhaConfirmacao:{
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