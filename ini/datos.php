

<!DOCTYPE html>

<html lang="en">
 	<head>
 		<meta charset="utf-8">
 		<meta http-equiv="X-UA-Compatible" content="IE=edge">
 		<meta name="viewport" content="width=device-width, initial-scale=1">
 		<title>PUC</title>
<script src="js/jquery-2.2.4.min.js"></script>

<script type="text/javascript">


  $(document).ready(function(){

  	$("#condicion1").click(function(){
  		  	if ($("#condicion1").is(":checked")) {
  		$("#formulario").removeAttr("style");
  		$("#salir").css("display", "none");


  	}
	});
  	$("#condicion2").click(function(){
  		  	if ($("#condicion2").is(":checked")) {
  		$("#salir").removeAttr("style");
  		$("#formulario").css("display", "none");
  	}


  	});
    var token = localStorage.getItem("puc_rut")
    
    $("#ru").val(token);


  
});



  </script>
  <link rel="stylesheet" type="text/css" href="/encuesta/tmp/assets/99f0d59a/css/jquery-ui-custom.css">
  <link rel="stylesheet" type="text/css" href="/encuesta/tmp/assets/99f0d59a/css/bootstrap-slider.css">
  <link rel="stylesheet" type="text/css" href="/encuesta/tmp/assets/99f0d59a/css/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css">
  <link rel="stylesheet" type="text/css" href="/encuesta/tmp/assets/99f0d59a/css/flat_and_modern.css">
  <link rel="stylesheet" type="text/css" href="/encuesta/tmp/assets/99f0d59a/css/template.css">
  <link rel="stylesheet" type="text/css" href="/encuesta/tmp/assets/e4ebeb6a/css/font-awesome.min.css">

 	</head>
 	<body>
 	<div class="main">
 		<h1>registro</h1>
 		<div id="consulta">
 		<form action="" id="form" method="post" >
 			<label>Imparte clases en 1-6 ?</label>
 			<input id="condicion1" type="radio" name="curso" value="si">Si
  			<input id="condicion2" type="radio" name="curso" value="no">No<br>
  			<div id="formulario" style="display:none">
  			<label>email:</label>
 			<input type="email" id="ema" name="email" placeholder="d@s.cl" required ><br>
 			<label>nombre:</label>
 			<input type="text" id="nom" name="nombre" placeholder="Juan" required ><br>
 			<label>apellido:</label>
 			<input type="text" id="apr" name="apellido" placeholder="perez" required ><br>
 			<label>rut:</label>
      <input type="text" id="rut" name="rut" placeholder="11111111-1" required ><br>
      <label>dependencia:</label>
      <select type="text" id="est" name="dependencia" placeholder="municipal" required >
        <option value="municipal">municipal</option>
        <option value="particular">particular</option>
        <option value="par.subencionado">par.subencionado</option>
      </select><br>
      <label>reguion:</label>
      <select type="text" id="reg" name="reguion" placeholder="metropolitana" required>
        <option value="Arica y Parinacota XV">Arica y Parinacota XV</option>
        <option value="Tarapacá I">Tarapacá I</option>
        <option value="Antofagasta II">Antofagasta II</option>
        <option value="Atacama III">Atacama III</option>
        <option value="Coquimbo IV">Coquimbo IV</option>
        <option value="Valparaiso V">Valparaiso V</option>
        <option value="Metropolitana de Santiago RM">Metropolitana de Santiago RM</option>
        <option value="Libertador General Bernardo O\'Higgins VI">Libertador General Bernardo O'Higgins VI</option>
        <option value="Maule VII">Maule VII</option>
        <option value="Biobío VIII">Biobío VIII</option>
        <option value="La Araucanía IX">La Araucanía IX</option>
        <option value="Los Ríos XIV">Los Ríos XIV</option>
        <option value="Los Lagos X">Los Lagos X</option>
        <option value="Aisén del General Carlos Ibáñez del Campo XI">Aisén del General Carlos Ibáñez del Campo XI</option>
        <option value="Magallanes y de la Antártica Chilena XII">Magallanes y de la Antártica Chilena XII</option>
      </select><br>
 			<input type="submit" class="botonlg" value="registrar">		
 			</div>
 			<div id="salir" style="display:none">    
 				<button type="button">salir</button>		
 			</div>	
		</form>
		</div>
		<br>
		
 	</div>
<script type="text/javascript">

  		$("#form").submit(function( event ) {
  			event.preventDefault();
      		$.ajax({
			     url:'carga.php',
			     type:'POST',
			     data: $(this).serialize(),
			     success:function(data){
				if ((JSON.parse(data).estado == true)) {

						window.location.href = (JSON.parse(data).txt);
				}else{
					
					alert((JSON.parse(data).txt));
				}

			},
			error:function(a,b,c){
				console.log(a,b,c);
			}





		});


  });


  </script>



 </body>
</html>

