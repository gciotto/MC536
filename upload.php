<?php
	$key = count($_FILES["file"]["name"]) -1;
	move_uploaded_file($_FILES["file"]["tmp_name"][$key],
		"upload/" . $_FILES["file"]["name"][$key]); /* Move arquivo para upload/ no servidor */

	$name = substr($_FILES["file"]["name"][$key], 0, strpos($_FILES["file"]["name"][$key],'.'));

	$saida = exec("chmod 777 upload/".$_FILES["file"]["name"][$key], $saida2); /* Altera permissões */

	exec("python scripts/executa_scripts.py upload/".$_FILES["file"]["name"][$key]);

	exec("chmod 777 vetores/".$name.".nor");
?>
