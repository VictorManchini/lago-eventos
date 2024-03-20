<?php
    header('Content-Type: application/json');

    include '../../conexao.php';

    $pesquisa = $_POST['pesquisa'];

    $sqlPesquisa = "SELECT * FROM evento WHERE nomeEvento LIKE '%$pesquisa%'";
    $resultadoPesquisa = mysqli_query($conexao, $sqlPesquisa);
    if (mysqli_num_rows($resultadoPesquisa) > 0) {
        while ($linha = mysqli_fetch_assoc($resultadoPesquisa)){
            $eventos[] = $linha;
        }
        echo json_encode($eventos);
    }else {
        echo json_encode("erro");
    } 