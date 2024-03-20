$(document).ready(function(){
    var dataAtual = new Date();
    var dia = dataAtual.getDate();
    var mes = dataAtual.getMonth() + 1;
    var ano = dataAtual.getFullYear();

    var dataAtual = ano+"-"+mes+"-"+dia;

    var dataFormat = null;

    if (mes <= 9) {
        $('#datepicker').val(dia+"/0"+mes+"/"+ano);
        var dataAtual = ano+"-0"+mes+"-"+dia;
    } else {
        $('#datepicker').val(dia+"/"+mes+"/"+ano);
        var dataAtual = ano+"-"+mes+"-"+dia;
    }
    

    $("#datepicker").datepicker({
        minDate: new Date(ano, mes - 1, dia),
        beforeShow: function(input, inst) {
            setTimeout(function() {
                var datepicker = $("#ui-datepicker-div");
                var left = ($(window).width() - datepicker.width()) / 2 + $(window).scrollLeft();
                
                var articlePosition = $(".article-categorias").offset();
                var articleHeight = $(".article-categorias").outerHeight();
                datepicker.css({
                    "left": left + "px",
                    "top": articlePosition.top + articleHeight + 50, 
                });
            }, 0);
            $('main').addClass('no-scroll');
            $('header').addClass('no-scroll');
        },
        onClose: function() {
            $('main').removeClass('no-scroll');
            $('header').removeClass('no-scroll');
        }
    });
    $.datepicker.setDefaults( $.datepicker.regional[ "pt-BR" ] );
    
    $("#datepicker").change(filtrar);

    let string_u_valor = 1;

    let listaCategorias = document.querySelector(".listaCategorias");
    let categoriasBTN = listaCategorias.querySelectorAll("button");

    // let idCategoria = listaCategorias.querySelector("button");
    // let li = listaCategorias.querySelector("button li");
    // idCategoria.id = 'ativo';  
    // li.id = 'ativo'; 

    let listaCategoriasResponsivo = document.querySelector(".categorias-telas-menores");
    let idCategoriaResponsivo = listaCategoriasResponsivo.querySelector("button");
    let liResponsivo = listaCategoriasResponsivo.querySelector("button li");
    idCategoriaResponsivo.id = 'ativo';  
    liResponsivo.id = 'ativo'; 

    var parteEspecifica = document.querySelector('.article-eventos');
    let inputPesquisa = document.getElementById("campo-busca"); 
    
    let pegarURL = new URLSearchParams(window.location.search);
    let pesquisaOutraPagina = pegarURL.get('search');
    let resultadoPesquisaOutraPagina = pegarURL.get('resultadoPesquisa');

    var idCategoria = 0;
    preencherEventos(idCategoria, dataAtual, dataFormat);

    categoriasBTN.forEach(categoria =>{
        categoria.addEventListener('click', function() {
            categoriasBTN.forEach(c => {
                c.id = null;
                var li = c.querySelector("li");
                li.id = null;
            })
            this.id = 'ativo';
            var li = this.querySelector("li");
            li.id = 'ativo';

            string_u_valor = 1;
            idCategoria = this.value

            $('.msg-result-evento').remove();
            $('#datepicker').val(dia+"/0"+mes+"/"+ano);
            preencherEventos(idCategoria, dataAtual, dataFormat);
        })
    })

    if (pesquisaOutraPagina == 'true') {
        pesquisaEventos(resultadoPesquisaOutraPagina);
        parteEspecifica.scrollIntoView({ behavior: 'smooth' });
        inputPesquisa.value = "";
    }

    $('#campo-busca').keydown(function(event) {
        if (event.keyCode === 13) {
            pesquisaEventos(inputPesquisa.value);
            parteEspecifica.scrollIntoView({ behavior: 'smooth' });
            inputPesquisa.value = "";
        }
    });

    $(".btn-pesquisa").on("click", function() {   
        categoriasBTN.forEach(c => {
            c.id = null;
            var li = c.querySelector("li");
            li.id = null;
        })
        let inputPesquisa = document.getElementById("campo-busca");
        idCategoria = 0;

        pesquisaEventos(inputPesquisa.value);
        parteEspecifica.scrollIntoView({ behavior: 'smooth' });
        inputPesquisa.value = "";
        
    })

    $(".pagination").on("click", ".page-link", function(e){
        string_u_valor = e.target.value;
        preencherEventos(idCategoria, dataAtual, dataFormat);
    });

    function filtrar() {
        var dataSelec = $( "#datepicker" ).datepicker( "getDate");
        var dataFormat = $.datepicker.formatDate('yy-mm-dd', dataSelec);
        $('.result-pesquisa p').remove();
        preencherEventos(idCategoria, dataAtual, dataFormat);
    }

    function preencherEventos(idCategoria, dataAtual, dataSelecionada){
        let u_itensPagina = 6;
        let u_valor = parseInt(string_u_valor);
        let categoria = idCategoria;
        console.log(categoria)
        $.ajax({
            url: 'ajax/numeroEventos.php',
            method: 'POST',
            data: {pagina: u_valor, itensPagina: u_itensPagina, idCategoria: categoria, dataAtual: dataAtual, dataSelecionada: dataSelecionada},
            dataType: 'json'
        }).done(function(resultado){    
            console.log(resultado) 
            $('.col').remove();
            if (resultado == "erro") {
                $('.row').append('<div class="col msg-erro-eventos" ><img src="../img/paginaInicial/erroEventos.png" class="img-erro"></div>');
            } else {
                let i = 0;
                resultado.forEach(evento => {
                    $('.row').append(`<div class="col"><div class="card"><div class="card-body card-evento-body"><figure><img src="${evento["imagemEvento"]}" class="card-img-top" alt="..."><div class="container-texto-evento"><figcaption class="figcaption${i}"><p>${evento["nomeEvento"]}</p></figcaption></div></figure><button class="btn-saiba-mais btn-outros-eventos"><a href="paginaDoEvento.php?id=${evento["idEvento"]}"> Saiba Mais </a></button></div></div></div>`);
                });
            } 
            j = 0;
        });

        $.ajax({
            url: 'ajax/paginacao.php',
            method: 'POST',
            data: {pagina: u_valor, itensPagina: u_itensPagina, idCategoria: categoria},
            dataType: 'json'
        }).done(function(resultadoPaginacao){
            console.log(resultadoPaginacao);
            if(resultadoPaginacao == 1) {
                $('.pagination').css('display', 'none');
               
            } else {
                $('.pagination').css('display', 'flex');
                
            }
            
            $('.page-link').remove();
            if(u_valor > 1) {
                $('.pagination').append(`<button class="page-link" value="${u_valor - 1}">&laquo;</button>`);
            }
            for (let k = 1; k <= resultadoPaginacao; k++) {
                let active = (k == u_valor) ? 'active' : '';
                $('.pagination').append(`<button class="page-link ${active}" value="${k}">${k}</button>`);
            }
            if (u_valor < resultadoPaginacao) {
                $('.pagination').append(`<button class="page-link" value="${u_valor + 1}">&raquo;</button>`);
            }
        });
    }


    function pesquisaEventos(pesquisa, dataAtual) {
        $.ajax({
            url: 'ajax/eventosPesquisar.php',
            method: 'POST',
            data: {pesquisa: pesquisa, dataAtual: dataAtual},
            dataType: 'json'
        }).done(function(eventos){
            $('.col').remove();
            $('.result-pesquisa p').remove();
            $('.pagination').css('display', 'none');
            $('.result-pesquisa').append(`<p class="msg-result-evento">Resultados da pesquisa: "${pesquisa}"</p>`);
            if (eventos == "erro") {
                $('.row').append('<div class="col msg-erro-eventos"><img src="../img/paginaInicial/erroPesquisa.png" class="img-erro"></div>');
            } else { 
                eventos.forEach(evento => {
                    $('.row').append(`<div class="col"><div class="card"><div class="card-body card-evento-body"><figure><img src="${evento["imagemEvento"]}" class="card-img-top" alt="..."><div class="container-texto-evento"><figcaption class="figcaption"><p>${evento["nomeEvento"]}</p></figcaption></div></figure><button class="btn-saiba-mais btn-outros-eventos"><a href="paginaDoEvento.php?id=${evento["idEvento"]}"> Saiba Mais </a></button></div></div></div>`);
                });
            }
        })
    }
});