

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
 			<input type="text" id="ru" name="rut" placeholder="11111111-1" required ><br>
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

