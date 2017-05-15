<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
//$c_dato = file_get_contents("cursos.json");
//$e_dato = file_get_contents("establecimiento.json");
//$ci_dato= file_get_contents("ciclo.json");
$co_dato= file_get_contents("region.json");
//$c_arr= json_decode($c_dato);
//$e_arr= json_decode($e_dato);
//$ci_arr=json_decode($ci_dato);
$re_arr=json_decode($co_dato);
$datos = new stdClass();

//$datos->c=$c_arr;
//$datos->e=$e_arr;
//$datos->ci=$ci_arr;
$datos->re=$re_arr;
echo json_encode($datos);
exit();


//$e_dato = file_get_contents("establecimiento.json");
//echo json_encode($e_dato);
		
//$ar = array('apple', 'orange', 'banana', 'strawberry');
//echo json_encode($ar); // ["apple","orange","banana","strawberry"]


?>