<?php
include_once "clases/datos/datos_mssql.cls.php";


class cls_comprobantes extends cls_datos_mssql
{


	var $idusuario;
	var $password;
	var $idempresa;
	
	
	
	//constructor
	function cls_comprobantes(){
		//parent::conectar();
	}
	
	//desconectar
	function comprobantes_desconectar(){
		//parent::desconectar();
	}


	 // log de los ingresos a la aplicacion 
	function obtener_todos()
	{
		// obetenemos el perfil que esta activo  
			
		$conn = parent::conectar("");
		
		$params = array(  
					array(&$this->idusuario),
					array(&$this->password), 	
					array(&$this->idempresa) ,
					array(&$this->fecha_desde) ,
					array(&$this->fecha_hasta),
					array(&$this->idcomprobante)  
					);
						
	
		$rs = parent::ejecutar("Comprobantes_ObtenerTodos_x_Empresa","SP","",$conn, $params);

		$array_return = array();
		
		if (sqlsrv_has_rows($rs)==0)
		{			
			return 0;
		}
		else
		{
			while($row = sqlsrv_fetch_array($rs, SQLSRV_FETCH_ASSOC))
			{
			  array_push($array_return, $row);
			}

			return $array_return;
		}	
	}  



	function control_cantidades()
	{
		// obetenemos el perfil que esta activo  
			
		$conn = parent::conectar("");
		
		$params = array(  
					array(&$this->idusuario),
					array(&$this->password), 	
					array(&$this->idempresa) 
					);
						
	
		$rs = parent::ejecutar("Comprobante_Control_Cantidades","SP","",$conn, $params);

		$array_return = array();
		
		if (sqlsrv_has_rows($rs)==0)
		{			
			return 0;
		}
		else
		{
			while($row = sqlsrv_fetch_array($rs, SQLSRV_FETCH_ASSOC))
			{
			  array_push($array_return, $row);
			}

			return $array_return;
		}	
	}  



	function reporte_ajustes()
	{
		// obetenemos el perfil que esta activo  
			
		$conn = parent::conectar("");
		
		$params = array(  
					array(&$this->idusuario),
					array(&$this->password), 	
					array(&$this->idempresa) , 	
					array(&$this->idcomprobante) 
					);
						
	
		$rs = parent::ejecutar("Rp_Resumen_Comprobante_Ajustes_Generales_Opt_liq_Web","SP","",$conn, $params);

		$array_return = array();
		
		if (sqlsrv_has_rows($rs)==0)
		{			
			return 0;
		}
		else
		{
			while($row = sqlsrv_fetch_array($rs, SQLSRV_FETCH_ASSOC))
			{
			  array_push($array_return, $row);
			}

			return $array_return;
		}	
	}  



	
}	

?>
