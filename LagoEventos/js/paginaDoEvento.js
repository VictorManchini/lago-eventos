/*
    idEvento, idUsuarioLogado e donoEvento estão sendo declarados no <script></script> do próprio html
*/
let excluirEvento = 0;

// Roda a funcao receberComentarios() quando a pagina abrir
$(document).ready(function(){
    receberComentarios(idEvento);

    $('#btn-enviar').on('click', function(){
        publicarComentario()
    });

    $('.input-comentario').keydown(function(event) {
        if (event.keyCode === 13) {
            publicarComentario()
        }
      });

    $('.sairEvento').on('click', function(){
        if(eventoInativo != 1) {
            $('.fade').toggleClass('active');
            $('.body').append(`<div class="telaSair"><p>Deseja Sair do Evento?</p><div class="telaSairButtons"><button class="telaSairButtonSim">Sim</button><button class="telaSairButtonNao">Não</button></div></div>`);
        }
    });

    $('.excluirEvento').on('click', function(){
        if(eventoInativo != 1) {
            excluirEvento = 1;
            $('.fade').toggleClass('active');
            $('.body').append(`<div class="telaSair"><p>Deseja Excluir o Evento?</p><div class="telaSairButtons"><button class="telaSairButtonSim">Sim</button><button class="telaSairButtonNao">Não</button></div></div>`);
        }
    });

    $('.terminarEvento').on('click', function(){
        if(eventoInativo != 1) {
            excluirEvento = 0;
            $('.fade').toggleClass('active');
            $('.body').append(`<div class="telaSair"><p>Deseja Terminar o Evento?</p><div class="telaSairButtons"><button class="telaSairButtonSim">Sim</button><button class="telaSairButtonNao">Não</button></div></div>`);
        }
    });

    $(document).on('click', '.telaSairButtonSim', function(){
        sairDoEvento(idEvento, idUsuarioLogado, donoEvento)
    })

    $(document).on('click', '.telaSairButtonNao', function(){
        $('.telaSair').remove();
        $('.fade').toggleClass('active');
    });

    $(document).on('click', '.removerADM', function(){
        if(eventoInativo != 1) {
            removerAdm()
        }
    });

    $(document).on('click', '.tornarADM', function(){
        if(eventoInativo != 1) {
            tornarAdm()
        }
    });

    $(document).on('click', '.expulsar', function(){
        if(eventoInativo != 1) {
            let usuarioAExpulsar = $('.expulsar').val()
            let dono = false;
            sairDoEvento(idEvento, usuarioAExpulsar, dono);
        }
    });

    document.addEventListener('click', function(event) {

        if (!event.target.closest('.popUpGerenciamento') && !event.target.closest('.figure-perfil-participante')) {
            $('.popUpGerenciamento').remove();            
        }
    });

    let perfils = document.querySelectorAll('.figure-perfil-participante');
    perfils.forEach(function(botao){
        botao.addEventListener('click', function(evento) {
            let tipoUser = evento.target.dataset.type;
            let id = evento.target.dataset.id;

            let x = evento.pageX, y = evento.pageY;

            const TIPO_USER_LOGADO = receberTipoUsuario();
                switch (TIPO_USER_LOGADO) {
                    case 'dono':
                        donoPopUp(tipoUser, id, x , y)
                        break;
                    case 'adm':
                        admPopUp(tipoUser, id, x , y)
                        break;
                    case 'user':
                        userPopUp(id, x , y)
                        break;
                }
        });
    });
});

function sairDoEvento(idEvento, idUsuarioLogado, donoEvento) {
    $.ajax({
        url: 'ajax/sairDoEvento.php',
        method: 'POST',
        data: {idEvento: idEvento, idUsuario: idUsuarioLogado, donoEvento: donoEvento, excluirEvento: excluirEvento},
        dataType: 'json',
        success: function() {
            window.location.href = 'lagoEventos.php';
        },
        error: function(xhr, status, error) {
            console.error('Erro na requisição AJAX:', error);
        }
    });
}

// Recebe o comentario inserido e publica
function publicarComentario() {
    let comentarioInserido = $('.input-comentario').val()
    $.ajax({
        url: 'ajax/publicarComentario.php',
        method: 'POST',
        data: {idEvento: idEvento, idUsuario: idUsuarioLogado, comentario: comentarioInserido},
        dataType: 'json',
        success: function() {
            $('.input-comentario').val('');
            receberComentarios(idEvento);
        }
    });
}

