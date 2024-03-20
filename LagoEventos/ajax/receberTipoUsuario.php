<?php
    header('Content-Type: application/json');

    
    if(isset($_POST['idUsuario']) && isset($_POST['idEvento'])) {

        include '../../conexao.php';
        $idUsuario = $_POST['idUsuario'];
        $idEvento = $_POST['idEvento'];

        $sqlEvento = 'SELECT * FROM evento WHERE idEvento = '.$idEvento.' AND criadorEvento = '.$idUsuario.'';
        $resultadoEvento = mysqli_query($conexao, $sqlEvento);
        if(mysqli_num_rows($resultadoEvento) > 0) {
            echo json_encode('dono');
        }else {
            $sqlRelacao = 'SELECT * FROM relacaoevento WHERE usuario = '.$idUsuario.' AND evento = '.$idEvento.' AND idRelacao = "'.$idUsuario.'_'.$idEvento.'"';
            $resultadoRelacao = mysqli_query($conexao, $sqlRelacao);
            if(mysqli_num_rows($resultadoRelacao) > 0) {
                $relacaoInfo = mysqli_fetch_assoc($resultadoRelacao);

                if($relacaoInfo['tipoUserRelation'] == 1) {
                    echo json_encode('adm');
                }else {
                    echo json_encode('user');
                }
            }
        }
    }
?>