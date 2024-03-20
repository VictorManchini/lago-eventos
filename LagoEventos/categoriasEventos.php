<?php
    
    function categoria($idCategoriaImpulsionada) {
        include '../conexao.php';
        $i = 1;
        
        $sqlCategoriasContar = "SELECT * FROM categoria where idCategoria != '$idCategoriaImpulsionada'";
        $resultCatContar = mysqli_query($conexao, $sqlCategoriasContar);
        $num_linhas_contar = mysqli_num_rows($resultCatContar);

        $sqlCategorias = "SELECT * FROM categoria where idCategoria != '$idCategoriaImpulsionada' ORDER BY idCategoria ASC LIMIT 5";
        $resultCat = mysqli_query($conexao, $sqlCategorias);
        $numLinhas = mysqli_num_rows($resultCat);
        
        if ($numLinhas > 0) {
            while($linhaCat = mysqli_fetch_assoc($resultCat)) {
                $categorias[] = $linhaCat;
        }
        ?>
        <div class="categorias-principal">   
        <?php
            foreach ($categorias as $categoria) {
                echo "<button value=".$categoria['idCategoria']." class = btnResponsivo".$i."><li>#".$categoria['nomeCategoria']."</li></button>";
                $i++;
            }
        } else {
            echo "<p class='erro-categorias-secundaria'>Não há outras categorias.</p>";
            
            ?>
            <?php
        }
        ?>
        </div>
        <?php
    ?>
    <?php
        $eventosRestantes = $num_linhas_contar - 7;
        if ($eventosRestantes > 0) {

        
        $sqlCategoriasExtra = "SELECT * FROM categoria where idCategoria != $idCategoriaImpulsionada ORDER BY idCategoria DESC LIMIT $eventosRestantes";
        $resultCatExtra = mysqli_query($conexao, $sqlCategoriasExtra);
            
        $numLinhasExtra = mysqli_num_rows($resultCatExtra);
        if ($numLinhasExtra > 0) {
            while($linhaCat = mysqli_fetch_assoc($resultCatExtra)) {
                $categoriasSecundaria[] = $linhaCat;
        
        } 
?>
       <div class="categorias-secundaria" id="categorias-secundaria">
        <?php           
            foreach ($categoriasSecundaria as $categoriaSecundaria) {
                echo "<button value=".$categoriaSecundaria['idCategoria']." class = btnResponsivo".$i."><li>#".$categoriaSecundaria['nomeCategoria']."</li></button>";
                $i++;
            }
        ?>
       </div> 
      <?php   } 
        } else {
            ?>
            <div class="categorias-secundaria" id="categorias-secundaria">
                <?php
                    echo "<p class='erro-categorias-secundaria'>Não há outras categorias.</p>";
                ?>
            </div>
            <?php
        }
    }

    function categoriaResponsivo($idCategoriaImpulsionada) {
        include '../conexao.php';
        $i = 1;
        $sqlCategoriasResponsivo = "SELECT * FROM categoria where idCategoria != $idCategoriaImpulsionada";
        $resultCat = mysqli_query($conexao, $sqlCategoriasResponsivo);
    
        $numLinhas = mysqli_num_rows($resultCat);
        
        if ($numLinhas > 0) {
            while($linhaCat = mysqli_fetch_assoc($resultCat)) {
                $categoriasResponsivo[] = $linhaCat;
            }
            
            foreach ($categoriasResponsivo as $categoriaResponsivo) {
                echo "<button value=".$categoriaResponsivo['idCategoria']." class = btnResponsivo".$i."><li>#".$categoriaResponsivo['nomeCategoria']."</li></button>";
                $i++;
                
            }
            
        }
    }
    

?>