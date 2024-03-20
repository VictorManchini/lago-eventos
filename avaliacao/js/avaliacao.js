let btnAvaliar = document.querySelector('.btn-avaliar');
let btnNaoAvaliar = document.querySelector('.btn-naoAvaliar');

let stars = document.querySelectorAll('.ratings span');
let starsContainer = document.querySelectorAll('.ratings');

let rating = 0;

for(let star of stars) {
    star.addEventListener('click', function(){

        for(let s of stars) {
            s.removeAttribute('data-clicked');
        }
        this.setAttribute('data-clicked', 'true');
        
        rating = parseInt(this.dataset.rating);

        btnAvaliar.disabled = rating == 0
    });
}

btnNaoAvaliar.addEventListener('click', function(){
    $.ajax({
        url: '../avaliacao/ajax/removerAvaliacaoPendente.php',
        method: 'POST',
        data: {AvaliacaoPendenteEventoID: AvaliacaoPendenteEventoID, AvaliacaoPendenteUser: AvaliacaoPendenteUser},
        dataType: 'json',
        success: function(resposta) {
            location.reload();
        }
    });
});

btnAvaliar.addEventListener('click', function(){
    if(rating != 0) {
        $.ajax({
            url: '../avaliacao/ajax/avaliarDono.php',
            method: 'POST',
            data: {AvaliacaoPendenteEventoID: AvaliacaoPendenteEventoID, AvaliacaoPendenteUser: AvaliacaoPendenteUser, AvaliacaoPendenteDono: AvaliacaoPendenteDono, rating: rating},
            dataType: 'json',
            success: function(resposta) {
                location.reload();
            }
        });
    }
});