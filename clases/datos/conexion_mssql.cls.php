<?php
class cls_conexion_mssql
{
	var $conexion;
	var $url_sitio;
	var $url_root;			

	function conectar($BD)
	{
		if ($BD == 'Micam' || $BD == '' || $BD == 'Afiliaciones')
		{
			include 'datos_conexion_mssql.php'; 
			if ($BD == 'Afiliaciones')
			{
				$base = $base_afiliaciones;
			}
			else
			{
				$BD = '';
			}
		}
		if ($BD == 'Ospat')
		{
			include 'datos_conexion_ospat_mssql.php'; 
		}
			
		//traigo las url de datos_conexion;
		//y se la asigno a las variables publicas que serán utulizadas
		//desde la clase datos y los objetos que hereden de conexion
		$this->url_sitio = $urlsitio;
		$this->url_root = $urlroot;		
				
		////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////		

		//$this->conexion = mssql_connect($host,$user, $pass) or die ("No se pudo conectar con SQLSERVER en el host: $host");
		$serverName = $host; 

		$connectionInfo = array( "Database"=>$base, "UID"=>$user, "PWD"=>$pass);
	
		$this->conn = sqlsrv_connect($serverName, $connectionInfo);

		return $this->conn;

		/*if ($BD == '')
		{
			mssql_select_db($base) or die ("No se pudo abrir la base $base");
		}
		else if ($BD == 'Afiliaciones')
		{
			
			mssql_select_db($base_afiliaciones) or die ("No se pudo abrir la base $base");
		}
		
		if ($this->conexion->errno!=0)
			{
			asignar_valor_parametro_salida("error_sql", "Problemas al establecer la Conexion con el servidor");
			return FALSE;
			}
		else
			{return TRUE;
			}*/
		}	

	function desconectar($conn)
	{
		//sqlsrv_free_stmt($stmt);
		sqlsrv_close($conn);
	}	
	


}
?>
