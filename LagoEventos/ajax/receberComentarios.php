<?php 
    header('Content-Type: application/json');

    include ('../../conexao.php');

    if(isset($_POST['idEvento'])) {
        $idEvento = $_POST['idEvento'];

        $perfilComComentario = array();

        $sqlComentarios = 'SELECT * FROM comentarioevento WHERE evento = '.$idEvento.' ORDER BY ordem DESC';
        $resultadoComentarios = mysqli_query($conexao, $sqlComentarios);
        if(mysqli_num_rows($resultadoComentarios) > 0) {
            $comentarios = array();
            while($linhaComentarios = mysqli_fetch_assoc($resultadoComentarios)) {
                $comentarios[] = $linhaComentarios;
            }
            foreach($comentarios as $comentario) {
                $sqlUsuario = 'SELECT * FROM usuario WHERE idUser = '.$comentario["usuario"].'';
                $resultadoUsuario = mysqli_query($conexao, $sqlUsuario);
                $userInfo = mysqli_fetch_assoc($resultadoUsuario);

                $userInfo['comentario'] = $comentario['comentario'];
                $userInfo['idComentario'] = $comentario['idComentario'];
                array_push($perfilComComentario, $userInfo);
            }
        }
        echo json_encode($perfilComComentario);
    }
?>