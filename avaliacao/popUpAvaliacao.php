<?php
        if(isset($_SESSION['nome'])) {

            $sqlAvaliacao = 'SELECT * FROM avaliacaopendente WHERE usuario = '.$idUser.' LIMIT 1';
            $resultadoAvaliacao = mysqli_query($conexao, $sqlAvaliacao);
            if(mysqli_num_rows($resultadoAvaliacao) > 0) {
                $avaliacaoPendente = mysqli_fetch_assoc($resultadoAvaliacao);
                $sqlEvento = 'SELECT * FROM evento WHERE idEvento = '.$avaliacaoPendente["evento"].'';
                $resultadoEvento = mysqli_query($conexao, $sqlEvento);
                if(mysqli_num_rows($resultadoEvento) > 0) {
                    $eventoPendenteInfo = mysqli_fetch_assoc($resultadoEvento);
                    date_default_timezone_set('America/Sao_Paulo');
                    $hoje = date("Y-m-d");

                    if($hoje > $eventoPendenteInfo['dataEvento'] || $eventoPendenteInfo['terminadoManualmente'] == 1){
                        $sqlDonoEvento = 'SELECT * FROM usuario WHERE idUser = '.$eventoPendenteInfo["criadorEvento"].'';
                        $resultadoCriador = mysqli_query($conexao, $sqlDonoEvento);
                        if(mysqli_num_rows($resultadoCriador) > 0) {
                            $criadorInfo = mysqli_fetch_assoc($resultadoCriador);
                            ?>
                                <div class="fade-avaliar">
                                    <div class="avaliar">
                                        <h1>Avaliar</h1>
                                        <p class="nome"><a href="../perfil/perfil.php?id=<?php echo $criadorInfo['idUser']; ?>" target="_blank"><?php echo $criadorInfo['nomeUser']; ?></a></p>
                                        <a class="linkImg" href="../perfil/perfil.php?id=<?php echo $criadorInfo['idUser']; ?>" target="_blank">
                                            <div class="container-img">
                                                <img src="<?php echo $criadorInfo['fotoUser']; ?>">
                                            </div>
                                        </a>
                                        <p>Dono(a) do Evento <a href="paginaDoEvento.php?id=<?php echo $eventoPendenteInfo['idEvento']; ?>" target="_blank"><?php echo $eventoPendenteInfo['nomeEvento']; ?></a></p>
                                        <div class="rating-wrapper">
                                            <div class="ratings">
                                                <span data-rating="5">&#9733;</span>
                                                <span data-rating="4">&#9733;</span>
                                                <span data-rating="3">&#9733;</span>
                                                <span data-rating="2">&#9733;</span>
                                                <span data-rating="1">&#9733;</span>
                                            </div>
                                        </div>
                                        <div class="buttons">
                                            <button class="btn-avaliar" data-id="<?php echo $criadorInfo['idUser']; ?>" disabled>Avaliar</button>
                                            <button class="btn-naoAvaliar">Não Obrigado</button>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    let AvaliacaoPendenteEventoID = <?php echo $eventoPendenteInfo['idEvento']; ?>;
                                    let AvaliacaoPendenteUser = <?php echo $idUser; ?>;
                                    let AvaliacaoPendenteDono = <?php echo $criadorInfo['idUser']; ?>;
                                </script>
                            <?php
                        }
                    }
                }
            }
        }
?>