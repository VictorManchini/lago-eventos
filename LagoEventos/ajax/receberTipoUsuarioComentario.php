<?php
    header('Content-Type: application/json');

    
    if(isset($_POST['idUsuario']) && isset($_POST['idEvento']) && isset($_POST['idComentario'])) {

        include '../../conexao.php';
        $idUsuario = $_POST['idUsuario'];
        $idEvento = $_POST['idEvento'];
        $idComentario = $_POST['idComentario'];


        $sqlComentario = 'SELECT * FROM comentarioevento where usuario = '.$idUsuario.' AND idComentario = "'.$idComentario.'"';
        $resultadoComentario = mysqli_query($conexao, $sqlComentario);
        if(mysqli_num_rows($resultadoComentario) > 0) {
            echo json_encode('donoComentario');
        }else {    
            $sqlDonoEvento = 'SELECT * FROM relacaoevento WHERE usuario = '.$idUsuario.' AND evento = '.$idEvento.' AND tipoUserRelation = 1 AND idRelacao = "'.$idUsuario.'_'.$idEvento.'"';
            $resultadoDonoEvento = mysqli_query($conexao, $sqlDonoEvento);
            if(mysqli_num_rows($resultadoDonoEvento) > 0) {
                echo json_encode('donoEvento');
            } else {
                echo json_encode('user');
            }
        }
    }
?>