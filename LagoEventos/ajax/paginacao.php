<?php
    header('Content-Type: application/json');

    include '../../conexao.php';

    $idCategoria = $_POST['idCategoria'];

    $itensPagina = $_POST["itensPagina"];

    $paginaAtual = $_POST["pagina"];

    $sqlTotal = "SELECT COUNT(*) as total FROM evento where idCategoria = $idCategoria";
    $resultadoTotal = mysqli_query($conexao, $sqlTotal);
    $linhaTotal = mysqli_fetch_assoc($resultadoTotal);
    $totalItens = $linhaTotal['total'];
    $totalPaginas = ceil($totalItens / $itensPagina);
    echo json_encode($totalPaginas);
?>