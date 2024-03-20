<?php
        header('Content-Type: application/json');

        include '../../conexao.php';

        $idCategoria = $_POST['idCategoria']; 
        
        $itensPagina = $_POST['itensPagina'];

        $paginaAtual = $_POST['pagina'];

        $dataAtual = $_POST['dataAtual'];

        $dataSelecionada = $_POST['dataSelecionada'];

        $pontoPartida = ($paginaAtual - 1) * $itensPagina;

        if ($idCategoria == 0) {
            if ($dataSelecionada != "") {
                $sql = "SELECT * FROM evento WHERE dataEvento >= '$dataSelecionada' ORDER BY dataEvento";
            } else {
                $sql = "SELECT * FROM evento WHERE dataEvento >= '$dataAtual' ORDER BY dataEvento";
            }
        } else {
            if ($dataSelecionada != "") {
                $sql = "SELECT * FROM evento WHERE idCategoria = $idCategoria AND dataEvento >= '$dataSelecionada' ORDER BY dataEvento";
            } else {
                $sql = "SELECT * FROM evento WHERE idCategoria = $idCategoria AND dataEvento >= '$dataAtual' ORDER BY dataEvento";
            }
        }
        
        $resultado = mysqli_query($conexao, $sql);
        if (mysqli_num_rows($resultado) > 0) {
            while ($linha = mysqli_fetch_assoc($resultado)){
                $eventos[] = $linha;
            }
            echo json_encode($eventos);
        }else {
            echo json_encode("erro");
        }
?>