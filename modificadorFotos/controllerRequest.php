<?php

require('instanciaConexaoOracle.php');

function requisicaoDadosIMG($arg){
    $arg = (string) $arg;
    $sql = "select DISTINCT desc_st_descricao || ' ' || cor_st_cor   as saida,desenho_st_desenho from tscl.horr_vw_sku  where pro_st_alternativo like concat(:arg,'%')  ";
    $stmt = DB::prepare($sql);
    $stmt->bindParam(':arg', $arg, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();
}


?>