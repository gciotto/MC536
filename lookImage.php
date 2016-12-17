<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
</head>
<body>

<form name="formulario" id="myForm" target='_new' method='POST' action="processing.php" enctype="multipart/form-data">

<input type="checkbox" id="tipo" name="escolha" hidden="true">  
<input type="checkbox" id="auxiliar" name="auxiliar" hidden="true">  
<input id="busca" name = "texto" type="text" hidden="true">

</form>


<?php
      require 'connection.php';
      
      echo "<img src='images/".$_POST['imagem_name'].".jpg' id='image_clicks'/>";

      $sql = "SELECT descricao FROM T_IMAGEM WHERE nome = '".$_POST['imagem_name']."'";

      $result = mysql_query($sql);
      $row = mysql_fetch_array($result);
  

      echo "<div id='descricao'>".$row['descricao']."</div>";
      echo "</div>";
?>

<script>

      function searchForImage(tag) {
	      document.getElementById("tipo").checked = true;
	      document.getElementById("auxiliar").checked = true;
	      document.getElementById("busca").value = tag;
	      document.getElementById("myForm").submit();
      }

</script>
</body>
</html>