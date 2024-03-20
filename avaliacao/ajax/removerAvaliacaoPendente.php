<?php
    header('Content-Type: application/json');

    if(isset($_POST['AvaliacaoPendenteEventoID']) && isset($_POST['AvaliacaoPendenteUser'])) {

        include '../../conexao.php';
        $AvaliacaoPendenteEventoID = $_POST['AvaliacaoPendenteEventoID'];
        $AvaliacaoPendenteUser = $_POST['AvaliacaoPendenteUser'];

        $sqlRemoverAvaliacao = 'DELETE FROM avaliacaopendente WHERE idAvaliacao = "A-'.$AvaliacaoPendenteUser.'_'.$AvaliacaoPendenteEventoID.'"';
        if(mysqli_query($conexao, $sqlRemoverAvaliacao)) {
            echo json_encode('sucesso');
        }else {
            echo json_encode('erro');
        }
    }else {
        echo json_encode('erro');
    }
?>