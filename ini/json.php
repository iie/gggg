<?php
require "json-file-decode.class.php";

$comunas = array();
$regiones = array();
$arrayunion = array();
$read = new json_file_decode();
$comunas = $read->json("../upload/templates/PUC_default/files/comunas.json");
$regiones = $read->json("../upload/templates/PUC_default/files/regiones.json");
$establecimientos = $read->json("../upload/templates/PUC_default/files/establecimiento.json");
$arrayunion = array('comunas' => $comunas,'regiones'=>$regiones,'establecimientos'=>$establecimientos);
echo json_encode($arrayunion);

?>