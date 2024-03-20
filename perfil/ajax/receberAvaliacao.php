<?php
    header('Content-Type: application/json');

    include('../../conexao.php');

    $userID = $_POST["userID"];

    $sqlProfile = 'SELECT * FROM usuario WHERE idUser = '.$userID.'';
    $resultProfile = mysqli_query($conexao, $sqlProfile);
    if(mysqli_num_rows($resultProfile) > 0) {
        $userInfo = mysqli_fetch_assoc($resultProfile);
    }

    $ratingAmount = $userInfo['aval1Estrela'] + $userInfo['aval2Estrela'] + $userInfo['aval3Estrela'] + $userInfo['aval4Estrela'] + $userInfo['aval5Estrela'];
    $totalPoints = (($userInfo['aval1Estrela'] * 1) + ($userInfo['aval2Estrela'] * 2) + ($userInfo['aval3Estrela'] * 3) + ($userInfo['aval4Estrela'] * 4) + ($userInfo['aval5Estrela'] * 5));
    if($ratingAmount <= 0) {
        $evalAverage = $totalPoints / 1;
    } else {
        $evalAverage = $totalPoints / $ratingAmount;
    }
    $approximateAverage = (ceil($evalAverage * 100)) / 100;

    $result = array(
        'approximateAverage' => $approximateAverage,
        'ratingAmount' => $ratingAmount
    );
    echo json_encode($result);
?>