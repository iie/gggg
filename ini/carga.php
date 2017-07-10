<?php
require 'conet.php';
// without composer this line can be used
// require_once 'path/to/your/rpcclient/jsonRPCClient.php';
// with composer support just add the autoloader
include ("C:/wamp64/www/limesurvey/src/org/jsonrpcphp/JsonRPCClient.php");
include ("C:/wamp64/www/limesurvey/src/org/jsonrpcphp/JsonRPCServer.php");
define( 'LS_BASEURL', 'http://localhost/limesurvey/index.php');  // aqui actual LimeSurvey URL
define( 'LS_USER', 'admin-martin' );// aqui actual LimeSurvey USER
define( 'LS_PASSWORD', 'martin.martin' );// aqui actual LimeSurvey PASSWORD
// instanciate a new client
$myJSONRPCClient = new org\jsonrpcphp\JsonRPCClient( LS_BASEURL.'/admin/remotecontrol' );
// receive session key
$sessionKey= $myJSONRPCClient->get_session_key( LS_USER, LS_PASSWORD );//crear clave sesion
$listaattr= array('id', 'completed', 'participant_id', 'firstname', 'lastname', 'email', 'sent', 'validuntil', 'remindersent', 'attribute_1');
//creo el participante
$participante= array ( 'user' => array ('email' =>  $_POST['email'],'lastname' =>  $_POST['apellido'],'firstname' =>   $_POST['nombre'],'token'=>rut_formato_token($_POST['rut']), 'attribute_1'=>rut_formato_atributo1($_POST['rut'])));//usuario para incorporar
$encuestas = $myJSONRPCClient->list_surveys( $sessionKey, null ); //lista encuestas llena
//filtro las encuestas activas en $encuestaok
$id=0; 
$encuestaok = array();
for ($x=0; $x < count($encuestas); $x++) {
	if ($encuestas[$x]["active"] == 'Y') {
		$encuestaok[$id]= $encuestas[$x];
		$id++;
	}
}
//busco mail en las encuestas 
$emails = array();
for ($i=0; $i < count($encuestaok); $i++) { 
	$emails[$i]= $mysqli->query("SELECT email FROM lime_tokens_".$encuestaok[$i]["sid"]." WHERE email = '".$_POST['email']."'");
}
$usersEncuesta=array();// usuarios por encuesta vacio
$users=array();// usuarios por encuesta vacio
//llenamos lista usuarios-------------------------------------------falta restringir
for ($i=0; $i < count($encuestaok) ; $i++) { 
	$usersEncuesta[$i] = $mysqli->query("SELECT * FROM lime_tokens_".$encuestaok[$i]["sid"]);
} 
for ($i=0; $i < count($encuestaok) ; $i++) { 
	for ($j=0; $j < $usersEncuesta[$i]->num_rows ; $j++) { 
		$users[$i][$j]=$usersEncuesta[$i]->fetch_assoc();
	}
}
echo "<pre>";
   print_r($users,null);
echo "</pre>";
echo "<pre>";
   print_r($usersEncuesta,null);
echo "</pre>";
$valida= false;//condicion que indica que el mail no se repite
for ($i=0; $i <count($encuestaok) ; $i++) { 
	if ($emails[$i]->num_rows>=1) {
		$valida= true;//si se repite en cualquier momento 
	} 
}
$cont=array();//array cont para contar a los usuarios 
for ($j=0; $j < count($encuestaok); $j++) { 
	$cont[$j] = $usersEncuesta[$j]->num_rows;//sacamos numero de participantes en la encuesta 
}
//comparo menor-------------------------------------------------
$iNumeroMenor = $cont[0];//eligo como menor el primero
$iPosicion = 0;//elijo la posision 0 como menor
for ($x=1; $x < count($cont); $x++) { //comparo con el segundo en adelante
	if ($cont[$x]<$iNumeroMenor){//si el actual es menor que el menor 
		$iNumeroMenor = $cont[$x];//remplaso por el contador actual
		$iPosicion = $x;//guardo su posicion
	}
}
//---------------------------------------------------------------

if ($valida) {
	$myJSONRPCClient->release_session_key( $sessionKey );//cierra sesion
	$resultado = array('estado' => false, 'txt'=>"el email ya fue ingresado");//aqui se arma mensage de error
	echo json_encode($resultado);
}else{	
	$sujeto = $myJSONRPCClient->add_participants($sessionKey, $encuestaok[$iPosicion]["sid"],$participante, false);
	$myJSONRPCClient->release_session_key( $sessionKey );//cierra sesion
	$resultado = array('estado' => true, 'txt'=>"/limesurvey/index.php/".$encuestaok[$iPosicion]["sid"]."/lang/es/token/".$sujeto["user"]["token"]);//aqui se arma la url de destino
	
	
	echo json_encode($resultado);
}	


function rut_formato_token( $rut ){//formatea rut xx.xxx.xxx-x, y xxxxxxxxx a xxxxxxxx 
	if ($rut[2]=='.' && $rut[6]=='.' && $rut[10]=='-') {
		return substr($rut,0,2).''.substr($rut,3,3).''.substr($rut,7,3);
	}else{
		return substr($rut,0,8);
	}
}
function rut_formato_atributo1( $rut ){//formatea rut xxxxxxxx-x y xxxxxxxxx a xx.xxx.xxx-x
	if ($rut[2]=='.' && $rut[6]=='.' && $rut[10]=='-') {
		return $rut;
	} else {
		if($rut[8]=='-'){
			return substr($rut,0,2).'.'.substr($rut,2,3).'.'.substr($rut,5,3).substr($rut,8,2);
		} else{
			return substr($rut,0,2).'.'.substr($rut,2,3).'.'.substr($rut,5,3).'-'.substr($rut,8,1);

		}
	}
}
?>