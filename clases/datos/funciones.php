<?php
session_start();
session_cache_expire(1);
date_default_timezone_set('UTC');

function formatoFechaGISS($fechavieja)
{
    $dia = substr($fechavieja,4,2);
	$mes = substr($fechavieja,0,3);
	$anio = substr($fechavieja,6,5);
	
	$dia = trim($dia);
	if ($dia != '')
		$dia = str_pad($dia,2,"0",STR_PAD_LEFT);
	$anio = trim($anio);
	
	  if ($mes=="Jan"){$mes="01";}
	  if ($mes=="Feb"){$mes="02";}
	  if ($mes=="Mar"){$mes="03";}
	  if ($mes=="Apr"){$mes="04";}
	  if ($mes=="May"){$mes="05";}
	  if ($mes=="Jun"){$mes="06";}
	  if ($mes=="Jul"){$mes="07";}
	  if ($mes=="Aug"){$mes="08";}
	  if ($mes=="Sep"){$mes="09";}
	  if ($mes=="Oct"){$mes="10";}
	  if ($mes=="Nov"){$mes="11";}
	  if ($mes=="Dec"){$mes="12";}
	  
	  $fecha  = $dia."-".$mes."-".$anio;
	  
	  return $fecha;
};

function dia_castellano($dia)
{

	
	  if ($dia=="Monday"){$dia="Lunes";}
	  if ($dia=="Tuesday"){$dia="Martes";}
	  if ($dia=="Wednesday"){$dia="Miercoles";}
	  if ($dia=="Thursday"){$dia="Jueves";}
	  if ($dia=="Friday"){$dia="Viernes";}
	  if ($dia=="Saturday"){$dia="Sabado";}
	  if ($dia=="Sunday"){$dia="Domingo";}

	  
	  return $dia;
};



function login(){
//-------------------------------------------------------------------------------------------
//OBTIENE ULTIMA PAGINA. PARA CUANDO HAY ERROR POR INTEGRIDAD REFERENCIAL...HACE REFERENCIA A LA PAGINA QUE OBTENEMOS AQUI!
$encontrar = '/';
$micadena = $_SERVER['PHP_SELF'];
$posicion = strrpos($micadena, $encontrar);
if ($posicion !== false) 
{
	$encontrar2 = '_';
	$micadena2 = substr($micadena,$posicion + 1);
	$posicion2 = strrpos($micadena2, $encontrar2);
	if ($posicion !== false) 
	{
		$_SESSION['ultima_pagina'] = substr($micadena2,0, $posicion2);
		$puntophp = '.php';
		$_SESSION['ultima_pagina']  = $_SESSION['ultima_pagina'].$puntophp;
	}
	else
	{
		$_SESSION['ultima_pagina'] = $micadena2;
	}
}
//VALIDO QUE ESTE LOGUEADO
	if (!in_array('autentificado',  array_keys($_SESSION))){
		header ("Location: http:login.php\n");
		exit("<a href=\"login.php\">Login</a>");
		return false;
	};
	return true;
	
}

function redirect($location){
?>
<script language="JavaScript">
document.location = "<?php echo($location);?>"
</script>
<?php
}


function validar_fecha($fecha){
	$dia=substr($fecha,0,2);
	$mes=substr($fecha,3,2);
	$ano=substr($fecha,6,4);		
	
	if (strtotime($fecha)!=-1) {
		if (checkdate ($mes, $dia, $ano))
			{return 1;}
		else
			{return 0;}
	}
	else{
		return 0;	
	}
}


function obtener_nombre_mes($mes_n){
	//$mes_n=Date("m");
	if ($mes_n=='01'){$mes='Enero';}
	if ($mes_n=='02'){$mes='Febrero';}
	if ($mes_n=='03'){$mes='Marzo';}
	if ($mes_n=='04'){$mes='Abril';}
	if ($mes_n=='05'){$mes='Mayo';}
	if ($mes_n=='06'){$mes='Junio';}
	if ($mes_n=='07'){$mes='Julio';}
	if ($mes_n=='08'){$mes='Agosto';}
	if ($mes_n=='09'){$mes='Septiembre';}
	if ($mes_n=='10'){$mes='Octubre';}
	if ($mes_n=='11'){$mes='Noviembre';}
	if ($mes_n=='12'){$mes='Diciembre';}
    return $mes; 
}

function obtener_ano_actual(){
	$ano=Date("Y");
    return $ano; 
}

function Obtener_carpeta_imagen()
{ 
	$carpeta_custom = $_SESSION['CARPETA_CUSTOM'];
		
	switch ($carpeta_custom) 
	{
		case "_ecipsacenter":
			$carpeta_imagen = '_ecipsacenter\imagenes'; 
	        break;
	   	case "_vallesvillella":
	        $carpeta_imagen = '_vallesvillella\imagenes';
       		break;
	       case "_casamagna":
    	    $carpeta_imagen = '_casamagna\imagenes';
	        break;
		case "_tierraalta":
    	    $carpeta_imagen= '_tierraalta\imagenes';
	        break;
		case "_cappa":
    	    $carpeta_imagen = '_cappa\imagenes';
	        break;
		case "_micam":
    	    $carpeta_imagen ='_micam\imagenes';
	        break;
		case "_local":
    	    $carpeta_imagen = "C:\AppServ\www\swi\_local\imagenes";
	        break;
		default:
			echo("");				
	        break;
	}
	return $carpeta_imagen;
}

function suma_fechas($fecha,$ndias)
{     
 
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
            
 
              list($dia,$mes,$ano)=split("/", $fecha);
            
 
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
            
 
              list($dia,$mes,$ano)=split("-",$fecha);
        $nueva = mktime(0,0,0, $mes,$dia,$ano) + $ndias * 24 * 60 * 60;
        $nuevafecha=date("d-m-Y",$nueva);
            
 
      return ($nuevafecha);  
            
 
}

 
function quitar_caracteres_especiales($string)
{
    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "-", "~",
             "#", "@", "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "`", "]",
             "+", "}", "{", "¨", "´",
             ">", "<", ";", ",", ":",
             "."),
        '',
        $string
    );

    return $string;
}

?>