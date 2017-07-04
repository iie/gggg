<?php
	require 'conet.php';

	include ("C:/wamp64/www/limesurvey/src/org/jsonrpcphp/JsonRPCClient.php");
	include ("C:/wamp64/www/limesurvey/src/org/jsonrpcphp/JsonRPCServer.php");

	define( 'LS_BASEURL', 'http://localhost/limesurvey/index.php');  // aqui actual LimeSurvey URL
	define( 'LS_USER', 'admin-martin' );// aqui actual LimeSurvey USER
	define( 'LS_PASSWORD', 'martin.martin' );// aqui actual LimeSurvey PASSWORD
	// instanciate a new client
	$myJSONRPCClient = new \org\jsonrpcphp\JsonRPCClient( LS_BASEURL.'/admin/remotecontrol' );

	// receive session key
	$sessionKey= $myJSONRPCClient->get_session_key( LS_USER, LS_PASSWORD );	
	//--requerido todo lo de arriba paracambiar los datos para el limesurvey--
	$encuestas = $myJSONRPCClient->list_surveys( $sessionKey, null ); //lista encuestas llena
	//recupero encuestas activas
	$id=0;
	$encuestasActivate=array();
	for ($i=0; $i < count($encuestas); $i++) { 
		if ($encuestas[$i]["active"]=='Y') {
			$encuestasActivate[$id]=$encuestas[$i];
			$id++;
		}
	}
	$usersEncuesta=array();// usuarios por encuesta vacio
	//llenamos lista usuarios-------------------------------------------
	$listaattr= array('id', 'completed', 'participant_id', 'language string', 'usesleft', 'firstname', 'lastname', 'email', 'blacklisted', 'validfrom', 'start_date', 'sent', 'validuntil', 'remindersent', 'mpid', 'emailstatus', 'remindercount', 'attribute_1');//datos pedidos 
	for ($i=0; $i < count($encuestasActivate) ; $i++) { 
		$usersEncuesta[$i] = $myJSONRPCClient->list_participants($sessionKey, $encuestasActivate[$i]["sid"],0,1000,false,$listaattr);//retorna participantes en la encuesta
	}
	
	
	
	//-----------------------------------------------------------------
	$usuario=array();//lista datos usuarios 
	
	//consulto rut usuarios--------------------------------------------
	for ($i=0; $i < count($encuestasActivate); $i++) { 
		$usuario[$i]= $mysqli->query("SELECT attribute_1 , token , completed FROM lime_tokens_".$encuestasActivate[$i]["sid"]." WHERE attribute_1 = '".$_POST['rut']."'");
	}
	
	//-----------------------------------------------------------------
	//-----------------------------------------------------------------
	//evaluo si existe-------------------------------------------------
	$estado=false;
	for ($i=0; $i < count($usuario); $i++) { 
		if(($usuario[$i]->num_rows==1)){
			$estado= true;		
		}
	}
	$n=0;
	//-----------------------------------------------------------------
	if ($estado) {//si existe
		$numtabla=0;
		//busco en cual existe-----------------------------------------
		$n=0; 
		while ($n<count($encuestasActivate)) {
			if (($usuario[$n]->num_rows==1)){
				$numtabla=$n;
				$n+=count($encuestasActivate);//es para salir del ciclo
			}			
			$n++;
		}
		//-------------------------------------------------------------
		$datos= $usuario[$numtabla]->fetch_assoc();
		if ($datos['completed']=='N') {
			$resultado = array('estado' => true,'texto'=>"/limesurvey/index.php/".$encuestasActivate[$numtabla]["sid"]."/lang/es/token/".$datos['token']);//aqui se arma la url de destino
		
		}else{
			$resultado = array('estado' => false,'texto'=>"/limesurvey/ini/datos.php");//aqui se arma la url de destino
		}
		echo json_encode($resultado);//mado array como json
		
	} else {//si no existe
		
		$resultado = array('estado' => false,'texto'=>"/limesurvey/ini/datos.php");//aqui se arma la url de destino
		
		echo json_encode($resultado);//mado array como json
	}
	$mysqli->close();//cierro MySQL
?>