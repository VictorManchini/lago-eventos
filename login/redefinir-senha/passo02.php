<?php
session_start();
if(isset($_GET["email"])){
    $email = $_GET["email"];
    if($email == ""){
        header('Location: passo01.php');
        exit;
    } else {
        include("../../conexao.php");
        $sql = "SELECT * FROM usuario WHERE emailUser LIKE '".$email."'";
    
        $resultado = mysqli_query($conexao, $sql);
        if(mysqli_num_rows($resultado) > 0) {
            $emailValido = true;
        } else {
            $emailValido = false;
            header('Location: passo01.php');
            exit;
        }
    }
} else {
    header('Location: passo01.php');
    exit;
}
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
            <div class="container-senha">
                <input type="password" name="senha" id="senha" placeholder="Informe sua nova senha:" data-error="#errNm1">
                <div id="errNm1" class="erro"></div>
            </div>
            <div class="container-confirmacaoSenha">
                <input type="password" name="confirmacaoSenha" id="confirmacaoSenha" placeholder="Informe sua senha novamente:" data-error="#errNm2">
                <div id="errNm2" class="erro"></div>
            </div>
            <button type="submit" name="btn-alterar" id="btn-alterar">Alterar</button>
        </form>
    <!-- jQuery -->
    <script src="../../jQuery/jquery-3.7.1.min.js"></script>
    <!-- jQuery Validate -->
    <script src="../../jQuery/jquery.validate.js"></script>
        <script src="passo02.js"></script>
    </main>
</body>
</html>
<?php
if(isset($_POST["btn-alterar"])){
    if($emailValido) {
        $senha = $_POST["senha"];
        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
        
        $sqlAtualizar = "UPDATE usuario SET senhaUser='".$senhaCriptografada."' WHERE emailUser LIKE '".$email."'";
        if(mysqli_query($conexao, $sqlAtualizar)) {
            if(isset($_SESSION['nome'])){
                session_destroy();
            }
            header('Location: passo03.php?email='.$email.'');
            exit;
        }else {
            echo "<p>Erro</p>";
        }
    }
}
?>