// Recebe os comentarios cadastrados e os insere na página
function receberComentarios(idEvento) {
    $('.container-comentario').remove();
    $.ajax({
        url: 'ajax/receberComentarios.php',
        method: 'POST',
        data: {idEvento: idEvento},
        dataType: 'json',
        success: function(resposta){
            for(let i = 0; i < resposta.length; i++){
                console.log(resposta[i]['idComentario']);
                $('.comentarios').append(`<div class="container-comentario" data-id="${resposta[i]['idUser']}" data-idComentario="${resposta[i]['idComentario']}"><img src="${resposta[i]['fotoUser']}" alt="foto de perfil de ${resposta[i]['nomeUser']}" class="comentarios-perfil"><div class="texto-comentario"><p>${resposta[i]['nomeUser']}</p><p>${resposta[i]['comentario']}</p></div></div>`);
            }
        }
    });
}

function donoPopUp(tipoUser, id, x, y) {
    if(tipoUser == 'dono') {
        $('.popUpGerenciamento').remove();
        $('.body').append(`<ul class="popUpGerenciamento"><li class="perfil"><a href="../perfil/perfil.php?id=${id}"><span class="material-symbols-outlined">person</span>Perfil</a></li></ul>`);
        $('.popUpGerenciamento').css('left', `${x}px`);
        $('.popUpGerenciamento').css('top', `${y}px`);
    }else if(tipoUser == 'adm') {
        $('.popUpGerenciamento').remove();
        $('.body').append(`<ul class="popUpGerenciamento"><li class="perfil"><a href="../perfil/perfil.php?id=${id}"><span class="material-symbols-outlined">person</span>Perfil</a></li><li class="removerADM" value="${id}"><span class="material-symbols-outlined">admin_panel_settings</span>Remover ADM</li><li class="expulsar" value="${id}"><span class="material-symbols-outlined">person_remove</span>Expulsar do Evento</li></ul>`);
        $('.popUpGerenciamento').css('left', `${x}px`);
        $('.popUpGerenciamento').css('top', `${y}px`);
    }else if(tipoUser == 'user') {
        $('.popUpGerenciamento').remove();
        $('.body').append(`<ul class="popUpGerenciamento"><li class="perfil"><a href="../perfil/perfil.php?id=${id}"><span class="material-symbols-outlined">person</span>Perfil</a></li><li class="tornarADM" value="${id}"><span class="material-symbols-outlined">admin_panel_settings</span>Tornar ADM</li><li class="expulsar" value="${id}"><span class="material-symbols-outlined">person_remove</span>Expulsar do Evento</li></ul>`);
        $('.popUpGerenciamento').css('left', `${x}px`);
        $('.popUpGerenciamento').css('top', `${y}px`);
    }
}

function admPopUp(tipoUser, id, x, y) {
    if(tipoUser == 'user') {
        $('.popUpGerenciamento').remove();
        $('.body').append(`<ul class="popUpGerenciamento"><li class="perfil"><a href="../perfil/perfil.php?id=${id}"><span class="material-symbols-outlined">person</span>Perfil</a></li><li class="expulsar" value="${id}"><span class="material-symbols-outlined">person_remove</span>Expulsar do Evento</li></ul>`);
        $('.popUpGerenciamento').css('left', `${x}px`);
        $('.popUpGerenciamento').css('top', `${y}px`);
    }else {
        $('.popUpGerenciamento').remove();
        $('.body').append(`<ul class="popUpGerenciamento"><li class="perfil"><a href="../perfil/perfil.php?id=${id}"><span class="material-symbols-outlined">person</span>Perfil</a></li></ul>`);
        $('.popUpGerenciamento').css('left', `${x}px`);
        $('.popUpGerenciamento').css('top', `${y}px`);
    }
    
}

function userPopUp(id, x, y) {
    $('.popUpGerenciamento').remove();
    $('.body').append(`<ul class="popUpGerenciamento"><li class="perfil"><a href="../perfil/perfil.php?id=${id}"><span class="material-symbols-outlined">person</span>Perfil</a></li></ul>`);
    $('.popUpGerenciamento').css('left', `${x}px`);
    $('.popUpGerenciamento').css('top', `${y}px`);
}

