<?php
//error handler function
function customError($errno, $errstr)
{ 
	$error = eregi("UNIQUE KEY 'IX_Recursos'", $errstr);
	//echo "<br><b>Error:</b> [$errno] $errstr";
	if ($error == 1)
	{
		?>
		<script type="text/javascript">
		alert("El recurso que ha intentado agregar ya existe");
		document.location = 'profesional_carga.php?accion=agregar';
		</script>
		<?php
	}
	
	$error = eregi("UNIQUE KEY 'IX_planes_prestadores'", $errstr);
	//echo "<br><b>Error:</b> [$errno] $errstr";
	if ($error == 1)
	{
		?>
		<script type="text/javascript">
		alert("La relacion plan prestador ya existe");
		document.location = 'prestador_carga.php?accion=agregar';
		</script>
		<?php
	}
}//set error handler
set_error_handler("customError");//dispara el error
?> 
