<?php
include_once "clases/datos/datos_mssql.cls.php";


class cls_afiliados extends cls_datos_mssql
{


	var $idusuario;
	var $password;
	var $idempresa;
	
	
	
	//constructor
	function cls_afiliados(){
		//parent::conectar();
	}
	
	//desconectar
	function afiliados_desconectar(){
		//parent::desconectar();
	}


	 // log de los ingresos a la aplicacion 
	// function obtener_todos()
	// {
	// 	// obetenemos el perfil que esta activo  
			
	// 	$conn = parent::conectar("");
		
	// 	$params = array(  
	// 				array(&$this->idusuario),
	// 				array(&$this->password), 	
	// 				array(&$this->idempresa) 
	// 				);
						
	
	// 	$rs = parent::ejecutar("Proveedores_ObtenerTodos_x_Empresa","SP","",$conn, $params);

	// 	$array_return = array();
		
	// 	if (sqlsrv_has_rows($rs)==0)
	// 	{			
	// 		return 0;
	// 	}
	// 	else
	// 	{
	// 		while($row = sqlsrv_fetch_array($rs, SQLSRV_FETCH_ASSOC))
	// 		{
	// 		  array_push($array_return, $row);
	// 		}

	// 		return $array_return;
	// 	}	
	// }  



	
}	

?>
