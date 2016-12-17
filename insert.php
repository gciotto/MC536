<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<?php
    require 'connection.php';
    require 'invalid_words.php';

    function isValidWord($word, $array) {
	for ($i = 0; $i < count($array); $i++) {
		if (strcmp($word, $array[$i]) == 0)
			return false;
	}
	return true;
    }

    $parts = explode(' ', $_POST['tags']);

    $name = substr($_FILES["file"]["name"], 0, strpos($_FILES["file"]["name"],'.'));

    $sql = "INSERT INTO T_IMAGEM (descricao, nome) VALUES ('".$_POST['desc']."', '".$name."');";
    $result = mysql_query($sql);

    $sql = "SELECT id_imagem FROM T_IMAGEM WHERE nome = '".$name."'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);

    $id_imagem = $row['id_imagem'];
    $tags = "";
    foreach($parts as $part) {

	if (isValidWord($part, $array)) {

	      $sql = "SELECT id_chave FROM T_PALAVRA_CHAVE WHERE texto LIKE '%".$part."%'";
	      $result = mysql_query($sql);
	      $tags = $tags.$part."-";
	      if (mysql_num_rows($result) <= 0) {
		    
		    $sql = "INSERT INTO T_PALAVRA_CHAVE (texto) VALUES ('".$part."');";
		    mysql_query($sql);
	      }

	      $sql = "SELECT id_chave FROM T_PALAVRA_CHAVE WHERE texto LIKE '%".$part."%'";
	      $result = mysql_query($sql);
	      $row = mysql_fetch_array($result);
	      $id_chave = $row['id_chave'];
	      $sql = "INSERT INTO T_IMAGEM_PALAVRA_CHAVE (id_imagem, id_chave) VALUES (".$id_imagem.",".$id_chave.");";
	      mysql_query($sql);	
	}
    }
    echo "Sumario:- <br>Descricao: - ".$_POST['desc']."<br>Tags:-".$tags."<br>Inserido com sucesso! <br>";    

?>

<a href="insert.html"> Voltar < </a>
</body>
</html>