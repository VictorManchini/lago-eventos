<header>
        <?php
            $url = $_SERVER['REQUEST_URI'];
            $nomeLagoInicial = basename($url);
            $nomeLagoInicial = basename(parse_url($url, PHP_URL_PATH));

            if ($nomeLagoInicial == "perfil.php") {
                
                $urlLagoInicial = "../lagoEventos/lagoEventos.php";
            } else {
                $urlLagoInicial = "lagoEventos.php";
            }
        ?>



        <a href="<?php echo $urlLagoInicial ?>"><img src="../img/paginaInicial/logoEventos.png" alt="Logo do Lago Eventos" class="img-logo-site"></a>
        <div class="container-busca">
            <input type="text" name="campo-busca" id="campo-busca" placeholder="Busque um evento!">
            <button type="button" class="btn-pesquisa" id="btn-pesquisa" name="btn-pesquisa">
                <img src="../img/paginaInicial/lupa.png" alt="Botão de busca">
            </button>
        </div>
        <div class="container-perfil">
            <a href="<?php echo $urlLagoInicial ?>"><img src="../img/paginaInicial/logoEventos.png" alt="Logo do Lago Eventos" class="img-logo-site-responsivo"></a>
            <figure class="figure-perfil">
                <?php
                if(isset($_SESSION['nome'])) {
                    $nameUser = $_SESSION['nome'];
                    $idUser = $_SESSION['id'];

                    include ('../conexao.php');

                    $sqlSession = 'SELECT * FROM usuario WHERE idUser = '.$idUser.'';
                    $resultSession = mysqli_query($conexao, $sqlSession);
                    if(mysqli_num_rows($resultSession) > 0) {
                        $Sessioninfo = mysqli_fetch_assoc($resultSession);
                    } 
                    echo '<div class="container-profile-img"><a href="../perfil/perfil.php?id='.$idUser.'" class="anchor-profile-img"><img src="'.$Sessioninfo['fotoUser'].'" alt="Foto de perfil do usuário"></a></div>';
                ?>
                <figcaption>
                <?php
                        echo'<p>Olá, <a href="../perfil/perfil.php?id='.$idUser.'">'.$nameUser.'</a> | <a href="../login/logout.php">Log out</a></p>';
                    }else {
                        echo '<p><a href="../login/login.php">Log in</a> | <a href="../cadastro/cadastro.php">Sign in</a></p>';
                    }
                ?>
                </figcaption>
            </figure>
        </div>
    </header>