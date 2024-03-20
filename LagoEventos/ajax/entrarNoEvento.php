<?php
    header ('Content-Type: application/json');

    if(isset($_POST['idEvento']) && isset($_POST['idUser'])) {

        include ('../../conexao.php');
        
        $idEvento = $_POST['idEvento'];
        $idUser = $_POST['idUser'];

        $sqlEntrar = 'INSERT INTO relacaoevento(idRelacao, usuario, evento, tipoUserRelation) values("'.$idUser.'_'.$idEvento.'", '.$idUser.', '.$idEvento.', 0)';
        if(mysqli_query($conexao, $sqlEntrar)){
            $sqlAvaliacao = 'INSERT INTO avaliacaopendente(idAvaliacao, usuario, evento, relacaoEvento) values("A-'.$idUser.'_'.$idEvento.'", '.$idUser.', '.$idEvento.', "'.$idUser.'_'.$idEvento.'")';
            if(mysqli_query($conexao, $sqlAvaliacao)){
                echo json_encode('sucesso');
            }else {
                echo json_encode('erro');
            }
        }else {
            echo json_encode('erro');
        }
    }
?>