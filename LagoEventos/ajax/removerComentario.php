<?php
    header('Content-Type: application/json');

    if(isset($_POST['idComentarioRemover'])) {

        include '../../conexao.php';
        $idComentario = $_POST['idComentarioRemover'];      
    
        
        $sqlRemoverComentario = 'DELETE FROM comentarioevento WHERE idComentario = "'.$idComentario.'"';
        if(mysqli_query($conexao, $sqlRemoverComentario)) {
            echo json_encode('sucesso');
        } else {
            echo json_encode('erro');
        }

    }
?>