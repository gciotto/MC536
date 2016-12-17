<?php
    $host 	= "localhost";  	// host do mysql
    $username	= "root";       	// usuario
    $password 	= "dUb1z1nh0";		// senha do usuario
    $base 	= "mc53618"; 		// nome da base de dados

    // conecta o mysql
    $cn = mysql_connect($host, $username, $password) or 
	die ("<br><br><center>Problemas ao conectar no servidor: " . mysql_error() . "</center>");

    // seleciona a base de dados
    $banc = mysql_select_db($base) or 
	die ("<br><br><center>Problemas ao selecionar a base de dados do sistemas: " . mysql_error() . "</center>");
?>
