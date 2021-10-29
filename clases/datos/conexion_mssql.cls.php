<?php
class cls_conexion_mssql
{
	var $conexion;
	var $url_sitio;
	var $url_root;			

	function conectar(){

		include 'datos_conexion_mssql.php'; 

			
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

		}	

	function desconectar($conn)
	{
		//sqlsrv_free_stmt($stmt);
		sqlsrv_close($conn);
	}	
	


}
?>
