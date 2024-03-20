<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lago Eventos</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="shortcut icon" href="../img/cadastro&login/logo-reduzido.png" type="image/x-icon">
    
    <link rel="stylesheet" type="text/css" href="../jQuery/jquery-ui-1.13.2.custom/jquery-ui.css">
    <!-- Bootstrap JS e Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-eebBp8F2UHzgRfZfRJCWSg3bs9MRw4xkzF/Q5QfFs0uVc8JrfoLsGQC/e5ynSdOY" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    

    <link rel="stylesheet" href="css/lagoEventos.css">
    <link rel="stylesheet" href="../header/header.css">
    <link rel="stylesheet" href="../footer/footer.css">
    <link rel="stylesheet" href="../avaliacao/avaliacao.css">
</head>
<body>
    <?php
        include '../conexao.php';
        include ("../header/header.php");
        include ("../avaliacao/popUpAvaliacao.php");
        $urlLagoInicial = "lagoEventos.php";
    ?>
    <main>
        <section class="secao-evento-impulsionado">
            <!-- É necessário implementar o código com o banco de dados para puxar o nome do evento, imagem e a categoria em destaque-->
            <div class="div-evento-impulsionado">
                <?php include 'eventoAleatorio.php'; ?>
                <!-- Na palavra destaque, colocar a categoria em destaque -->
                <h1>Evento de <span class="palavra-destaque">#<?php 
                        $infosCat = categoriaAleatorio();
                        echo $infosCat[1];     
                     ?>
                 </span> Impulsionado</h1>
                
                <figure class="figure-evento-impulsionado img-fluid">
                    <!-- Imagem do evento em destaque -->
                    <?php $infosEvento = eventoAleatorio($infosCat[0]); ?>
                    <img src="<?php echo $infosEvento[1] ?>" alt="Imagem do Evento">
                    <!-- Nome do evento em destaque -->
                    <figcaption><p><?php 
                    echo $infosEvento[0]; ?></p></figcaption>
                </figure>
                <!-- O botão tem que encaminhar para a página do evento em destaque -->
                <button class="btn-saiba-mais"><a href="paginaEventoVisitante.php?id=<?php echo $infosEvento[2] ?>" class="link-evento">Saiba Mais</a></button>
            </div>
        </section>
        <section class="secao-eventos-categoria-impulsionada">
            <article class="header-categoria-impulsionada">
                <h2>Outros eventos da categoria: </h2>
                <h2>#<?php 
                        echo $infosCat[1];     
                     ?>
                </h2>
            </article>
            <article class="eventos-categoria-impulsionada">
                <div class="container-eventos">
                    <?php eventos($infosCat[0], $infosEvento[2]); ?>
                </div>  
            </article>
        </section>
        <section class="secao-outros-eventos">
            <article class="article-categorias" id="article-categorias">
                <div class="categorias">
                    <h2>Categorias</h2>
                    <div class="container-categorias">
                        
                        <?php include "categoriasEventos.php";
                        ?>
                        <ul class="listaCategorias">
                            <?php 
                                echo categoria($infosCat[0]);
                            ?>
                            <div class="categorias-telas-menores">
                                <?php
                                    echo categoriaResponsivo($infosCat[0]);
                                ?>
                            </div>
                        </ul>
                        <?php 
                            
                        ?>
                        <button  onclick="mostrarCategorias(this)" class="btn-img-dropdown"><img src="../img/paginaInicial/flecha-dropdown.png" alt="Ícone de uma flecha que serve como dropdown" id="img-dropdown"></button>
                    </div>
                </div>
                <div class="data">
                    <h2>Data</h2>
                    <div class="container-data">
                        
                        <div class="dataPreencher">
                            <a href="#article-categorias"><input type="text"  id="datepicker" name="inputDate" readonly></input></a>
                        </div>
                    </div>
                </div>
            </article>
            <article class="article-eventos">
                <div class="result-pesquisa"></div>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-xl-2 row-cols-xxl-3 g-4 "></div>
                <div class="pagination"></div>
            </article>
        </section>
    </main>
    <?php include "../footer/footer.html" ?>
</body>
<script src="../jQuery/jquery-3.7.1.min.js"></script>
<script src="../jQuery/jquery-ui-1.13.2.custom/jquery-ui.min.js"></script>
<script src="../jQuery/datepicker-pt-BR.js"></script>
<script src="../avaliacao/js/avaliacao.js"></script>
<script src="js/mostrarCategorias.js"></script>
<script src="ajax/ajax.js"></script>
</html>