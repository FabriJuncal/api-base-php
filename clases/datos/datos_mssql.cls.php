<?php
include_once  'conexion_mssql.cls.php';
//include 'funciones_errores.php';



class cls_datos_mssql extends cls_conexion_mssql
{
	var $parametros = array();
	var $cont = 0;
	var $cant_params = 0;
	var $stmt="";
	
	//constructor
	function cls_datos_mssql(){
		 $conn = parent::conectar("");
	}

	//FUNCIONES		
	function ejecutar($cadena='', $tipo='SQL',$motrar_parametros_php,$conn,$params){
		if ($tipo=='SQL')
		{
			$rs = sqlsrv_query($cadena);		
		}
		else
		{
			//formateo el nombre del SP para los parametros
			$cant_params = count($params,0);
			//echo "<br>Cant parametros: ".$cant_params;	
			
			$sp_a_ejecutar = $cadena;
			
			if ($params != '')
			{
				foreach ($params as $valor) 
				{
					if ($cont==0)
					{
						$cadena = "{call ".$cadena." ("	;
					}
					//si tiene parametros...
					if ($cant_params > 0)
					{
						//si es el primer parametro a enviar...
						if ($cont == 0)
						{
							$cadena .= "?";
						}
						else
						{
							$cadena .= ", ?";
						}
					}
					
					$cont++;
				}
				$cadena = $cadena.")}";
			}
			
			

			//echo "<br>".$cadena."<br>".$conn."<br>".var_dump($params);



			$stmt = sqlsrv_prepare($conn, $cadena, $params);
			//echo "<br>Statement: ".$stmt."<br>";

			if( $stmt === false )
			{
			     
			    die ($this->DisplayWarnings($motrar_parametros_php,$sp_a_ejecutar, $params, $cadena) );
			}

			if (sqlsrv_execute($stmt)===false)
			{
				die ($this->DisplayWarnings($motrar_parametros_php,$sp_a_ejecutar, $params, $cadena) );
			}
						
		}


		if ($stmt==FALSE)
		{
			return FALSE;
		}
		else
		{
			//limpio el array y cant para volver los parametros a 0 y si ejecuto otra consulta...que no quede basura
			$this->parametros = '';

			return $stmt;
		}
	}		
	

	function DisplayWarnings($motrar_parametros_php,$sp, $params, $cadena)  
	{  
	     $warnings = sqlsrv_errors();
	     $directory = "";

	     $nombre_log = rand();
	     
	     if(!is_null($warnings))  
	     {  
	          foreach( $warnings as $warning )  
	          {  
	               echo "
						<table align='center'>
						<tr align='center'>
							<td><font color='#CC6600' size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>Problemas al ejecutar. <br>Informar en Mesa de Ayuda el nro de error: ".$nombre_log."</font></td>
						</tr>
						<tr align='center'>
							<td><font color='#000000' size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>Comuniquese con los administradores del sistema.</font> </td>
						</tr>
						<tr align='center'>
							<td><font color='#000000' size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>Micam Soluciones - Cordoba - Argentina </font></td>
						</tr>
						<tr align='center'>
							<td><font color='#000000' size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>Tel: 0800-555-6422</font></td>
						</tr>
						
						</table>\n";

					if ($motrar_parametros_php != '')
					{
						echo var_dump($params);
					}

					


					 //GUARDO EN UN TXT PARA BUGFIXING
					 $error = utf8_encode($warning['message'])."\r\n\r\n"."Sql State: ".utf8_encode($warning['SQLSTATE'])."\r\n\r\n"."/////////////////////////////////////////////////////////////"."\r\n\r\nexec ".$sp."\r\n\r\n";

					 foreach($params as $fila_datos)
					 {
					 	$error = $error."\r\n".$fila_datos[0].",";

					 }
				     $directory = $_SESSION['url_root'].'logs/'.$nombre_log.'.txt';
				     //unlink($directory);
				     if ($fp = fopen ($directory,"a")) 
					 {
							fwrite($fp,"$error\r\n");
					 } 

					 if ($fp = fopen ($directory,"a")) 
					 {
						fwrite($fp,"/////////////////////////////////////////\r\n$cadena\r\n");
					 } 



					 fclose($fp);
	          }  
	     }  
	}  
	
	function reemplaza_caracteres_problematicos($cadena)
	{
		//$cadena =  str_replace("\'", " ", $cadena);
		//$cadena =  str_replace("\"", " ", $cadena);
		$cadena = stripslashes($cadena);
		return $cadena;
	}
	
	
	/*function vaciar_parametros()
	{
		$parametros = array();
		$cant = 0;	
	}
	*/
	
	

	
	function desconectar(){
		//parent::desconectar();
	}
	
	function separar_fecha_nac($fecha) {
		$fecha_separada = explode(" ",$fecha);
		
		switch ($fecha_separada[0]) {
			case "Jan":
				$mes = 1;
				break;
			case "Feb":
				$mes = 2;
				break;
			case "Mar":
				$mes = 3;
				break;
			case "Apr":
				$mes = 4;
				break;
			case "May":
				$mes = 5;
				break;
			case "Jun":
				$mes = 6;
				break;
			case "Jul":
				$mes = 7;
				break;
			case "Aug":
				$mes = 8;
				break;
			case "Sep":
				$mes = 9;
				break;
			case "Oct":
				$mes = 10;
				break;
			case "Nov":
				$mes = 11;
				break;
			case "Dec":
				$mes = 12;
				break;
		}
		$fecha_separada[0] = $mes;
		
		return $fecha_separada;
	}
	
}
?>
