$(document).ready(function(){
    let u_userID = $('#userID').val();
    getRating(u_userID);
});

// Receber avaliação (estrelas)
function getRating(u_userID) {
    $.ajax({
        url: 'ajax/receberAvaliacao.php',
        method: 'POST',
        data: {userID: u_userID},
        dataType: 'json'
    }).done(function(result){
        amountPerStar = divideRatingByStar(result.approximateAverage);
        $('.container-rating-imgProfile').prepend(`<div class="rating" id="rating" name="rating" data-tooltip="${result.approximateAverage}⭐ Avaliações: ${result.ratingAmount}"></div>`)
        for(let i = 0; i < amountPerStar.length; i++) {
            $('#rating').append(`<div class="star" id="star0${i + 1}" name="star0${i + 1}"><div class="star-fill" id="star0${i + 1}-fill" name="star0${i + 1}-fill" style="width: ${Math.ceil(amountPerStar[i] * 100)}%"></div><div class="star-baseline"></div></div>`)
        }   
    });
}

// Dividir avaliação por cada estrela
function divideRatingByStar(rating) {
    const stars = [0, 0, 0, 0, 0];
    let index = 0;

    while (rating > 0 && index < stars.length) {
        const REMAINING_RATING = 1.00 - stars[index];
        const value = Math.min(REMAINING_RATING, rating);
        stars[index] += value;
        rating -= value;
        index++;
    }

    return stars;
}