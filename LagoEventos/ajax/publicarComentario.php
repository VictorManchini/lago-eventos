<?php
    header('Content-Type: application/json');

    include ('../../conexao.php');

    if(isset($_POST['idEvento']) && isset($_POST['idUsuario']) && isset($_POST['comentario'])) {


        $idEvento = $_POST['idEvento'];
        $idUsuario = $_POST['idUsuario'];
        $comentario = $_POST['comentario'];

        if($comentario != ''){
            $sqlRelacao = 'SELECT * FROM relacaoevento WHERE evento = '.$idEvento.' AND usuario = '.$idUsuario.' AND idRelacao = "'.$idUsuario.'_'.$idEvento.'" ';
            $resultadoRelacao = mysqli_query($conexao, $sqlRelacao);
            if(mysqli_num_rows($resultadoRelacao) > 0) {
    
                $sqlComentarios = 'SELECT * FROM comentarioevento WHERE evento = '.$idEvento.' ORDER BY ordem DESC LIMIT 1';
                $resultadoComentarios = mysqli_query($conexao, $sqlComentarios);
                if(mysqli_num_rows($resultadoComentarios) > 0) {
                    $linhaComentarios = mysqli_fetch_assoc($resultadoComentarios);
    
                    $posicaoNovoComentario = 1 + $linhaComentarios['ordem'];
                }else {
                    $posicaoNovoComentario = 1;
                }
                
                $sqlMeusComentarios = 'SELECT * FROM comentarioevento WHERE evento = '.$idEvento.' AND usuario = '.$idUsuario.' AND relacaoEvento = "'.$idUsuario.'_'.$idEvento.'" ORDER BY ordem DESC';
                $resultadoMeusComentarios = mysqli_query($conexao, $sqlMeusComentarios);
                if(mysqli_num_rows($resultadoMeusComentarios) > 0) {
                    $i = 0;
                    while($linhaMeusComentarios = mysqli_fetch_assoc($resultadoMeusComentarios)){
                        if($i == 0) {
                            $meuUltimoComentario = $linhaMeusComentarios;
                            $i++;
                        }
                    }
                    $meuUltimoComentarioSemLetra = substr($meuUltimoComentario['idComentario'], 1);
                    $meuUltimoComentarioDivido = explode('-', $meuUltimoComentarioSemLetra);
    
                    $qtdeMeusComentarios = $meuUltimoComentarioDivido[0];
                }else {
                    $qtdeMeusComentarios = 0;
                }
    
    
                $sqlInserirComentario = 'INSERT INTO comentarioevento(idComentario, usuario, evento, relacaoEvento, comentario, ordem) values("C'.($qtdeMeusComentarios + 1).'-'.$idUsuario.'_'.$idEvento.'", '.$idUsuario.', '.$idEvento.', "'.$idUsuario.'_'.$idEvento.'", "'.$comentario.'", '.$posicaoNovoComentario.')';
    
                if(mysqli_query($conexao, $sqlInserirComentario)){
                    echo json_encode('sucesso');
                }
            }
        }
    }

?>