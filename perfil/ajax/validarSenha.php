<?php
    header('Content-Type: application/json');

    $senhaValida = false;

        if(isset($_POST['senhaInserida'])){
            $idUser = $_POST['idUser'];
            $senhaInserida = $_POST['senhaInserida'];
            include('../../conexao.php');

            $sqlSenha = 'SELECT * FROM usuario WHERE idUser = '.$idUser.'';
            $resultado = mysqli_query($conexao, $sqlSenha);

            if(mysqli_num_rows($resultado) > 0){
                $linha = mysqli_fetch_assoc($resultado);
                if(password_verify($senhaInserida, $linha['senhaUser'])){
                    $senhaValida = true;
                }
            }
        }
    echo json_encode(array('senhaValida' => $senhaValida));
?>