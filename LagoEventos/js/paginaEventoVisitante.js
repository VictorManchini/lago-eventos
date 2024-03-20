/*
    idUser, idEvento, qtdeParticipantes e dataEvento sendo declarados no <script></script> do próprio html
*/

$(document).on('click', '.btnPopup', function() {
    $('.fade').toggleClass('active');
    $('.popupEntrar').remove();
});

$(document).on('click', '.telaEntrarButtonNao', function() {
    $('.fade').toggleClass('active');
    $('.telaEntrar').remove();
});

$('.btnEntrar').on('click', function() {
    $.ajax({
        url: 'ajax/numeroParticipantes.php',
        method: 'POST',
        data: {idUser: idUser, idEvento: idEvento, qtdeParticipantes: qtdeParticipantes, dataEvento: dataEvento, eventoInativo: eventoInativo},
        dataType: 'json',
        success: function(resultado) {
            if(resultado == 'EntradaValida') {
                $('.fade').toggleClass('active');
                $('.body').append(`<div class="telaEntrar"><p>Deseja Entrar no Evento?</p><div class="telaEntrarButtons"><button class="telaEntrarButtonSim" onclick="entrarNoEvento()">Sim</button><button class="telaEntrarButtonNao">Não</button></div></div>`);
            }else if (resultado == 'expirado') {
                $('.fade').toggleClass('active');
                $('.body').append(`<div class="popupEntrar"><p>Evento Terminado</p><button class="btnPopup">OK</button></div>`);
            }else if (resultado == 'naoLogado') {
                $('.fade').toggleClass('active');
                $('.body').append(`<div class="popupEntrar"><p>Faça o Login</p><button class="btnPopup">OK</button></div>`);
            }else if (resultado == 'cheio') {
                $('.fade').toggleClass('active');
                $('.body').append(`<div class="popupEntrar"><img style='width: 100%; height: 70%; object-fit: cover;' src='../img/paginaInicial/paginaDoEvento/lotarEventos.png'><button class="btnPopup">OK</button></div>`);;
            }
        }
    });
});

function entrarNoEvento() {
    $('.telaEntrar').remove();
    $.ajax({
        url: 'ajax/entrarNoEvento.php',
        method: 'POST',
        data: {idUser: idUser, idEvento: idEvento},
        dataType: 'json',
        success: function(resultado){
            if(resultado == 'sucesso') {
                $('.fade').toggleClass('active');
                location.reload();
            }else if (resultado == 'erro') {
                $('.fade').toggleClass('active');
                $('.body').append(`<div class="popupEntrar"><p>Erro</p><button class="btnPopup">OK</button></div>`);
            }
        }
    })
}