// Receber data de hoje
const TODAY = new Date();
let day = TODAY.getDate();
let month = TODAY.getMonth() + 1;
let year = TODAY.getFullYear();

// Adicionar 0 antes do numero se ele for menor que 10
day = day < 10 ? day.toString().padStart(2, '0'): day;
month = month < 10 ? month.toString().padStart(2, '0'): month;

// Criar String da data inteira
let fullDate = `${year}-${month}-${day}`;

// Adicionar atributo "min" com a data inteira para limitar a data inicial
$('#dateEvent').attr('min', `${fullDate}`);


// Validação para participantes não for nulo ou menor que 1
$.validator.addMethod("participantes", function() {
    let currentParticipants = $('#participantEvent').val();

    if(currentParticipants == '' || currentParticipants == null || currentParticipants <= 0) {
        return false;
    }else {
        return true;
    }   
});

// Validação para emails inválidos
$.validator.addMethod("emailCorreto", function(){

    let regexEmail = /^([a-zA-Z0-9._%\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,})$/
    let informedEmail = $('#userEmail').val()
    
    if(regexEmail.test(informedEmail)){
        return true;
    }else{
        return false;
    }
});

// Compara data minima (hoje) com a inserida
$.validator.addMethod("dataMinima", function() {
    let currentDate = $('#dateEvent').val();

    if(currentDate < fullDate) {
        return false;
    }else {
        return true;
    }
});

// Validação para categoria não for nulo ou menor que 1
$.validator.addMethod("categoriaValida", function(){
    let currentCategory = $('#categoryEvent').val();

    if(currentCategory == '' || currentCategory == null || currentCategory <= 0){
        return false;
    }else {
        return true;
    }
});

// Preencher dias
for(let i = 1; i <= 31; i++) {
    $('#userDay').append(`<option value=${i}>${i}</option>`);
}

// Preencher meses
const MONTHS = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
for(let i = 1; i <= 12; i++) {
    $('#userMonth').append(`<option value="${i}">${MONTHS[i - 1]}</option>`);
}

// Preencher anos
const MINIMUM_YEAR = 1900;
const CURRENT_YEAR = new Date().getFullYear();
for(let i = CURRENT_YEAR; i >= MINIMUM_YEAR; i--) {
    $('#userYear').append(`<option value="${i}">${i}</option>`)
}

// Valicação data de nascimento
$.validator.addMethod("dataNascValida", function(){
    const DAY_INPUT = $('#userDay').val();
    const MONTH_INPUT = $('#userMonth').val();
    const YEAR_INPUT = $('#userYear').val();
    console.log(YEAR_INPUT);
    const DATE = `${YEAR_INPUT}-${MONTH_INPUT}-${DAY_INPUT}`;

    if(YEAR_INPUT < MINIMUM_YEAR && YEAR_INPUT != null) {
        return false;
    }else {
        if(YEAR_INPUT == null) {
            return true;
        }else {
            if(isDateValid(DATE)) {
                return true;
            }else{
                return false;
            }
        }
    }
});

// Validação idade minima
$.validator.addMethod("idadeMinima", function(){
    const DAY_INPUT = $('#userDay').val();
    const MONTH_INPUT = $('#userMonth').val();
    const YEAR_INPUT = $('#userYear').val();
    const DATE = `${YEAR_INPUT}-${MONTH_INPUT}-${DAY_INPUT}`;

    const MINIMUM_AGE = 16;
    if(YEAR_INPUT == null) {
        return true;
    }else {
        if(getAge(DATE) >= MINIMUM_AGE){
            return true;
        }else {
            return false;
        }
    }

});

// Validação cpf valido
$.validator.addMethod("cpfValido", function(){
    let cpf= $('#userCPF').val()
    if(cpf == '') {
        return true
    }else {
    let removeFromCPF = "-.";
    let strCPF = removeCharactersRegex(cpf, removeFromCPF);
    let Sum;
    let Remains;
    Sum = 0;
  if (strCPF == "00000000000" || strCPF == "11111111111" || strCPF == "22222222222" || strCPF == "33333333333" || strCPF == "44444444444" || strCPF == "55555555555" || strCPF == "66666666666" || strCPF == "77777777777" || strCPF == "88888888888" || strCPF == "99999999999") return false;

  for (i=1; i<=9; i++) Sum = Sum + parseInt(strCPF.substring(i-1, i)) * (11 - i);
  Remains = (Sum * 10) % 11;

    if ((Remains == 10) || (Remains == 11))  Remains = 0;
    if (Remains != parseInt(strCPF.substring(9, 10)) ) return false;

  Sum = 0;
    for (i = 1; i <= 10; i++) Sum = Sum + parseInt(strCPF.substring(i-1, i)) * (12 - i);
    Remains = (Sum * 10) % 11;

    if ((Remains == 10) || (Remains == 11))  Resto = 0;
    if (Remains != parseInt(strCPF.substring(10, 11) ) ) return false;
    return true;
    }
});

