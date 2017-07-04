<?php

function rut_formato_token( $rut ){
	if ($rut[2]=='.' && $rut[6]=='.' && $rut[10]=='-') {
		return substr($rut,0,2).''.substr($rut,3,3).''.substr($rut,7,3);
	}else{
		return substr($rut,0,8);
	}
}
function rut_formato_atributo1( $rut ){
	if ($rut[2]=='.' && $rut[6]=='.' && $rut[10]=='-') {
		return $rut;
	}else if($rut[8]=='-'){
		return substr($rut,0,2).'.'.substr($rut,2,3).'.'.substr($rut,5,3).substr($rut,8,2)
	}else{
		return substr($rut,0,2).'.'.substr($rut,2,3).'.'.substr($rut,5,3).'-'.substr($rut,8,1);

	}
}
$sujeto = rut_formato_atributo1( "18392303k" );
echo "<pre>";
print_r($sujeto, null );
echo "</pre>";

?>