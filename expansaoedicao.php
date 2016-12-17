<?php 

      function ordenarVetor(&$strings, &$vetor, $tam) {
		for ($i = 0; $i < $tam; $i++) {
		    for ($j = $i+1; $j < $tam; $j++) {
				if ($vetor[$i] > $vetor[$j]) {
				      $aux1 = $vetor[$i];
				      $aux2 = $strings[$i];
				      $vetor[$i] = $vetor[$j];
				      $strings[$i] = $strings[$j];
				      $vetor[$j] = $aux1;
				      $strings[$j] = $aux2;
				}
		    }
		}

      }


      function edicao ($string, &$strings, &$resultado) {
	    
	    $sql = "SELECT DISTINCT texto FROM T_PALAVRA_CHAVE";
	    $result = mysql_query($sql);
	    
	    $i = 0;

	    while ($row = mysql_fetch_array($result)) {

		    $word = $row['texto'];

		    $r = levenshtein($string, $word);

		    if ($i < 5) {
			  $strings[$i] = $word;
			  $resultado[$i] = $r;
			  $i = $i + 1;
			  ordenarVetor($strings, $resultado, $i);
		    } else if ($r < $resultado[4]) {
			  $strings[4] = $word;
			  $resultado[4] = $r;
			  ordenarVetor($strings, $resultado, 5);
		    }

	    }

	    $tamanho = strlen($string);
	    for ($i = 0; $i < 5; $i++) {

		    if (2*$resultado[$i] > max($tamanho,strlen($strings[$i])))  {
			    $strings[$i] = "";
		    }
	    }
	  
      }

?>