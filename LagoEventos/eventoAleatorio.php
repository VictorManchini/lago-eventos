<?php
function categoriaAleatorio() {
    // Consulta aleatória para selecionar uma categoria
    include '../conexao.php';
    $verificador = false;
    while ($verificador == false) {
        $sql_categoria = "SELECT * FROM categoria ORDER BY RAND() LIMIT 1";
        $result_categoria = mysqli_query($conexao, $sql_categoria);
        if ($result_categoria->num_rows > 0) {
            $row_categoria = $result_categoria->fetch_assoc();
            $idCategoria = $row_categoria["idCategoria"];
            $nomeCategoria = $row_categoria["nomeCategoria"];
        } else {
            echo "Não há categorias";
        }

        $sql_evento = "SELECT * FROM evento WHERE idCategoria = $idCategoria ORDER BY RAND() LIMIT 1";
        $result_evento = mysqli_query($conexao, $sql_evento);
        $linhasEvento = mysqli_num_rows($result_evento);
        if ($linhasEvento > 0) {
            $infosCat = array($idCategoria, $nomeCategoria);
            $verificador = true;
            return $infosCat;          
        } else {
            $infosCat = "Não há eventos!";
            return $infosCat; 
        }
    } 
} 

function eventoAleatorio($idCategoria) {
    date_default_timezone_set('America/Sao_Paulo');
    $dataAtualImpulsionado = date("Y-m-d");
    include '../conexao.php';
    // Consulta aleatória para selecionar um evento associado a essa categoria
    $sql_evento = "SELECT * FROM evento WHERE idCategoria = '$idCategoria' AND dataEvento >= '$dataAtualImpulsionado' ORDER BY RAND() LIMIT 1";
    $result_evento = mysqli_query($conexao, $sql_evento);

    if ($result_evento->num_rows > 0) {
        $row_evento = $result_evento->fetch_assoc();
         
        $idEvento = $row_evento['idEvento'];
        $nomeEvento = $row_evento["nomeEvento"];
        $imagemEvento = $row_evento["imagemEvento"];
        $infosEvento = array($nomeEvento, $imagemEvento, $idEvento);
    } else {
        $infosEvento = "Nenhum evento encontrado para esta categoria.";
    }

    return $infosEvento;
}

function eventos($idCategoria, $idEvento) {
    date_default_timezone_set('America/Sao_Paulo');
    $dataAtualImpulsionado = date("Y-m-d");
    include '../conexao.php';
    $sqlEventos = "SELECT * FROM evento where idCategoria = '$idCategoria' AND dataEvento >= '$dataAtualImpulsionado'";
    $resultado = mysqli_query($conexao, $sqlEventos) ;
    $num_linhas = mysqli_num_rows($resultado);

    if ($num_linhas > 0) {
        while ($linhaEvento = mysqli_fetch_assoc($resultado)) {
            $eventos[] = $linhaEvento;
        }
        foreach ($eventos as $evento) { 
            if ($evento['idEvento'] != $idEvento) { ?>
                <div class="container-card-evento">
                        <div class="card-evento">
                            <div class="card-body">
                                <figure>
                                    <img src="<?php echo $evento['imagemEvento']; ?>" class="card-img-top" alt="...">
                                    <div class="container-texto-evento">
                                        <figcaption class="nome-evento-impulsionado"><?php echo $evento['nomeEvento']; ?></figcaption>
                                        <p class="descricao-evento-impulsionado"><?php echo $evento['descricaoEvento']; ?></p>
                                    </div>  
                                </figure>
                                <button class="btn-saiba-mais"><a href="paginaEventoVisitante.php?id=<?php echo $evento['idEvento'] ?>" class="link-evento">Saiba Mais</a></button>
                            </div>
                        </div>
                    </div>
        <?php  
            }
            if($num_linhas == 1) {
                echo "<p class='msg-erro-eventos'>Não existem outros eventos com essa categoria!</p>";
            }
        };
    }
}

?>
<script>
    // Função para atualizar a página
    function atualizarPagina() {
        location.reload(); // Recarrega a página
    }

    // Calcula o tempo restante até a próxima atualização (nesse caso, 24 horas)
    var tempoRestante = 24 * 60 * 60 * 1000; // 24 horas em milissegundos

    // Configura um temporizador para disparar a função de atualização a cada 24 horas
    setTimeout(atualizarPagina, tempoRestante);
</script>
</body>
</html>
