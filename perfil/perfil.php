 <?php
    session_start();
    if(isset($_GET['id'])) {
        $profileUserId = $_GET['id'];
        
        include('../conexao.php');

        $sqlProfile = 'SELECT * FROM usuario WHERE idUser = '.$profileUserId.'';
        $resultProfile = mysqli_query($conexao, $sqlProfile);
        if(mysqli_num_rows($resultProfile) > 0) {
            $userInfo = mysqli_fetch_assoc($resultProfile);
        } else {
            header('location: ../LagoEventos/lagoeventos.php');
        }
        if(isset($_SESSION['nome'])) {
            $idUser = $_SESSION['id'];
            $profileOwner = ($idUser === $profileUserId);
        }else {
            $profileOwner = false;
        }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/cadastro&login/logo-reduzido.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" type="text/css" href="../jQuery/jquery-ui-1.13.2.custom/jquery-ui.css">
    <link rel="stylesheet" href="../header/header.css">
    <link rel="stylesheet" href="../footer/footer.css">
    <link rel="stylesheet" href="../avaliacao/avaliacao.css">
    <?php
    if($profileOwner) {
        echo '<link rel="stylesheet" href="css/perfilDono.css">';
    }else {
        echo '<link rel="stylesheet" href="css/perfilVisitante.css">';
    }
    ?><title><?php echo $userInfo['nomeUser']; ?></title>
</head>
<body>
    <?php
    include ('../header/header.php');
    include ("../avaliacao/popUpAvaliacao.php");
    $urlLagoInicial = "";
    $urlLagoInicial = "../lagoEventos/lagoEventos.php";  
    echo '<input type="hidden" id="userID" value="'.$profileUserId.'"></input>';
    ?>
    <main>
        <a href="../LagoEventos/lagoeventos.php" class="anchor-back-arrow">
            <span class="material-symbols-outlined back-arrow">
                arrow_back_ios_new
            </span>
        </a>
        <article class="div-organizacional">

        
        <div class="container-profile">
            <section class="profile" id="profile" name="profile">
                <div class="line01">
                    <!-- Perfil Dono -->
                    <?php
                        if($profileOwner) { 
                    ?>
                        <div class="container-rating-imgProfile">
                            <div class="container-img-profile">
                                <img src="<?php echo $userInfo['fotoUser']; ?>" alt="foto de <?php echo $userInfo['nomeUser']; ?>" class="img-profile">
                            </div>
                        </div>
                        
                        <div class="text-profile">
                            <h1 class="name-profile"><?php echo $userInfo['nomeUser']; ?></h1>
                            <div class="email-dob">
                                <p class="email-profile"><?php echo $userInfo['emailUser']; ?></p>
                                <p class="dob-profile"><?php
                                    $dob = explode('-', $userInfo["dataNascUser"]);
                                    echo '' . $dob[2] . '/' . $dob[1] . '/' . $dob[0] . '';
                                ?></p>
                            </div>
                            <p class="bio-profile"><?php echo $userInfo['bioUser']; if($userInfo['bioUser']== "") { echo "não há bio.."; } ?></p>
                        </div>
                </div>
                <div class="line02">
                    <button class="btn-toggle active" id="btn-criar-evento">Criar um evento</button>
                    <button class="btn-toggle" id="btn-editar-perfil">Editar perfil</button>
                </div>
                <div class="line03">
                    <!-- Form Criar Evento -->
                    <form method="post" class="form active" id="formEvent" name="formEvent" enctype="multipart/form-data">
                        <div class="container-form">
                            <p class="line03-title">#VAMOS FAZER A FESTA</p>
                            <p class="line03-info">Info:</p>
                            
                            <div class="event-line01">
                                <div class="element01">
                                    <div class="img-event">
                                        <div for="imageEvent" class="imgPreviewEvent" id="imgPreviewEvent"></div>
                                        <label for="imageEvent" class="input-personalizado"></label>
                                        <input type="file" id="imageEvent" name="imageEvent" style="display: none;" required></input>
                                    </div>
                                    <?php
                                        if(isset($_POST["btn-criar"])){
                                            $imageEvent = $_FILES["imageEvent"]["name"];
                                            if(empty($imageEvent)){
                                                $imageFolderEvent = '../img/imgEventos/fundoImagemAzul.png';
                                            }else{
                                                $imageFileTypeEvent = pathinfo($imageEvent,PATHINFO_EXTENSION);
                                                if($imageFileTypeEvent !="jpg" && $imageFileTypeEvent !="png" && $imageFileTypeEvent !="jpeg" && $imageFileTypeEvent !="webp"){
                                                    echo"<p>Insira uma Imagem (.jpg/.png/.webp)</p>";
                                                }else{
                                                    $folderEvent = "../img/imgEventos";
                                                    $imageFolderEvent = $folderEvent . "/" . $imageEvent;
                                                    move_uploaded_file($_FILES["imageEvent"]["tmp_name"],$imageFolderEvent);
                                                }
                                            }
                                        }
                                    ?>
                                </div>
                                <div class="event-element-right">
                                    <div class="element02">
                                        <div class="container-name">
                                            <input type="text" id="nameEvent" name="nameEvent" placeholder="Informe o título do evento:">
                                        </div>
                                        <div class="container-descriptionEvent">
                                            <textarea type="text" id="descriptionEvent" name="descriptionEvent" placeholder="Informe a descrição:" maxlength="250"></textarea>
                                            <span id="counterDescriptionEvent" name="counterDescriptionEvent">0/250</span>
                                            
                                        </div>
                                        <!-- <div class="container-date">
                                            <input type="date" id="dateEvent" name="dateEvent">
                                        </div> -->
                                        <div class="container-date">
                                            <input type="text" class="dateEvent"  id="datepicker" name="dateEvent" readonly></input>
                                        </div>
                                    </div>
                                    <div class="elements-right-bottom">
                                        <!-- O select eu terei que tentar criar como uma DIV -->
                                        <div class="element03">
                                            <select id="categoryEvent" name="categoryEvent">
                                                <option value="0" disabled selected>Categoria</option>
                                                <?php
                                                    $sqlCat = "SELECT * FROM categoria";
                                                    $resultCategory = mysqli_query($conexao, $sqlCat);
                                                    if (mysqli_num_rows($resultCategory) > 0) {
                                                        $categories = array();
                                                        while($line = mysqli_fetch_assoc($resultCategory)) {
                                                            echo '<option value ="' . $line["idCategoria"] . '">#' . $line["nomeCategoria"] . '</option>';
                                                        }
                                                    } else {
                                                        echo '<option value ="0">Nenhuma Categoria</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="element04">
                                            <input type="number" id="participantEvent" name="participantEvent" value="1" min="1">

                                            
                                            <img src="../img/perfil/conteudoPrincipal/participantes.png" class="img-participant">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="event-line03">
                                <label class="event-line03-title">Local:</label>
                                <div class="element01">
                                    <div class="container-input" id="container-cepEvent">
                                        <input type="text" id="cepEvent" name="cepEvent" placeholder="CEP">
                                    </div>
                                    <div class="container-input" id="container-addressEvent">
                                        <input type="text" id="addressEvent" name="addressEvent" placeholder="Endereço">
                                    </div>
                                </div>
                                <div class="element02">     
                                    <div class="container-input" id="container-neighboorhoodEvent">                           
                                        <input type="text" id="neighboorhoodEvent" name="neighboorhoodEvent" placeholder="Bairro">
                                    </div>
                                    <div class="container-input" id="container-placeNumberEvent">
                                        <input type="number" id="placeNumberEvent" name="placeNumberEvent" placeholder="Número">
                                    </div>
                                    <div class="container-input" id="container-complementEvent">    
                                        <input type="text" id="complementEvent" name="complementEvent" placeholder="Complemento">
                                    </div>
                                </div>
                            </div>
                            
                            <button id="btn-criar" name="btn-criar">Criar</button>
                            <?php
                                if(isset($_POST['btn-criar'])) {
                                    $nameEvent = $_POST["nameEvent"];
                                    $descriptionEvent = $_POST["descriptionEvent"];
                                    $dateEvent = $_POST["dateEvent"];
                                    $categoryEvent = $_POST["categoryEvent"];
                                    $participantsEvent = $_POST["participantEvent"];
                                    $CEPEvent = $_POST["cepEvent"];
                                    $neighboorhoodEvent = $_POST["neighboorhoodEvent"];
                                    $addressEvent = $_POST["addressEvent"];
                                    $placeNumberEvent = $_POST["placeNumberEvent"];
                                    $complementEvent = $_POST["complementEvent"];
                                    
                                    $dob = explode('/', $dateEvent);
                                    $dateEvent = '' . $dob[2] . '-' . $dob[1] . '-' . $dob[0] . '';

                                    $SqlEvent = 'INSERT INTO evento(nomeEvento, criadorEvento, descricaoEvento, qtdeParticipantes, idCategoria, CEPEvento, bairroEvento, enderecoEvento, numeroLugarEvento, complementoEvento, dataEvento, imagemEvento) values("'.$nameEvent.'", '.$idUser.', "'.$descriptionEvent.'", "'.$participantsEvent.'", "'.$categoryEvent.'", "'.$CEPEvent.'", "'.$neighboorhoodEvent.'", "'.$addressEvent.'", "'.$placeNumberEvent.'", "'.$complementEvent.'", "'.$dateEvent.'", "'.$imageFolderEvent.'")';
                                    
                                    
                                    if(mysqli_query($conexao, $SqlEvent)) {
                                        $id_evento = $conexao->insert_id;
                                        
                                        $sqlRelation = 'INSERT INTO relacaoEvento(idRelacao, usuario, evento, tipoUserRelation) values("'.$idUser.'_'.$id_evento.'",'.$idUser.','.$id_evento.', 1)';
                                        if(mysqli_query($conexao, $sqlRelation)){
                                            echo "ok";
                                        }
                                    } else {
                                        echo "falha";
                                    }
                                }
                            ?>
                        </div>
                    </form>
                    <!-- Form Alterar Usuario -->
                    <form method="post" class="form" id="formUser" name="formUser" enctype="multipart/form-data" >
                        <div class="container-form">
                            <p class="line03-title02">#VAMOS EDITAR</p>
                            <p class="line03-update">Atualize:</p>
                            <div class="container-user-edit">
                                <div class="userLine01">
                                    <div class="element01">
                                        <div class="img-user">
                                            <div for="imageUser" class="imgPreviewUser" id="imgPreviewUser" style="background-image: url('<?php echo $userInfo['fotoUser'] ?>')"></div>
                                            <label for="imageUser" class="input-personalizado"></label>
                                            <input type="file" id="imageUser" name="imageUser" style="display: none;"></input>
                                        </div>
                                        <?php
                                            if(isset($_POST["btn-atualizar"])){
                                                $imageUser = $_FILES["imageUser"]["name"];
                                                if(empty($imageUser)){
                                                    $imageFolderUser = $userInfo['fotoUser'];
                                                }else{
                                                    $imageFileTypeUser = pathinfo($imageUser,PATHINFO_EXTENSION);
                                                    if($imageFileTypeUser !="jpg" && $imageFileTypeUser !="png" && $imageFileTypeUser !="jpeg" && $imageFileTypeUser !="webp"){
                                                        echo"<p>Insira uma Imagem (.jpg/.png/.webp)</p>";
                                                    }else{
                                                        $folderUser = "../img/imgEventos";
                                                        $imageFolderUser = $folderUser . "/" . $imageUser;
                                                        move_uploaded_file($_FILES["imageUser"]["tmp_name"],$imageFolderUser);
                                                    }
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="container-edit-info">
                                    <div class="userLine02">
                                        <div class="element01">
                                            <div class="container-userName">
                                                <input type="text" id="userName" name="userName" placeholder="Nome" value="<?php echo $userInfo['nomeUser'] ?>">
                                            </div>
                                            <div class="container-userEmail">
                                                <input type="text" id="userEmail" name="userEmail" placeholder="E-mail" value="<?php echo $userInfo['emailUser'] ?>">
                                            </div>
                                            <div class="container-descriptionUser">
                                                <textarea type="text" id="descriptionUser" name="descriptionUser" placeholder="Bio:" maxlength="150" value="  "><?php echo $userInfo['bioUser']?></textarea>
                                                <span id="counterDescriptionUser" name="counterDescriptionUser">0/150</span>
                                            </div>
                                        </div>
                                        <div class="element02">
                                            <div class="container-userCPF">
                                                <input type="text" id="userCPF" name="userCPF" placeholder="CPF" value="<?php echo $userInfo['cpfUser'] ?>">
                                            </div>  
                                            <div class="container-data-perfil">
                                                <?php
                                                    $dataNasc = explode('-', $userInfo['dataNascUser']);
                                                    $dayFill = $dataNasc[2];
                                                    if($dataNasc[1] == 00) {
                                                        $monthFill = 0;
                                                    }else {
                                                        $monthFill = $dataNasc[1] - 1;
                                                    }
                                                    $yearFill = $dataNasc[0];
                                                    $months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                                                    for($i = 0; $i < count($months); $i++) {
                                                        if ($monthFill == $i) {
                                                            $monthName = $months[$i];
                                                        }
                                                    }
                                                ?>

                                                <select id="userDay" name="userDay">
                                                    <option value="<?php echo $dayFill ?>" disabled selected><?php echo $dayFill ?></option>
                                                </select>
                                                <select id="userMonth" name="userMonth">
                                                    <option value="<?php echo ($monthFill + 1) ?>" disabled selected><?php echo $monthName ?></option>
                                                </select>
                                                <select id="userYear" name="userYear">
                                                    <option value="<?php echo $yearFill ?>" disabled selected><?php echo $yearFill ?></option>
                                                </select>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="userLine03">
                                        <div class="container-userPassword">
                                            <input type="text" id="userPassword" name="userPassword" placeholder="Senha:">
                                        </div>
                                        <div class="container-userPasswordConfirmation">
                                            <input type="text" id="userPasswordConfirmation" name="userPasswordConfirmation" placeholder="Confirme a Senha:">
                                        </div>
                                    </div>
                                    
                                    
                                </div>  
                            </div>
                            <div class="container-forgot-password">
                                <a href="../login/redefinir-senha/passo01.php" id="userForgotPassword" name="userForgotPassword">Esqueceu a Senha?</a>
                            </div>
                            <button id="btn-atualizar" name="btn-atualizar">Atualizar</button>
                        </div>
                    </form>
                    <?php
                        if(isset($_POST["btn-atualizar"])){
                            $nameUser = $_POST['userName'];
                            $emailUser = $_POST['userEmail'];
                            $bioUser = $_POST['descriptionUser'];
                            $cpfUser = $_POST['userCPF'];

                            if(isset($_POST['userDay'])){
                                $dayUser = $_POST['userDay'];
                            }else {
                                $dayUser = 0;
                            }

                            if(isset($_POST['userMonth'])){
                                $monthUser = $_POST['userMonth'];
                            }else {
                                $monthUser = 0;
                            }

                            if(isset($_POST['userYear'])){
                                $yearUser = $_POST['userYear'];
                            }else{
                                $yearUser = 0;
                            }

                            $birthDate = ''.$yearUser.'-'.$monthUser.'-'.$dayUser.'';


                            if($birthDate == '0-0-0') {
                                $sqlUser = 'UPDATE usuario SET nomeUser="'.$nameUser.'", emailUser="'.$emailUser.'", fotoUser="'.$imageFolderUser.'", bioUser="'.$bioUser.'", cpfUser="'.$cpfUser.'" WHERE idUser = '.$idUser.'';
                            }else {
                                $sqlUser = 'UPDATE usuario SET nomeUser="'.$nameUser.'", emailUser="'.$emailUser.'", fotoUser="'.$imageFolderUser.'", bioUser="'.$bioUser.'", cpfUser="'.$cpfUser.'", dataNascUser="'.$birthDate.'" WHERE idUser = '.$idUser.'';
                            }


                            if(mysqli_query($conexao,$sqlUser)) {
                                echo "sucesso";
                            }else {
                                echo "erro";
                            }
                            
                        }
                    ?>
                </div>

                
                    <!-- Perfil Visitante -->
                    <?php
                        }else{
                    ?>  
                        <div class="container-rating-imgProfile">
                            <div class="container-img-profile">
                                <img src="<?php echo $userInfo['fotoUser'] ?>" alt="foto de <?php echo $userInfo['nomeUser']; ?>" class="img-profile">
                            </div>
                        </div>
                        <div class="text-profile">
                            <h1 class="name-profile"><?php echo $userInfo['nomeUser']; ?></h1>
                            <p class="bio-profile"><?php echo $userInfo['bioUser']; if($userInfo['bioUser'] == '') { echo "este perfil não possui bio.."; }?></p>
                        </div>
                </div>
                <div class="line02">
                        <h1 class="title-participated-events">#<?php echo $userInfo['nomeUser']; ?> foi</h1>
                        <div class="participated-events">
                            <?php
                                // Pegando as linhas da tabela relação evento para identificar os eventos pertencentes a esse usuário
                                $sqlRelacaoVisitante = 'SELECT * FROM relacaoevento WHERE usuario = '.$userInfo['idUser'].'';
                                
                                $resultadoRelacaoVisitante = mysqli_query($conexao, $sqlRelacaoVisitante);
                                $numLinhasRelacaoVisitante = mysqli_num_rows($resultadoRelacaoVisitante);
                                if ($numLinhasRelacaoVisitante > 0) {
                                    while ($linhaRelacaoVisitante = mysqli_fetch_assoc($resultadoRelacaoVisitante)) {
                                        $relacoesVisitante[] = $linhaRelacaoVisitante;
                                    }
                                    foreach($relacoesVisitante as $linhaRelacaoVisitante) {
                                        // Pegando os eventos relacionados a esse usuários
                                        $eventosVisitante = array();
                                        $sqlEventosVisitante = 'SELECT * FROM evento WHERE idEvento = '.$linhaRelacaoVisitante['evento'].' AND criadorEvento !='.$userInfo['idUser'].'';
                                        
                                        $resultadoEventoVisitante = mysqli_query($conexao, $sqlEventosVisitante);
                                        $numLinhasEventoVisitante = mysqli_num_rows($resultadoEventoVisitante);
                                        
                                        if ($numLinhasEventoVisitante > 0) {
                                            while ($linhaEventoVisitante = mysqli_fetch_assoc($resultadoEventoVisitante)) {
                                             $eventosVisitante[] = $linhaEventoVisitante;
                                            }
                                            foreach($eventosVisitante as $eventoVisitante) {
                                                // Pegando a categoria relacionada ao evento inserido

                                                $sqlCategoriaVisitante = 'SELECT * FROM categoria WHERE idCategoria = '.$eventoVisitante['idCategoria'].'';
                                                $resultadoCategoriaVisitante = mysqli_query($conexao, $sqlCategoriaVisitante);

                                                if($resultadoCategoriaVisitante) {
                                                    $categoriaVisitante = mysqli_fetch_assoc($resultadoCategoriaVisitante);
                                                }
                                                ?>    
                                                    <div class="event">
                                                        <div class="container-img-event">
                                                            <img src="<?php echo $eventoVisitante['imagemEvento'] ?>" class="event-img">
                                                            <div class="info-event">
                                                                <div class="container-event-title">
                                                                    <h2 class="event-title"><?php echo $eventoVisitante['nomeEvento'] ?></h2>
                                                                </div>
                                                                <p class="event-category"><?php echo $categoriaVisitante['nomeCategoria'] ?></p>
                                                            </div>
                                                        </div>
                                                        <a href="../LagoEventos/paginaEventoVisitante.php?id=<?php echo $eventoVisitante['idEvento'] ?>" class="btn-saiba-mais">Saiba Mais</a>
                                                    </div>
                                                <?php
                                            }
                                        }
                                        
                                    }
                                } else {
                                    echo "<p class='msgErroEventos'>Você não participou de nenhum evento até agora!</p>";
                                }
                            ?>
                    <?php
                        }
                    ?>
                </div>
            </section>
        </div>
        <!-- Parte Direita do Perfil Dono -->
            <?php
            if($profileOwner) {
            ?>
            <aside>
                <div class="container-buttons">
                    <div class="container-buttons-top">
                        <button id="participatedEventsButton" class="asideButtons active-btn">Eventos Participados</button>
                        <button id="myEventsButton" class="asideButtons">Meus Eventos</button>
                    </div>
                    <div class="div active-events events-list container-events" id="participatedEvents">
                        <!-- Provisório -->
                        <p class="participatedEvents-title">#EU ESTAVA</p>
                        <div class="events">
                            <?php
                                // Pegando as linhas da tabela relação evento para identificar os eventos pertencentes a esse usuário
                                $sqlRelacaoParticipado = 'SELECT * FROM relacaoevento WHERE usuario = '.$userInfo['idUser'].'';
                                
                                $resultadoRelacaoParticipado = mysqli_query($conexao, $sqlRelacaoParticipado);
                                $numLinhasRelacaoParticipado = mysqli_num_rows($resultadoRelacaoParticipado);
                                if ($numLinhasRelacaoParticipado > 0) {
                                    
                                    while ($linhaRelacaoParticipado = mysqli_fetch_assoc($resultadoRelacaoParticipado)) {
                                        $relacoesParticipado[] = $linhaRelacaoParticipado;
                                    }
                                    
                                    foreach($relacoesParticipado as $relacaoParticipado) {
                                        // Pegando os eventos relacionados a esse usuários
                                        $eventosParticipado = array();
                                        $sqlEventosParticipado = 'SELECT * FROM evento WHERE idEvento = '.$relacaoParticipado['evento'].' AND criadorEvento != '.$userInfo['idUser'].'';
                                        
                                        $resultadoEventoParticipado = mysqli_query($conexao, $sqlEventosParticipado);
                                        $numLinhasEventoParticipado = mysqli_num_rows($resultadoEventoParticipado);
                                        
                                        if ($numLinhasEventoParticipado > 0) {
                                            
                                            while ($linhaEventoParticipado = mysqli_fetch_assoc($resultadoEventoParticipado)) {
                                                $eventosParticipado[] = $linhaEventoParticipado;
                                            }  
                                            
                                            
                                            foreach($eventosParticipado as $eventoParticipado) {
                                                // Pegando a categoria relacionada ao evento inserido
                                                
                                                $sqlCategoriaParticipado = 'SELECT * FROM categoria WHERE idCategoria = '.$eventoParticipado['idCategoria'].'';
                                                $resultadoCategoriaParticipado = mysqli_query($conexao, $sqlCategoriaParticipado);

                                                if($resultadoCategoriaParticipado) {
                                                    $categoriaParticipado = mysqli_fetch_assoc($resultadoCategoriaParticipado);
                                                }
                                                
                                                ?>    
                                                    <div class="event">
                                                        <div class="container-img-event">
                                                            <img src="<?php echo $eventoParticipado['imagemEvento'] ?>" class="event-img">
                                                            <div class="info-event">
                                                                <div class="container-event-title">';
                                                                    <h2 class="event-title"><?php echo $eventoParticipado['nomeEvento'] ?></h2>
                                                                </div>
                                                                <p class="event-category"><?php echo $categoriaParticipado['nomeCategoria'] ?></p>
                                                            </div>
                                                        </div>
                                                        <a href="../LagoEventos/paginaEventoVisitante.php?id=<?php echo $eventoParticipado['idEvento'] ?>" class="btn-saiba-mais">Saiba Mais</a>
                                                    </div>
                                                <?php
                                            }
                                            
                                        }
                                        
                                    }
                                } else {
                                    echo "<p class='msgErroEventos'>Você não participou de nenhum evento até agora!</p>";
                                }
                            ?>
                        </div>
                        <!-- Provisório -->
                    </div>
                    <div id="myEvents" class="div myEvents events-list container-events">
                        <p class="participatedEvents-title">#EU CRIEI</p>
                        <div class="events">
                        <?php
                        $sqlMyEvents = 'SELECT * FROM evento WHERE criadorEvento = '.$profileUserId.'';
                        $resultMyEvents = mysqli_query($conexao, $sqlMyEvents);
                            if(mysqli_num_rows($resultMyEvents) > 0) {
                                while($lineMyEvents = mysqli_fetch_assoc($resultMyEvents)) {
                                    echo '<div class="event">';
                                        echo '<div class="container-img-event">';
                                            echo '<div class="container-img">';
                                                echo '<img src="'.$lineMyEvents["imagemEvento"].'" class="event-img">';
                                            echo '</div>';
                                            echo '<div class="info-event">';
                                                echo '<div class="container-event-title">';
                                                    echo '<h2 class="event-title">'.$lineMyEvents["nomeEvento"].'</h2>';
                                                echo '</div>';
                                                $sqlMyEventsCat = 'SELECT * FROM categoria WHERE idCategoria = '.$lineMyEvents["idCategoria"].'';
                                                $resultMyEventsCat = mysqli_query($conexao, $sqlMyEventsCat);
                                                $MyEventsCat = mysqli_fetch_assoc($resultMyEventsCat);
                                                echo '<p class="event-category">#'.$MyEventsCat["nomeCategoria"].'</p>';
                                            echo '</div>';
                                        echo '</div>';
                                        echo "<a href='../LagoEventos/paginaEventoVisitante.php?id=".$lineMyEvents['idEvento']."' class='btn-saiba-mais'>Saiba Mais</a>";
                                    echo '</div>';

                                }
                            }else {
                                echo '<p id="myEventsError" class="myEventsError msgErroEventos">Nenhum Evento Encontrado</p>';
                            }
                    ?></div>
                    </div>
                </div>
            </aside>
            <?php
            }
            } else {
                header('location: ../LagoEventos/lagoeventos.php');
            }
            ?>
        </article>
    </main>
    <?php include "../footer/footer.html" ?>
<script src="../jQuery/jquery-3.7.1.min.js"></script>
<script src="../avaliacao/js/avaliacao.js"></script>
<?php
if($profileOwner) {
    echo '<script src="../jQuery/jquery.validate.js"></script>';
    echo '<script src="../jQuery/jquery.mask.min.js"></script>';
    echo '<script src="../jQuery/jquery-ui-1.13.2.custom/jquery-ui.min.js"></script>';
    echo '<script src="../jQuery/datepicker-pt-BR.js"></script>';
    echo '<script src="js/perfil.js"></script>';
    echo '<script src="ajax/ajaxDono.js"></script>';
}else {
    echo '<script src="ajax/ajaxVisitante.js"></script>';
}
?>

<script>
    var btnSearch = document.getElementById("btn-pesquisa");
    var inputPesquisa = document.getElementById("campo-busca");
    btnSearch.addEventListener("click", function() {
        window.location.href = "../lagoEventos/lagoEventos.php?search=true&resultadoPesquisa=" + encodeURIComponent(inputPesquisa.value);
    })
    inputPesquisa.addEventListener("keydown", function(event) {
        if (event.keyCode == 13) {
            window.location.href = "../lagoEventos/lagoEventos.php?search=true&resultadoPesquisa=" + encodeURIComponent(inputPesquisa.value);
        }
    })
</script>
</body>
</html>



<?php
    // if (isset($_POST['btn-pesquisa'])) {
    //     $search = true; 
    //     header("Location: ../lagoEventos/lagoEventos.php?search=".$search);
    // }
?>