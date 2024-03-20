<?php
       session_start();
       if(isset($_SESSION['nome'])){
        header("Location: ../LagoEventos/lagoEventos.php");
       }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="atv1.1.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="shortcut icon" href="../img/cadastro&login/logo-reduzido.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <main>
        <a href="../LagoEventos/lagoEventos.php">
            <span class="material-symbols-outlined">
            arrow_back_ios
            </span>
        </a>
        <form action="" method="post" id="formUsuario">
            <h1>Login</h1>
            <div class="container-email">
                <input type="text" id="loginLog" name="loginLog" placeholder="Informe seu e-mail:">
                <div id="errNm1" class="erro">
            <?php
                if(isset($_POST["btn-logar"])) {
                    include('../conexao.php');
                    $email=$_POST['loginLog'];
        
                    $sqlEmail = "SELECT * FROM usuario WHERE emailUser LIKE '".$email."'";
                    $resultadoEmail = mysqli_query($conexao, $sqlEmail);
        
                    if(mysqli_num_rows($resultadoEmail) > 0) {
                        $emailExistente = true;
                    }else {
                        $emailExistente = false;
                        echo '<div class="error">E-mail Inválido</div>';
                    }
                }
            ?>
                </div>
            </div>
            <div class="container-senha">
                <input type="password" name="senhaLog" id="senhaLog" placeholder="Informe sua senha:">
                <div id="errNm2" class="erro">
            <?php
                if(isset($_POST["btn-logar"])) {
                    if($emailExistente) {
                        $senha=$_POST['senhaLog'];
                        $sql = "SELECT * FROM usuario WHERE emailUser = '$email'";
                        $result = mysqli_query($conexao, $sql);
                        if(mysqli_num_rows($result) > 0) {
                            while ($linha = mysqli_fetch_assoc($result)) {
                                $idBanco = $linha["idUser"];
                                $nomeBanco = $linha["nomeUser"];
                                $emailBanco = $linha["emailUser"];
                                $senhaBanco = $linha["senhaUser"];
                            }
                            if ($emailBanco == $email && password_verify($senha, $senhaBanco)) {
                                session_start();
                                $_SESSION['id']= $idBanco;
                                $_SESSION['nome']= $nomeBanco;
                                header("Location: ../LagoEventos/lagoEventos.php");
                            } else {
                                echo'<div class="error">Senha Incorreta</div>';
                            }
                        }
                    }
                }
            ?>
                </div>
            </div>
            <div class="botao-links">
                <button type="submit" name="btn-logar">Login</button>
                <div class="links">
                    <a href="../cadastro/cadastro.php" class="link">Quer se cadastrar?</a>
                    <span>|</span>
                    <a href="redefinir-senha/passo01.php" class="link">Esqueceu a Senha?</a>
                </div>
            </div>
        </form>
    </main>
</body>
</html>