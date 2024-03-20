<?php
    header('Content-Type: application/json');

    if(isset($_POST['AvaliacaoPendenteEventoID']) && isset($_POST['AvaliacaoPendenteUser']) && isset($_POST['AvaliacaoPendenteDono']) && isset($_POST['rating'])) {

        include '../../conexao.php';
        $AvaliacaoPendenteEventoID = $_POST['AvaliacaoPendenteEventoID'];
        $AvaliacaoPendenteUser = $_POST['AvaliacaoPendenteUser'];
        $AvaliacaoPendenteDono = $_POST['AvaliacaoPendenteDono'];
        $rating = $_POST['rating'];

        $sqlReceberAvaliacao = 'SELECT aval'.$rating.'Estrela FROM usuario WHERE idUser='.$AvaliacaoPendenteDono.'';
        $resultadoReceberAvaliacao = mysqli_query($conexao, $sqlReceberAvaliacao);
        if(mysqli_num_rows($resultadoReceberAvaliacao) > 0) {
            $receberAvaliacao = mysqli_fetch_assoc($resultadoReceberAvaliacao);
            
            $novaQuantidade = $receberAvaliacao['aval'.$rating.'Estrela'] + 1;
            $sqlAvaliarDono = 'UPDATE usuario SET aval'.$rating.'Estrela = '.$novaQuantidade.' WHERE idUser = '.$AvaliacaoPendenteDono.'';
            if(mysqli_query($conexao, $sqlAvaliarDono)) {
                $sqlRemoverAvaliacao = 'DELETE FROM avaliacaopendente WHERE idAvaliacao = "A-'.$AvaliacaoPendenteUser.'_'.$AvaliacaoPendenteEventoID.'"';
                if(mysqli_query($conexao, $sqlRemoverAvaliacao)) {
                    echo json_encode('sucesso');
                }else {
                    echo json_encode('erro');
                }
            }else {
                echo json_encode('erro');
            }
        }else {
            echo json_encode('erro');
        }
    }else {
        echo json_encode('erro');
    }
?>