// Validação se a senha coindcide com a do banco de dados
$.validator.addMethod('validarSenha', function(){ 
    let userID = $('#userID').val();
    let inputPassword = $('#userPassword').val();
    let validPassword = false;
    $.ajax({
        url: 'ajax/validarSenha.php',
        method: 'POST',
        async: false,
        data: {senhaInserida: inputPassword, idUser: userID},
        dataType: 'json',
        success: function(response) {
            validPassword = response.senhaValida;
        }
    })
    return validPassword;
});

// Validação se o email ja é utilizado
$.validator.addMethod('validarEmail', function(){ 
    let userID = $('#userID').val();
    let inputEmail = $('#userEmail').val();
    let validEmail = false;
    $.ajax({
        url: 'ajax/validarEmail.php',
        method: 'POST',
        async: false,
        data: {emailInserido: inputEmail, idUser: userID},
        dataType: 'json',
        success: function(response) {
            validEmail = response.emailValido;
        }
    })
    return validEmail;
});

// Jquery Validation
$(document).ready(function(){
    
    fillUserDescription();

    $("#formEvent").validate({

        rules:{
            nameEvent:{
                required:true,
                minlength: 4
            },
            descriptionEvent:{
                required:true,
                minlength: 7,
                maxlength: 250
            },
            dateEvent:{
                required:true,
                dataMinima: true
            },
            categoryEvent:{
                required:true,
                categoriaValida: true
            },
            participantEvent:{
                required:true,
                participantes: true
            },
            cepEvent:{
                required:true,
                minlength: 9
            },
            addressEvent:{
                required:true
            },
            placeNumberEvent:{
                required:true
            }
        },
        messages:{
            nameEvent:{
                required:"Campo Obrigatório",
                minlength: "Mínimo de 4 caracteres"
            },
            descriptionEvent:{
                required:"Campo Obrigatório",
                minlength: "Mínimo de 7 caracteres",
                maxlength: "Máximo de 250 caracteres"
            },
            dateEvent:{
                required:"Campo Obrigatório",
                dataMinima: "Data Minima é hoje"
            },
            categoryEvent:{
                required:"Campo Obrigatório",
                categoriaValida: "Categoria Inválida"
            },
            participantEvent:{
                required:"Campo Obrigatório",
                participantes: "O número não pode ser negativo ou igual a zero."
            },
            cepEvent:{
                required:"Campo Obrigatório",
                minlength: "CEP inválido"
            },
            addressEvent:{
                required:"Campo Obrigatório"
            },
            placeNumberEvent:{
                required:"Campo Obrigatório"
            }
        }
    });

    $('#formUser').validate({

        rules:{
            userName:{
                required:true
            },
            userEmail:{
                required:true,
                emailCorreto:true,
                validarEmail:true
            },
            descriptionUser:{
                maxlength: 150
            },
            userCPF:{
                cpfValido:true
            },
            userYear:{
                dataNascValida:true,
                idadeMinima:true
            },
            userPassword:{
                required:true,
                validarSenha:true
            },
            userPasswordConfirmation:{
                required:true,
                equalTo:userPassword
            }
        },
        messages :{
            userName:{
                required:"Campo Obrigatório"
            },
            userEmail:{
                required:"Campo Obrigatório",
                emailCorreto:"E-mail Inválido",
                validarEmail:"E-mail já Está em uso"
            },
            descriptionUser:{
                maxlength: "Máximo de 150 caracteres"
            },
            userCPF:{
                cpfValido:"CPF Inválido"
            },
            userYear:{
                dataNascValida:"Data inválida",
                idadeMinima:"Minimo de 16 anos"
            },
            userPassword: {
                required:"Campo Obrigatório",
                validarSenha:"Senha Incorreta"
            },
            userPasswordConfirmation:{
                required:"Campo Obrigatório",
                equalTo:"Senhas Diferentes"
            }
        }
    });
});

// Mascara input CEP
$('#cepEvent').mask('00000-000');
$('#userCPF').mask('000.000.000-00');

// Preencher campos automaticamente de acordo com CEP
$("#cepEvent").blur(function(){
    let cep = this.value.replace(/[^\d]/g,"");
    let urlCep = `https://viacep.com.br/ws/${cep}/json/`;

    $.getJSON(urlCep, function(resultCep){

        $("#neighboorhoodEvent").val(resultCep.bairro);
        $("#addressEvent").val(resultCep.logradouro);
    });
});

// Indicar visualmente o limite de carateres da descrição
$('#descriptionEvent').on('input', function(){
    fillEventDescription()
});

function fillEventDescription() {
    let descriptionEvent = $('#descriptionEvent').val();
    $('#counterDescriptionEvent').html(`${descriptionEvent.length}/250`)
    if(descriptionEvent.length > 250) {
        $('#counterDescriptionEvent').css('color','red');
    }else {
        $('#counterDescriptionEvent').css('color','black');
    }
}

$('#descriptionUser').on('input', function(){
    fillUserDescription()
});

function fillUserDescription() {
    let descriptionUser = $('#descriptionUser').val();
    $('#counterDescriptionUser').html(`${descriptionUser.length}/150`)
    if(descriptionUser.length > 150) {
        $('#counterDescriptionUser').css('color','red');
    }else {
        $('#counterDescriptionUser').css('color','black');
    }
}

