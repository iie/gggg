<!DOCTYPE html>
<?php
// Start the session
session_start();
session_destroy();
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/log.css">
		<title>PUC</title>
		
		<script src="js/jquery-2.2.4.min.js"></script>
		<script src="js/jquerrut.js"></script>
	</head>
	<body>
		<div class="error">
			<span>No se a encontrado el rut ingresado desea participar REGISTRESE</span>
		</div>
		<div class="main">
			<h1>bienvenido</h1>
			<form action="" id="formlg">
				<input type="text" id="lorut" name="rut" placeholder="ejemplo: 111111111" required autofocus>
				<input type="submit" class="botonlg" value="iniciar sesión">
			</form>
			<br>
			<a href="http://localhost/limesurvey/index.php/256738?lang=es"> Registrese</a>
		</div>
		<script type="text/javascript">
		$("#lorut").rut();
		$("#formlg").submit(function( event ) {
			event.preventDefault();
			var da= $("#lorut").val();
			var tmpstr = "";
			for ( var i=0; i < da.length ; i++ ){
				if ( da.charAt(i) != ' ' && da.charAt(i) != '.' && da.charAt(i) != '-' ){
					tmpstr = tmpstr + da.charAt(i);
				}
			}
		
			var texto = tmpstr;
			var de = $.validateRut(texto);
		
			if(de === false){
				alert("El rut ingresado es invalido");
			}else{
				$.ajax({
					url:'login.php',
					type:'POST',
					data: $(this).serialize(),
					success:function(data){
						//console.log(JSON.parse(data).attribute_1);
						//console.log(JSON.parse(data).texto);
						if (data['estado']== false ) {
							localStorage.setItem("puc_rut", $("#lorut").val());
							//console.log(JSON.parse(data).texto);
							location.href=(JSON.parse(data).texto);
						}else{
							//console.log(JSON.parse(data).texto);
							location.href= (JSON.parse(data).texto);
						}
						
					},
					error:function(a,b,c){
						console.log(a,b,c);
					}
				});
			}
		
		});
		</script>
	</body>
</html>