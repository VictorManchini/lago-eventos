<?php
    header('Content-Type: application/json');

    include ('../../conexao.php');

    if(isset($_POST['idEvento']) && isset($_POST['idUsuario']) && isset($_POST['donoEvento']) && isset($_POST['excluirEvento'])) {
        $idEvento = $_POST['idEvento'];
        $idUsuario = $_POST['idUsuario'];
        $donoEvento = filter_var($_POST['donoEvento'], FILTER_VALIDATE_BOOLEAN);
        $excluirEvento = $_POST['excluirEvento'];
        if($donoEvento && $excluirEvento == 1) {
            $sqlEvento = 'SELECT * FROM evento WHERE idEvento = '.$idEvento.'';
            $resultadoEvento = mysqli_query($conexao, $sqlEvento);
            if(mysqli_num_rows($resultadoEvento) > 0) {
                $eventoInfo = mysqli_fetch_assoc($resultadoEvento);
                if(file_exists('../'.$eventoInfo['imagemEvento'].'')) {
                    if('../'.$eventoInfo['imagemEvento'].'' != '../../img/imgEventos/fundoImagemAzul.png') {
                        unlink('../'.$eventoInfo['imagemEvento'].'');
                    }

                    $sqlExcluirEvento = 'DELETE FROM evento WHERE idEvento = '.$idEvento.'';
                    if(mysqli_query($conexao, $sqlExcluirEvento)) {
                        echo json_encode('sucesso');
                    } 
                }else {
                    $sqlExcluirEvento = 'DELETE FROM evento WHERE idEvento = '.$idEvento.'';
                    if(mysqli_query($conexao, $sqlExcluirEvento)) {
                        echo json_encode('sucesso');
                    } 
                }
            }
        }else if($donoEvento && $excluirEvento == 0) {
            $sqlTerminarEvento = 'UPDATE evento SET terminadoManualmente = 1 WHERE idEvento = '.$idEvento.'';
            if(mysqli_query($conexao, $sqlTerminarEvento)) {
                echo json_encode('sucesso');
            }
        }else {
            $sqlSairEvento = 'DELETE FROM relacaoevento WHERE usuario = '.$idUsuario.' AND evento = '.$idEvento.'';
            if(mysqli_query($conexao, $sqlSairEvento)) {
                echo json_encode('sucesso');
            }
        }
    }
?>