function receberTipoUsuario() {
    let tipoUserLogado = 'user';
    $.ajax({
        url: 'ajax/receberTipoUsuario.php',
        method: 'POST',
        async: false,
        data: {idUsuario: idUsuarioLogado, idEvento: idEvento},
        dataType: 'json',
        success: function(resposta){
            tipoUserLogado = resposta;
        }
    });
    return tipoUserLogado;
}

function receberTipoUsuarioComentario(idComentario) {
    let tipoUserLogadoComentario = 'user';
    let idcomentario = idComentario; 

    $.ajax({
        url: 'ajax/receberTipoUsuarioComentario.php',
        method: 'POST',
        async: false,
        data: {idUsuario: idUsuarioLogado, idEvento: idEvento, idComentario: idcomentario},
        dataType: 'json',
        success: function(respostaComentario){
            console.log(respostaComentario);
            tipoUserLogadoComentario = respostaComentario;
        }
    });
    return tipoUserLogadoComentario;
}

function removerAdm() {
    let usuarioARemover = $('.removerADM').val()
    $.ajax({
        url: 'ajax/removerAdm.php',
        method: 'POST',
        data: {idUser: usuarioARemover, idEvento: idEvento},
        dataType: 'json',
        success: function(){
            location.reload();
        }
    });
}

function removerComentario() {
    let comentarioARemover = $('.li-removerComentario').attr('value');
    $.ajax({
        url: 'ajax/removerComentario.php',
        method: 'POST',
        data: {idComentarioRemover: comentarioARemover},
        dataType: 'json',
        success: function(resposta){
            location.reload();
        }
    });
}

function editarComentario() {

}

function tornarAdm() {
    let usuarioAPromover = $('.tornarADM').val()
    $.ajax({
        url: 'ajax/tornarAdm.php',
        method: 'POST',
        data: {idUser: usuarioAPromover, idEvento: idEvento},
        dataType: 'json',
        success: function(){
            location.reload();
        }
    });
}



function donoComentario(id, x, y) {
    $('.popUpComentario').remove();
    $('.body').append(`<ul class="popUpComentario">
                            <li class="li-removerComentario" value="${id}">
                                <a class="removerComentario">
                                <span class="material-symbols-outlined">
                                    delete
                                </span>
                                    Excluir Comentário
                                </a>
                            </li>
                        </ul>`);
    // Defina o posicionamento com base nas coordenadas do clique
    $('.popUpComentario').css('left', x + 'px');
    $('.popUpComentario').css('top', y + 'px');
}

function donoEventoComentario(id, x, y) {
    $('.popUpComentario').remove();
    $('.body').append(`<ul class="popUpComentario">
                            <li class="li-removerComentario" value="${id}">
                                <a class="removerComentario">
                                    <span class="material-symbols-outlined">
                                        delete
                                    </span>
                                    Excluir Comentário
                                </a>
                            </li>
                        </ul>`);
    // Defina o posicionamento com base nas coordenadas do clique
    $('.popUpComentario').css('left', x + 'px');
    $('.popUpComentario').css('top', y + 'px');
}

$(document).on('click', '.removerComentario', function(){
    if(eventoInativo != 1) {
        removerComentario()
    }
});

let secaoComentarios = document.querySelector('.comentarios');
let comentarios = secaoComentarios.querySelectorAll('.comentarios-perfil');

document.addEventListener('click', function(event) {

    if (!event.target.closest('.popUpComentario') && !event.target.closest('.container-comentario')) {
        $('.popUpComentario').remove();            
    }
});


secaoComentarios.addEventListener('click', function(event) {
    if (event.target && event.target.classList.contains('container-comentario')) {
        $('.popUpComentario').remove(); 
        let idUser = event.target.dataset.id;
        let idComentario = event.target.dataset.idcomentario;
        let x = event.pageX, y = event.pageY;

        const TIPO_USER_LOGADO = receberTipoUsuarioComentario(idComentario);
                switch (TIPO_USER_LOGADO) {
                    case 'donoComentario':
                        donoComentario(idComentario, x , y)
                        break;
                    case 'donoEvento':
                        donoEventoComentario(idComentario, x , y)
                        break;
                }
    }
});