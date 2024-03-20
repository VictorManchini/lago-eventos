var ativo = true

function mostrarCategorias(button) {
    var categorias = document.getElementById('categorias-secundaria');
    var img = document.getElementById('img-dropdown');
    
    if (ativo) {
        categorias.style.display = "flex";
        img.classList.add('abrirDropdown');
        ativo = false;
    } else {
        categorias.style.display = "none";
        ativo = true;
        img.classList.remove('abrirDropdown');
    }
    
}



function mostrarEventos() {
    var eventosOcultos = document.getElementById('eventos-extras');
    var btnMaisEventos = document.getElementById('btn-mais-eventos')

    var btnMenosEventos = document.getElementById('btn-menos-eventos')


    if (ativo == true) {
        eventosOcultos.style.display = 'flex';
        btnMaisEventos.style.display = 'none';
        btnMenosEventos.style.display = 'inline-block';
        setTimeout(function() {
            eventosOcultos.style.opacity = '1';
            btnMaisEventos.style.opacity = '0';
            btnMenosEventos.style.opacity = '1';    
        }, 100);
        ativo = false;
    } else {
        eventosOcultos.style.opacity = '0';
        btnMaisEventos.style.opacity = '1';
        btnMenosEventos.style.opacity = '0';    
        setTimeout(function() {
            eventosOcultos.style.display = 'none';
            btnMaisEventos.style.display = 'inline-block';
            btnMenosEventos.style.display = 'none';     
        }, 500);
        ativo = true;
        
    }  
    console.log(ativo)
}



