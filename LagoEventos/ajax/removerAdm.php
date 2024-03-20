<?php
    header('Content-Type: application/json');

    if(isset($_POST['idUser']) && isset($_POST['idEvento'])) {

        include '../../conexao.php';
        $idUser = $_POST['idUser'];
        $idEvento = $_POST['idEvento'];        
        
        $sqlRelacao = 'UPDATE relacaoevento SET tipoUserRelation = 0 WHERE usuario = '.$idUser.' AND evento = '.$idEvento.' AND idRelacao = "'.$idUser.'_'.$idEvento.'"';
        if(mysqli_query($conexao, $sqlRelacao)) {
            echo json_encode('sucesso');
        }
    }
?>