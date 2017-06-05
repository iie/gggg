<?php
	require 'conet.php';
	include ("C:/wamp64/www/limesurvey/src/org/jsonrpcphp/JsonRPCClient.php");
	include ("C:/wamp64/www/limesurvey/src/org/jsonrpcphp/JsonRPCServer.php");

	define( 'LS_BASEURL', 'http://localhost/limesurvey/index.php');  // adjust this one to your actual LimeSurvey URL
	define( 'LS_USER', 'admin-martin' );
	define( 'LS_PASSWORD', 'martin.martin' );
	// instanciate a new client
	$myJSONRPCClient = new \org\jsonrpcphp\JsonRPCClient( LS_BASEURL.'/admin/remotecontrol' );

	// receive session key
	$sessionKey= $myJSONRPCClient->get_session_key( LS_USER, LS_PASSWORD );	
	//--------------------------requerido todo lo de arriba paracambiar los datos para el limesurvey-------------------------
	$encuestas = $myJSONRPCClient->list_surveys( $sessionKey, null ); //lista encuestas 
	$usersEncuesta=array();

	for ($i=0; $i < count($encuestas) ; $i++) { 
		$usersEncuesta[$i] = $myJSONRPCClient->list_participants($sessionKey, $encuestas[$i]["sid"],0,10,true,false);//retorna participantes en la encuesta
	}

	$usuario=array();

	for ($i=0; $i < count($encuestas); $i++) { 
		$usuario[$i]= $mysqli->query("SELECT attribute_1,token FROM lime_tokens_".$encuestas[$i]["sid"]." WHERE attribute_1 = '".$_POST['rut']."'");
	}

	$estado=false;
	$n=0;
	while ($estado==false) {
		if(($usuario[$n]->num_rows==1)){
			$estado= true;		
		}
	}
	$n=0;
	if ($estado) {
		$numtabla=0;
		//busco en cual
		$n=0; 
		while ($n<count($encuestas)) {
			if (($usuario[$n]->num_rows==1)){
				$numtabla=$n;
				$n=4;
			}			
			$n++;
		}
		$datos= $usuario[$numtabla]->fetch_assoc();
		$resultado = array('estado' => true,'texto'=>"/limesurvey/index.php/".$encuestas[$numtabla]["sid"]."/lang/es/token/".$datos['token']);
		echo json_encode($resultado);
	} else {
		$cont=array();
		for ($j=0; $j < 3; $j++) { 
			$cont[$j] = count($usersEncuesta[$j]);//sacamos numero de participantes en la encuesta 
		}
		$iNumeroMenor = $cont[0];
   		$iPosicion = 0;
		for ($x=1; $x < count($cont); $x++) { 
			if ($cont[$x]<$iNumeroMenor){
				$iNumeroMenor = $cont[$x];
				$iPosicion = $x;
			}
		}
		$resultado = array('estado' => false,'texto'=>("/limesurvey/index.php/".$encuestas[$iPosicion]["sid"]."?lang=es"));
		
		echo json_encode($resultado);
	}
	
	
	$mysqli->close();
?>