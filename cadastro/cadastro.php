<?php
       session_start();
       if(isset($_SESSION['nome'])){
        header("Location: ../LagoEventos/lagoEventos.php");
       }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="shortcut icon" href="../img/cadastro&login/logo-reduzido.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <main>
        <a href="../LagoEventos/lagoEventos.php">
            <span class="material-symbols-outlined">
            arrow_back_ios
            </span>
        </a>
        <form action="" method="post" id="formUsuario">
            <h1>Cadastro</h1>
            <div class="container-nome">
                <input type="text" name="nome" id="nome" class="nome" placeholder="Informe seu nome:" data-error="#errNm1">
                <div id="errNm1" class="erro"></div>
            </div>
            <div class="container-email">
                <input type="text" name="emailInformado" id="emailInformado" class="emailInformado" placeholder="Informe seu e-mail:" data-error="#errNm2">
                <div id="errNm2" class="erro">
                    <?php
                        if(isset($_POST["btn-cadastrar"])) {
                            include("../conexao.php");
                            $email = $_POST["emailInformado"];
                            $emailJaUtilizado = 0;
                    
                            $sqlChecar = "SELECT * FROM usuario WHERE emailUser LIKE '".$email."'";
                    
                            $resultadoChecar = mysqli_query($conexao, $sqlChecar);
                    
                            if(mysqli_num_rows($resultadoChecar) > 0) {
                                echo '<div class="error">Email já esta em uso</div>';
                                $emailJaUtilizado = 1;
                            } else {
                                $emailJaUtilizado = 0;
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="inputs-senha">
                <div class="container-senha">
                    <input type="password" name="senha" id="senha" class="senha" placeholder="Digite a Senha:" data-error="#errNm3">
                    <div id="errNm3" class="erro"></div>
                </div>
                <div class="container-confirmacao-senha">
                    <input type="password" name="senhaConfirmacao" id="senhaConfirmacao" class="senhaConfirmacao" placeholder="Confirme a Senha:" data-error="#errNm4">
                    <div id="errNm4" class="erro"></div>
                </div>
            </div>
            <button type="submit" id="btn-cadastrar" name="btn-cadastrar">Cadastrar</button>
            <?php
                if(isset($_POST["btn-cadastrar"])) {

                    include("../conexao.php");

                    $nome = $_POST["nome"];
                    $senha = $_POST["senha"];
                    $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

                    if($emailJaUtilizado == 0) {

                        $sql = "INSERT INTO usuario (nomeUser, emailUser, senhaUser, fotoUser) VALUES ('$nome', '$email', '$senhaCriptografada', '../img/perfil/perfilDefault/perfil.jpg')";
                        
                        if(mysqli_query($conexao, $sql)) {
                            header('Location: confirmacao.php?email='.$email.'');
                            exit;
                        }else {
                            echo '<label class="error">Erro ao Cadastrar Usuário</label>';
                        }
                    }
                }
            ?>
        </form>
    </main>
    <!-- jQuery -->
    <script src="../jQuery/jquery-3.7.1.min.js"></script>
    <!-- jQuery Validate -->
    <script src="../jQuery/jquery.validate.js"></script>
    <script src="cadastro.js"></script>
</body>
</html>