<?php
    session_start();
    if(isset($_GET['id']) && !empty($_GET['id'])) {
        $idEvento = $_GET['id'];

        include('../conexao.php');

        $sqlEvento = 'SELECT * FROM evento WHERE idEvento = '.$idEvento.'';
        $resultadoEvento = mysqli_query($conexao, $sqlEvento);
        if(mysqli_num_rows($resultadoEvento) > 0) {
            $eventoInfo = mysqli_fetch_assoc($resultadoEvento);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $eventoInfo['nomeEvento']; ?></title>
    <link rel="shortcut icon" href="../img/cadastro&login/logo-reduzido.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="css/paginaDoEvento.css">
    <link rel="stylesheet" href="../header/header.css">
    <link rel="stylesheet" href="../footer/footer.css">
    <link rel="stylesheet" href="../avaliacao/avaliacao.css">
</head>
<body class="body">
    <div class="fade"></div>
    <input type="hidden" id="idEvento" value="<?php echo $idEvento; ?>">
    <div id="fundo">
        <?php   
                include "../header/header.php";
                include ("../avaliacao/popUpAvaliacao.php");
                $urlLagoInicial = "lagoEventos.php";  
                if(isset($_SESSION['nome'])) {
                    $sqlRelacao = 'SELECT * FROM relacaoevento WHERE usuario = '.$idUser.' AND evento = '.$idEvento.' AND idRelacao = "'.$idUser.'_'.$idEvento.'"';
                    $resultadoRelacao = mysqli_query($conexao, $sqlRelacao);
                    if(mysqli_num_rows($resultadoRelacao) <= 0) {
                        header('location: paginaEventoVisitante.php?id='.$idEvento.'');
                    }
        ?>
    
        <main>
            <a href="lagoEventos.php"><button class="btn-voltar" href="lagoEventos.php"><img src="../img/paginaInicial/paginaDoEvento/btnVoltar.png" alt=""></button></a>
            <section class="secao-evento">
                <article class="data-evento">
                    <!-- Data do evento retirada do banco de dados -->
                    <p>
                        <?php
                            $dataArray = explode('-', $eventoInfo['dataEvento']);
                            $dataFormatada = ''.$dataArray[2].'/'.$dataArray[1].'/'.$dataArray[0].'';
                            echo $dataFormatada;
                        ?>
                    </p>
                    <?php
                        date_default_timezone_set('America/Sao_Paulo');
                        $data_atual = date("Y-m-d");
                        if ($eventoInfo['dataEvento'] >= $data_atual && $eventoInfo['terminadoManualmente'] == 0) {
                            echo "<p>Evento Ativo</p>";
                            $eventoInativo = 0;   
                        } else {
                            echo "<p>Evento Inativo</p>";
                            $eventoInativo = 1;
                        }
                    ?>
                    
                </article>    
                <article class="conteudo-evento">
                    <h1><?php echo $eventoInfo['nomeEvento']; ?></h1>
                    <figure class="img-evento">
                        <!-- img do evento no banco de dados -->
                        <img src="<?php echo $eventoInfo['imagemEvento']; ?>" alt="">
                    </figure>

                    <article class="infos-evento">
                        <div class="descricao-evento">
                            <!-- Descrição relacionada com o que tem presente no banco de dados -->
                            <p><?php echo $eventoInfo['descricaoEvento']; ?></p>
                        </div>
                        <div class="infos-adicionais-evento">
                            <div class="lista-participantes">
                                <p>PARTICIPANTES</p>
                                <!-- A lista de participantes tem que ser gerada a partir do banco de dados -->
                                <div class="container-participantes">
                                    <?php  
                                        $sqlDono = 'SELECT * FROM usuario WHERE idUser = '.$eventoInfo["criadorEvento"].'';
                                        $resultadoDono = mysqli_query($conexao, $sqlDono);
                                        if(mysqli_num_rows($resultadoDono) > 0) {
                                            $donoInfo = mysqli_fetch_assoc($resultadoDono);
                                            echo '<figure class="figure-perfil-participante">
                                                    <div class="container-img">
                                                    <span class="legendaDonoAdm">👑Dono</span>
                                                        <img src="'.$donoInfo['fotoUser'].'" alt="Imagem do Dono" class="img-perfil" data-type="dono" data-id="'.$donoInfo['idUser'].'">
                                                    </div>
                                                    <figcaption data-type="dono" data-id="'.$donoInfo['idUser'].'">'.$donoInfo['nomeUser'].'</figcaption>
                                                </figure>';
                                        }else {
                                            echo '<figure class="figure-perfil-participante">
                                                    <div class="container-img">
                                                        <img src="../img/perfil/vazio/blank-profile.jpg" alt="Imagem do Dono" class="img-perfil" data-type="dono" data-id="'.$donoInfo['idUser'].'>
                                                    </div>
                                                    <figcaption data-type="dono" data-id="'.$donoInfo['idUser'].'">Usuário não Encontrado</figcaption>
                                                </figure>';
                                        }

                                        $sqlAdministradoresID = 'SELECT usuario FROM relacaoevento WHERE evento = '.$idEvento.' AND usuario != '.$eventoInfo["criadorEvento"].' AND tipoUserRelation = 1';
                                        $resultadoAdministradoresID = mysqli_query($conexao, $sqlAdministradoresID);
                                        if(mysqli_num_rows($resultadoAdministradoresID) > 0) {
                                             $administradoresID = array();
                                            while($linhaAdministradoresID = mysqli_fetch_assoc($resultadoAdministradoresID)) {
                                                $administradoresID[] = $linhaAdministradoresID;
                                            }

                                            foreach($administradoresID as $administradorID) {
                                                $sqlAdministrador = 'SELECT * FROM usuario WHERE idUser = '.$administradorID["usuario"].'';
                                                $resultadoAdministrador = mysqli_query($conexao, $sqlAdministrador);
                                                $administrador = mysqli_fetch_assoc($resultadoAdministrador);
                                                echo '<figure class="figure-perfil-participante">
                                                        <div class="container-img">
                                                            <span class="legendaDonoAdm">🛡️Adm</span>
                                                            <img src="'.$administrador['fotoUser'].'" alt="Imagem de Administrador" class="img-perfil" data-type="adm" data-id="'.$administrador['idUser'].'">
                                                        </div>
                                                        <figcaption data-type="adm" data-id="'.$administrador['idUser'].'">'.$administrador['nomeUser'].'</figcaption>
                                                    </figure>';

                                            }
                                        }

                                        $sqlParticipantesID = 'SELECT usuario FROM relacaoevento WHERE evento = '.$idEvento.' AND usuario != '.$eventoInfo["criadorEvento"].' AND tipoUserRelation = 0';
                                        $resultadoParticipantesID = mysqli_query($conexao, $sqlParticipantesID);
                                        if(mysqli_num_rows($resultadoParticipantesID) > 0) {
                                                $participantesID = array();
                                           while($linhaParticipantesID = mysqli_fetch_assoc($resultadoParticipantesID)) {
                                                $participantesID[] = $linhaParticipantesID;
                                           } 

                                           foreach($participantesID as $participanteID) {
                                                $sqlParticipante = 'SELECT * FROM usuario WHERE idUser = '.$participanteID["usuario"].'';
                                                $resultadoParticipante = mysqli_query($conexao, $sqlParticipante);
                                                $participante = mysqli_fetch_assoc($resultadoParticipante);
                                                echo '<figure class="figure-perfil-participante">
                                                        <div class="container-img">
                                                            <img src="'.$participante['fotoUser'].'" alt="Imagem de Participante" class="img-perfil" data-type="user" data-id="'.$participante['idUser'].'">
                                                        </div>
                                                        <figcaption data-type="user" data-id="'.$participante['idUser'].'">'.$participante['nomeUser'].'</figcaption>
                                                    </figure>';
                                           }
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="localização-evento">
                                <!-- Os campos tem que ser preenchidos com base na info do evento dentro do banco de dados -->
                                <div class="endereco">
                                    <div class="container-rua">
                                        <span class="span-rua"><?php echo ''.$eventoInfo["enderecoEvento"].', '.$eventoInfo["numeroLugarEvento"].' / Ap.'.$eventoInfo["complementoEvento"].''; ?></span>
                                        <span class="span-bairro"><?php echo $eventoInfo['bairroEvento']; ?></span> 
                                    </div>
                                </div>
                                <div class="container-figure-endereco">
                                    <figure class="figure-endereco">
                                        <div class="container-img-endereco">
                                            <img src="../img/paginaInicial/paginaDoEvento/logoMapa.png" alt="" class="img-endereco">
                                        </div>
                                        <figcaption><?php echo $eventoInfo["CEPEvento"]; ?></figcaption>
                                    </figure>
                                </div>    
                            </div>
                        </div>
                    </article>
                </article>
            </section>
            <section class="secao-comentarios">
                <h2>Qualquer dúvida pergunte nos comentários:</h2>
                <article class="enviar-comentarios">
                    <?php
                        $sqlUsuarioLogado = 'SELECT * FROM usuario WHERE idUser = '.$idUser.'';
                        $resultadoUsuarioLogado = mysqli_query($conexao, $sqlUsuarioLogado);
                        $usuarioLogadoInfo = mysqli_fetch_assoc($resultadoUsuarioLogado);
                    ?>
                    <!-- foto de perfil vem do banco de dados, relacionado com o usuário que está logado -->
                    <img src="<?php echo $usuarioLogadoInfo['fotoUser']; ?>" alt="foto de perfil" class="comentarios-perfil" id="foto-perfil-comentar">
                    <input type="text" class="input-comentario">
                    <button class="btn-enviar" id="btn-enviar">
                        <span class="material-symbols-outlined backarrow">
                            arrow_forward_ios
                        </span>
                    </button>
                </article>

                <!-- Gerar comentários a partir de requisição do banco de dados -->
                <article class="comentarios"> 
                </article>
            </section>
            <div class="container-excluir-evento">
                <?php
                    $eventoDono = ($idUser == $eventoInfo['criadorEvento']);
                    if($eventoDono) {
                ?>
                    <button class="excluirEvento">Excluir Evento</button>
                    <button class="terminarEvento">Terminar Evento</button>
                <?php
                    }else {
                ?>
                        <button class="sairEvento">Sair do Evento</button>
                <?php
                    }
                ?>
            </div>
        </main>

        <?php include "../footer/footer.html" ?>
    </div>
<?php           // Usuario Não Logado
                }else {
                    header('location: paginaEventoVisitante.php?id='.$idEvento.'');
                }
        // Evento Não Existente
        }else {
        header('location: lagoEventos.php');
        }
    // GET ID não existente
    }else{
        header('location: lagoEventos.php');
    }
?>
<script src="../JQuery/jquery-3.7.1.min.js"></script>
<script src="../avaliacao/js/avaliacao.js"></script>
<script>
    let idEvento = <?php echo $idEvento; ?>;
    let idUsuarioLogado = <?php echo $usuarioLogadoInfo['idUser']; ?>;
    let donoEvento = <?php echo json_encode($eventoDono); ?>;
    let eventoInativo = <?php echo $eventoInativo; ?>;
</script>
<script src="js/paginaDoEvento.js"></script> 
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