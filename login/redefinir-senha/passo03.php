<?php
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
    <link rel="stylesheet" href="../login.css">  
</head>
<body>
    <main>
        <form action="">
            <h1>Senha Alterada com Sucesso</h1>
        </form>
    </main>
</body>
</html>
<?php
    if($emailValido){
        header("Refresh:2; url=../login.php");
        exit;
    }
?>