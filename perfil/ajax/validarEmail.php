<?php
    header('Content-Type: application/json');

    $emailValido = false;

        if(isset($_POST['emailInserido'])){
            $idUser = $_POST['idUser'];
            $emailInserido = $_POST['emailInserido'];
            include('../../conexao.php');

            $sqlEmail = 'SELECT * FROM usuario WHERE idUser != '.$idUser.' AND emailUser = "'.$emailInserido.'"';
            $resultado = mysqli_query($conexao, $sqlEmail);

            if(mysqli_num_rows($resultado) > 0){
                $emailValido = false;
            }else {
                $emailValido = true;
            }
        }
    echo json_encode(array('emailValido' => $emailValido));
?>