// Trocar qual form é visivel
let btn01 = $('#btn-criar-evento');
let btn02 = $('#btn-editar-perfil');
let form01 = $('#formEvent');
let form02 = $('#formUser');

let = btn01ON = true;

btn01.on('click', function(){
    if (!btn01ON) {
        btn02.removeClass('active');
        btn01.addClass('active');
        form02.removeClass('active');
        form01.addClass('active');
        btn01ON = true;
    }
});

btn02.on('click', function(){
    if (btn01ON) {
        btn01.removeClass('active');
        btn02.addClass('active');
        form01.removeClass('active');
        form02.addClass('active');
        btn01ON = false;
    }
});

// Trocar qual tipo de evento é visivel
let btn01ParticipatedEvents = $('#participatedEventsButton');
let btn02CreatedEvents = $('#myEventsButton');
let divParticipatedEvents = $('#participatedEvents');
let divCreatedEvents = $('#myEvents');

let = btnEventsON = true;

btn01ParticipatedEvents.on('click', function(){
    if (!btnEventsON) {
        console.log("teste1");
        btn02CreatedEvents.removeClass('active-btn');
        btn01ParticipatedEvents.addClass('active-btn');
        divCreatedEvents.removeClass('active-events');
        divParticipatedEvents.addClass('active-events');
        btnEventsON = true;
    }
});

btn02CreatedEvents.on('click', function(){
    if (btnEventsON) {
        console.log("teste2");
        btn01ParticipatedEvents.removeClass('active-btn');
        btn02CreatedEvents.addClass('active-btn');
        divParticipatedEvents.removeClass('active-events');
        divCreatedEvents.addClass('active-events');
        btnEventsON = false;
    }
});

// Função para checar se data é valida
function isDateValid(dateString) {
    let dateParts = dateString.split("-");
    let validationDay = parseInt(dateParts[2]);
    let validationMonth = parseInt(dateParts[1]);
    let validationYear = parseInt(dateParts[0]);

    let validationDate = new Date(validationYear, validationMonth - 1, validationDay);
    return validationDate.getFullYear() === validationYear && validationDate.getMonth() === validationMonth - 1 && validationDate.getDate() === validationDay;
}

// Função para receber idade por data
function getAge(dateString) {
    const TODAY = new Date();

    const BIRTH_DATE = new Date(dateString);

    let age = TODAY.getFullYear() - BIRTH_DATE.getFullYear();

    const MONTH = TODAY.getMonth() - BIRTH_DATE.getMonth();

    if(MONTH < 0 || (MONTH === 0 && TODAY.getDate() < BIRTH_DATE.getDate())) {
        age--;
    }
    return age;
}

//Função para remover caracteres especiais do CPF
function removeCharactersRegex(cpf, removeFromCPF) {
    const regexPattern = new RegExp(`[${removeFromCPF.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}]`, 'g');
    return cpf.replace(regexPattern, '');
}



const imageInput = document.querySelector("#imageEvent");
const imgPreview = document.querySelector(".imgPreviewEvent");

const imageInputPerfil = document.querySelector("#imageUser");
const imgPreviewPerfil = document.querySelector(".imgPreviewUser");

imageInput.addEventListener("change", function() {
    const reader = new FileReader();
    reader.onload = function(event) {
        const uploadImagem = reader.result;
        imgPreview.style.backgroundImage = `url(${uploadImagem})`;
        imgPreview.classList.remove("imgPreview");       
    }
    reader.readAsDataURL(this.files[0]);
})

imageInputPerfil.addEventListener("change", function() {
    const reader = new FileReader();
    reader.onload = function(event) {
        const uploadImagem = reader.result;
        imgPreviewPerfil.style.backgroundImage = `url(${uploadImagem})`;
        imgPreviewPerfil.classList.remove("imgPreview");       
    }
    reader.readAsDataURL(this.files[0]);
})



var dataAtual = new Date();
var dia = dataAtual.getDate();
var mes = dataAtual.getMonth() + 1;
var ano = dataAtual.getFullYear();


if (mes <= 9) {
    $('#datepicker').val(dia+"/0"+mes+"/"+ano);
} else {
    $('#datepicker').val(dia+"/"+mes+"/"+ano);
}

$("#datepicker").datepicker({
    minDate: new Date(ano, mes - 1, dia),
    showButtonPanel: false,
    beforeShow: function(input, inst) {
        setTimeout(function() {
            var datepicker = $("#ui-datepicker-div");
            var left = ($(window).width() - datepicker.width()) / 2 + $(window).scrollLeft();
            
            var articlePosition = $(".dateEvent").offset();
            var articleHeight = $(".dateEvent").outerHeight();
            datepicker.css({
                "left": left + "px",
                // "position": "fixed", // opcional: usar posição fixa para garantir que o calendário permaneça centralizado ao rolar a página
                "top": articlePosition.top + articleHeight + 10, 
                // "transform": "translateY(-50%)" 
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