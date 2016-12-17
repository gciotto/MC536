<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
</head>
<body>

<form name="formulario" id="myForm" target='_self' method='POST' enctype="multipart/form-data">

<input type="checkbox" id="tipo" name="escolha" hidden="true">  
<input type="checkbox" id="auxiliar" name="auxiliar" hidden="true">  
<input id="busca" name = "texto" type="text" hidden="true">

</form>

<form name="formulario2" id="myForm2" action='lookImage.php' method='POST' enctype="multipart/form-data" target="_new">
<input id="imagem_name" name = "imagem_name" type="text" hidden="true">
</form>

<a href='home.html'> < Home </a>

<div id = "resto">
<div id = "result"></div>

<?php
	require 'invalid_words.php';
	require 'expansaoedicao.php';

	function isValidWord($word, $array) {
		for ($i = 0; $i < count($array); $i++) {
			#echo $word."  =  ".$array[$i]."<br>";
			if (strcmp($word, $array[$i]) == 0) 
				return false;
		}
		return true;
	}

	function isValidString($string,$array) {
		if ((strlen($string) > 8 || strlen($string) < 3 ) && isValidWord($string, $array))
		    return false;
		return true;
	}

	function thereisImage($image, $select_images) {
		 for ($i = 0; $i < count($select_images); $i++)
		      if (strcmp($image, $select_images[$i]) == 0)
			  return true;

		 return false;
	}

	function ordena_vetores(&$vetor_images, &$vetor_count, $tam) {
		
		for ($i = 0; $i < $tam; $i++) {
		      for ($j = $i+1; $j < $tam; $j++) {
			    if ($vetor_count[$i] < $vetor_count[$j]) {
				  echo $i." < ".$j."<br>";
				  $aux1 = $vetor_count[$i];
				  $aux2 = $vetor_images[$i];
				  echo $aux1." - ".$aux2."<br>";
				  $vetor_images[$i] = $vetor_images[$j];
				  $vetor_count[$i] = $vetor_count[$j];
				  $vetor_images[$j] = $aux2;
				  $vetor_count[$j] = $aux1;
			    }
		      }
		}
	}

	/* sql2.lab.ic.unicamp.br/phpMyAdmin */
	require 'connection.php';
	

	if (!isSet($_POST['escolha'])) { /* Busca por Texto */
		$parts = explode(' ', $_POST['texto']);
		$number = 0;

		$sql = "SELECT DISTINCT ip.id_imagem
			FROM T_IMAGEM_PALAVRA_CHAVE ip
			WHERE ";

		$alternative_sql = "SELECT DISTINCT ip.id_imagem
			FROM T_IMAGEM_PALAVRA_CHAVE ip, T_PALAVRA_CHAVE p
			WHERE p.id_chave = ip.id_chave ";
		
		$strings = array(); $results = array();
		$first = true;	$firstt = true;
		foreach ($parts as &$part) {
			if (isValidWord($part,$array)) {
				if (!$first) $string = " AND ip.id_imagem IN (SELECT id_imagem FROM T_PALAVRA_CHAVE pc, T_IMAGEM_PALAVRA_CHAVE imp WHERE pc.id_chave = imp. id_chave AND texto = '".$part."')";
				else $string = "ip.id_imagem IN (SELECT id_imagem FROM T_PALAVRA_CHAVE pc, T_IMAGEM_PALAVRA_CHAVE imp WHERE pc.id_chave = imp. id_chave AND texto = '".$part."')";
				$sql = $sql.$string;
				$first = false;
				edicao($part, &$strings, &$results);

				
				for ($i = 0; $i < 5; $i++) {
				      if (isValidWord($strings[$i], $array)) {
					    if ($firstt) echo "Did you want to say...? ";						  
					    echo "<span class='fake-link' onclick=searchForLink('".$strings[$i]."')>".$strings[$i]."</span> ";
					    $firstt = false;
				      }
				}

			}
		}

		
		echo "<br>";
		$result = mysql_query($sql);
		$number = 0;

		while (($row = mysql_fetch_array($result)) && $number <20) {
			$sql2 = "SELECT nome FROM T_IMAGEM WHERE id_imagem = ".$row['id_imagem'];
			$result2 = mysql_query($sql2);
			$row2 = mysql_fetch_array($result2);
			$number++;
			$nome = $row2['nome'];
			$alternative_sql = $alternative_sql." AND ip.id_imagem != ".$row['id_imagem'];
			echo "\n<img src = 'images/".$nome.".jpg' id = 'images'  onclick='image_click(this)'/>";
		}

		if   ($number < 20) {
			
			$alternative_sql = $alternative_sql." AND (";
			$first = true;
			foreach ($parts as &$part) {
				if (isValidWord($part,$array)) {
					if (!$first) $string = " OR p.texto LIKE '%".$part."%'";
					else $string = " p.texto LIKE '%".$part."%'";

					$alternative_sql = $alternative_sql.$string;
					$first = false;				
				}
			}
			$alternative_sql = $alternative_sql.");";

			$result = mysql_query($alternative_sql);
			
			while (($row = mysql_fetch_array($result)) && ($number < 20)) {
				$sql2 = "SELECT nome FROM T_IMAGEM WHERE id_imagem = ".$row['id_imagem'];
				$result2 = mysql_query($sql2);
				$row2 = mysql_fetch_array($result2);
				$nome = $row2['nome'];
				$number++;
				echo "\n<img src = 'images/".$nome.".jpg' id = 'images' onclick='image_click(this)'/>";
				
			}
		}

		echo "<script> document.getElementById('result').innerHTML = ";
		if ($number > 0) echo "'".$number." image(s) found.'";
		else echo "'No images found.'";
		echo "</script>";

	} else { /* Busca por Conteudo */
		/* Etapas : 	1) Gerar vetor .norm
				2) Chamar ./busca_por_imagem <-- arq saida --> I) n elementos II) cada linha é a IMG 
		*/
		$timeAntigo = time();
  
		if (!isSet($_POST['auxiliar']))	{ 
		      $name = substr($_FILES["file"]["name"], 0, strpos($_FILES["file"]["name"],'.'));
		      copy("vetores/".$name.".nor", "scripts/vetor_lido_exaustiva.nor");
		}
		else if (isSet($_POST['auxiliar'])) {
		      $name = $_POST['texto'];
		      copy("normalizados/".$name.".nor", "scripts/vetor_lido_exaustiva.nor");
		}

		//exec("chmod 777 scripts/vetor_lido_exaustiva.nor");

		echo "<br>";
		$path = "";

		while(!file_exists("scripts/vetor_lido.out"));


		exec("rm -f scripts/vetor_lido_exaustiva.nor");

		copy("scripts/vetor_lido.out", "scripts/".$name.".out");
		exec("chmod 777 scripts/".$name.".out");
		$timeFinish = time();

		exec("rm -f scripts/vetor_lido.out");
		//unlink("scripts/vetor_lido.out");

		$handle = fopen("scripts/".$name.".out", "r");
		list($f_count) = fscanf($handle, "%d");
		
		$aux = 0;
		$count = 0;

		$select_images = array();

		$sql_teste = "SELECT c.texto as texto, COUNT(*) AS q FROM T_IMAGEM i, T_PALAVRA_CHAVE c, T_IMAGEM_PALAVRA_CHAVE ic WHERE
				  ic.id_imagem = i.id_imagem AND c.id_chave = ic.id_chave AND (" ;
		$primeiro = true;
		while (!feof($handle) && $count < 15){
			$image = fgets($handle);
			
			$image = substr($image, 0, strrpos($image, ".jpg"));

			
			if (!thereisImage($image, $select_images))
			    $select_images[$aux++] = $image;
			
			if ($count < 15)
			if ($primeiro ){ $sql_teste = $sql_teste."i.nome = '".$image."'"; $primeiro = false;}
			else $sql_teste = $sql_teste." OR i.nome = '".$image."'";

			$count++;
			
		}

		exec("rm -f scripts/".$name.".out");

		$sql_teste = $sql_teste.") GROUP BY c.texto ORDER BY q DESC";

		$result = mysql_query($sql_teste);

		echo "Have you been looking for more...?";
		$count = 0;
		while (($row = mysql_fetch_array($result)) && $count < 10) {
			$string = $row['texto'];
			if (isValidString($string, $array)) {
			    echo " <span class='fake-link' onclick=searchForLink('".$string."')>".$string."</span> ";
			    $count++;
			}
		}

		echo "<br>";

		if ($aux > 0) {
		      for ($i = 0; $i < $aux; $i++) {
			    echo "<img src='images/".$select_images[$i].".jpg' id = 'images' onclick='image_click(this)'\>\n";
		      }
		}
		echo "<script> document.getElementById('result').innerHTML = '";
		if ($f_count > 0) echo $f_count." similar image(s) found.";
		else echo "No images found.";
		echo " Busca gerada em ".($timeFinish - $timeAntigo)." ms.'</script>";

		fclose($handle);
	}
	mysql_close($cn);
?>
</div>

<script>
      var isShowing = false, auxiliar = false;

      function image_click(t) {
	      document.getElementById("imagem_name").value = t.src.substring(33, t.src.indexOf(".jpg"));
	      document.getElementById("myForm2").submit();
      }

      function searchForLink(tag) {
	      document.getElementById("tipo").checked = false;
	      document.getElementById("busca").value = tag;
	      document.getElementById("myForm").submit();
      }

</script>
</body>
</html>


