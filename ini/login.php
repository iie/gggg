<?php
	require 'conet.php';

	$usuario= $mysqli->query("SELECT attribute_1,token FROM lime_tokens_952371 
		WHERE attribute_1 = '".$_POST['rut']."'");

	if ($usuario->num_rows==1):
		$datos= $usuario->fetch_assoc();
			echo json_encode($datos);
	else:
		echo json_encode(array('error'=>true));
		endif;

	$mysqli->close();
?>