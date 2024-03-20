<?php
    header ('Content-Type: application/json');

    if(isset($_POST['idEvento']) && isset($_POST['qtdeParticipantes']) && isset($_POST['dataEvento']) && isset($_POST['eventoInativo'])){

        include ('../../conexao.php');
        
        $podeEntrar = false;
        $idEvento = $_POST['idEvento'];
        $qtdeParticipantes = $_POST['qtdeParticipantes'];
        $dataEvento = $_POST['dataEvento'];
        $eventoInativo = $_POST['eventoInativo'];
        date_default_timezone_set('America/Sao_Paulo');
        $hoje = date("Y-m-d");

        if($hoje <= $dataEvento && $eventoInativo == 0) {
            $sqlRelacao = 'SELECT * FROM relacaoevento WHERE evento = '.$idEvento.'';
            $resultadoRelacao = mysqli_query($conexao, $sqlRelacao);
            if (mysqli_num_rows($resultadoRelacao) < $qtdeParticipantes) {
                $podeEntrar = true;
            }
    
            if($podeEntrar) {
                if(isset($_POST['idUser']) && $_POST['idUser'] != 0){
                    echo json_encode('EntradaValida');
                }else {
                    echo json_encode('naoLogado');
                }
            }else {
                echo json_encode('cheio');
            }
        }else {
            echo json_encode('expirado');
        }
    }
?>