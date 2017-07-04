<?php

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
/*datos de pruba 
$participante = array( 'user' => array('email' =>  "asdf@asdf.asdf",'lastname' =>   "ignacio",'firstname' =>   "alvarez",'token'=>"16269874", 'attribute_1'=>"16.269.874-5" , 'attribute_2' =>"Municipal", 'attribute_3' =>"7", 'attribute_4' =>"Femenino", 'attribute_5' =>"1 a 6"));
*/
$listaattr= array('id', 'completed', 'participant_id', 'firstname', 'lastname', 'email', 'sent', 'validuntil', 'remindersent', 'attribute_1');
//creo el participante
$participante= array ( 'user' => array ('email' =>  $_POST['email'],'lastname' =>   $_POST['apellido'],'firstname' =>   $_POST['nombre'],'token'=>rut_formato_token($_POST['rut']), 'attribute_1'=>rut_formato_atributo1($_POST['rut'])));

$encuestas = $myJSONRPCClient->list_surveys( $sessionKey, null ); //lista encuestas llena
$id=0; 
$encuestaok = array();
for ($x=1; $x < count($encuestas); $x++) {
	if ($encuestas[$x]["active"] == 'Y') {
		$encuestaok[$id]= $encuestas[$x];
		$id++;
	}
}

$usersEncuesta=array();// usuarios por encuesta vacio
//llenamos lista usuarios-------------------------------------------
for ($i=0; $i < count($encuestaok) ; $i++) { 
	$usersEncuesta[$i] = $myJSONRPCClient->list_participants($sessionKey, $encuestaok[$i]["sid"],0,10,true,$listaattr);//retorna participantes en la encuesta(para conseguir los usuarios filtrados desde el inicui tiene que einplementar una consulta mysql.)
}
//(substr($usersEncuesta[$e][$j]['sent'],0,7) == substr(date("Y-m-d h:i"),0,7) && $usersEncuesta[$e][$j]['completed']=='N') || ($usersEncuesta[$e][$j]['completed'] != 'N')
$id=0;
$usersrecientesnc = array();


for ($e=0; $e < sizeof($usersEncuesta) ; $e++) { 
	for ($j=0; $j <sizeof($usersEncuesta[$e] ); $j++) { 
		if ((substr($usersEncuesta[$e][$j]['sent'],0,7) == substr(date("Y-m-d h:i"),0,7) && $usersEncuesta[$e][$j]['completed']=='N') || ($usersEncuesta[$e][$j]['completed'] != 'N')) {
			$usersrecientes[$e][$id]=$usersEncuesta[$e][$j];
			$id++;
		}
	}
	$id=0;

}//optengo lista de usuarios de los completados + los no completados resientes
$cont=array();//array cont para contar a los usuarios 
//cuento los usuarios por encuesta------------------------------
for ($j=0; $j < count($encuestaok); $j++) { 
	$cont[$j] = count($usersrecientes, COUNT_RECURSIVE)- count($usersrecientes);//sacamos numero de participantes en la encuesta 
}
//--------------------------------------------------------------
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
$valida= false;//condicion que indica que el mail no se repite
for ($i=0; $i <sizeof($usersEncuesta[$iPosicion]) ; $i++) { 
	if (strpos($usersEncuesta[$iPosicion][$i]['participant_info']['email'], trim($_POST['email']))) {
		$valida= true;//si se repite en cualquier momento 
	} 
}
if ($valida) {
	$myJSONRPCClient->release_session_key( $sessionKey );//cierra sesion
	$resultado = array('estado' => false, 'txt'=>"el email ya fue ingresado");//aqui se arma la url de destino
	echo json_encode($resultado);
}else{
	
	$sujeto = $myJSONRPCClient->add_participants($sessionKey, $encuestaok[$iPosicion]["sid"],$participante, false);
	$resultado = array('estado' => true, 'txt'=>"/limesurvey/index.php/".$encuestaok[$iPosicion]["sid"]."/lang/es/token/".$sujeto["user"]["token"]);//aqui se arma la url de destino
	$myJSONRPCClient->release_session_key( $sessionKey );//cierra sesion
	
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