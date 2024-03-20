<?php
    header('Content-Type: application/json');
    
    include '../../conexao.php';
    $idCategoria = $_POST['idCategoria'];

    $sqlCategoria = "SELECT * FROM categoria WHERE idCategoria = $idCategoria";
    $resultadoCategoria = mysqli_query($conexao, $sqlCategoria);
    $linhaCategoria = mysqli_fetch_assoc($resultadoCategoria);
    echo json_encode($linhaCategoria);
?>