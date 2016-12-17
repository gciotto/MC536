<?php

    require 'connection.php';

    $sql = "SELECT descricao FROM T_IMAGEM WHERE nome = '".$_POST['SRC']."'";

    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);

    echo $row['descricao'];
?>
