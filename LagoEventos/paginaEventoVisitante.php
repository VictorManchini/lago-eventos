<?php
    session_start();

    if(isset($_GET['id']) && !empty($_GET['id'])){
        $idEvento = $_GET['id'];

        include('../conexao.php');

        $sqlEvento = 'SELECT * FROM evento WHERE idEvento = '.$idEvento.'';
        $resultadoEvento = mysqli_query($conexao, $sqlEvento);
        if(mysqli_num_rows($resultadoEvento) > 0) {
            $eventoInfo = mysqli_fetch_assoc($resultadoEvento);

            if(isset($_SESSION['nome'])) {
                
                $idUser = $_SESSION['id'];
                
                $sqlRelacao = 'SELECT * FROM relacaoevento WHERE usuario = '.$idUser.' AND evento = '.$idEvento.' AND idRelacao = "'.$idUser.'_'.$idEvento.'"';
                $resultadoRelacao = mysqli_query($conexao, $sqlRelacao);

                if(mysqli_num_rows($resultadoRelacao) > 0) {
                    
                    header('location: paginaDoEvento.php?id='.$idEvento.'');
                    
                }
            }

            $sqlCategoria = 'SELECT * FROM categoria WHERE idCategoria = '.$eventoInfo["idCategoria"].'';
            $resultadoCategoria = mysqli_query($conexao, $sqlCategoria);
            $categoriaInfo = mysqli_fetch_assoc($resultadoCategoria);
?>

            <!DOCTYPE html>
            <html lang="pt-br">
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $eventoInfo['nomeEvento']; ?></title>
            <link rel="shortcut icon" href="../img/cadastro&login/logo-reduzido.png" type="image/x-icon">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"/>
            <link rel="stylesheet" href="css/paginaEventoVisitante.css">
            <link rel="stylesheet" href="../header/header.css">
            <link rel="stylesheet" href="../footer/footer.css">
            <link rel="stylesheet" href="../avaliacao/avaliacao.css">
            </head>
            <body class="body">
                <div class="fade"></div>
                <div class="fundo">
                    <?php 
                        include('../header/header.php');
                        include ("../avaliacao/popUpAvaliacao.php");
                        $urlLagoInicial = "lagoEventos.php";  
                    ?>
                    <main>
                        <a href="lagoEventos.php">
                            <span class="material-symbols-outlined backarrow">
                                arrow_back_ios
                            </span>
                        </a>
                        <div class="conteudo">
                            <h1 class="tituloEvento"><?php echo $eventoInfo['nomeEvento']; ?></h1>
                            <div class="container-imgEvento">
                                <img src="<?php echo $eventoInfo['imagemEvento']; ?>" class="imgEvento">
                            </div>
                            <section class="informacoesEvento">
                                <p class="categoria-responsivo">#<?php echo $categoriaInfo['nomeCategoria']; ?></p>
                                <article class="localizacaoEvento">
                                    <div class="container-endereco">
                                        <p class="endereco"><?php echo ''.$eventoInfo["enderecoEvento"].', '.$eventoInfo["numeroLugarEvento"].' / Ap.'.$eventoInfo["complementoEvento"].''; ?></p>
                                        <p class="bairro"><?php echo $eventoInfo['bairroEvento']; ?></p>
                                    </div>
                                    <figure class="container-figureEndereco">
                                        <div class="container-imgEndereco">
                                            <img src="../img/paginaInicial/paginaDoEvento/mapa.png">
                                        </div>
                                        <figcaption><?php echo $eventoInfo["CEPEvento"]; ?></figcaption>
                                    </figure>
                                </article>
                                <p class="categoria">#<?php echo $categoriaInfo['nomeCategoria']; ?></p>
                                <article class="entrarEvento">
                                    <div class="container-entrarTexto">
                                        <p class="entrarTexto">Quer entrar no evento?</p>
                                        <p class="btnEntrar">Clique Aqui</p>
                                    </div>
                                    <figure class="container-figureEntrar">
                                        <div class="container-imgEntrar">
                                            <img src="../img/paginaInicial/paginaDoEvento/aviao.png">
                                        </div>
                                        <figcaption><?php
                                            $dataArray = explode('-', $eventoInfo['dataEvento']);
                                            $dataFormatada = ''.$dataArray[2].'/'.$dataArray[1].'/'.$dataArray[0].'';
                                            echo $dataFormatada;
                                        ?></figcaption>
                                    </figure>
                                </article>
                            </section>
                            <p class="descricaoEvento"><?php echo $eventoInfo['descricaoEvento']; ?></p>
                        </div>
                    </main>
                    <?php include "../footer/footer.html" ?>
                </div>
<?php
        // Evento Não Existente
        }else {
            header('location: lagoEventos.php');
        }
    // GET ID não existente
    }else {
        header('location: lagoEventos.php');
    }
?>
<script src="../JQuery/jquery-3.7.1.min.js"></script>
<script src="../avaliacao/js/avaliacao.js"></script>
<script>
<?php
    if(isset($_SESSION['nome'])) {
?>
    let idUser = <?php echo $idUser; ?>;
<?php
    }else {
?>
    let idUser = 0;
<?php
    }
?>
    let idEvento = <?php echo $idEvento; ?>;
    let qtdeParticipantes = <?php echo $eventoInfo['qtdeParticipantes']; ?>;
    let dataEvento = <?php echo json_encode($eventoInfo["dataEvento"]); ?>;
    let eventoInativo = <?php echo $eventoInfo['terminadoManualmente']; ?>;
</script>
<script src="js/paginaEventoVisitante.js"></script>
<script>
    var btnSearch = document.getElementById("btn-pesquisa");
    var inputPesquisa = document.getElementById("campo-busca");
    btnSearch.addEventListener("click", function() {
        window.location.href = "lagoEventos.php?search=true&resultadoPesquisa=" + encodeURIComponent(inputPesquisa.value);
    })
    inputPesquisa.addEventListener("keydown", function(event) {
        if (event.keyCode == 13) {
            window.location.href = "../lagoEventos/lagoEventos.php?search=true&resultadoPesquisa=" + encodeURIComponent(inputPesquisa.value);
        }
    })
</script> 
</body>
</html>