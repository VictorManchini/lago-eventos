<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="shortcut icon" href="../../img/cadastro&login/logo-reduzido.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../login.css">
</head>
<body>
    <main>
        <a href="../login.php">
            <span class="material-symbols-outlined">
            arrow_back_ios
            </span>
        </a>
        <form action="" method="post" id="formUsuario">
            <h1>Redefinir Senha</h1>
            <div class="container-email">
                <input type="text" name="emailInformado" id="emailInformado" placeholder="Informe seu e-mail:">
                <div id="errNm1" class="erro">
                    <?php
                        if(isset($_POST["btn-avancar"])){
                            include("../../conexao.php");
                            $email = $_POST["emailInformado"];
                            $sql = "SELECT * FROM usuario WHERE emailUser LIKE '".$email."'";
                            $resultado = mysqli_query($conexao, $sql);
                            if($email == ""){
                                echo '<div class="error">E-mail Inválido</div>';
                            } else {
                                if(mysqli_num_rows($resultado) > 0) {
                                    header('Location: passo02.php?email='.$email.'');
                                    exit;
                                } else {
                                    echo '<div class="error">E-mail Inválido</div>';
                                }
                            }
                        }
                    ?>
                </div>
            </div>
            <button type="submit" name="btn-avancar" id="btn-avancar">Avançar</button>
        </form>
    </main>
</body>
</html>