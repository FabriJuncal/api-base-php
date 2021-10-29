<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/


date_default_timezone_set('America/Argentina/Cordoba');


//metodos 
include_once "funciones/lista_respuestas.cls.php";



class API //extends cls_datos_mssql
{
    
    public function API(){

        header('Content-Type: application/JSON');    
		
		
        // Guardamos el tipo de verbo que recibimos 
        $verbo = $_SERVER['REQUEST_METHOD'];
		
		// Guardamos el tipo de accion 
		$method = $_GET['action'];

		
		
		
		//$accion = 'usuario-login';

		$cantidad_digitos_entidad = strpos($method, "-") ;
		$entidad = substr($method,0,$cantidad_digitos_entidad );
		$method = substr($method, $cantidad_digitos_entidad+1,strlen($method));
		$metodo_inexistente = 0;
	
		
		//LOG
		$log = 'log_API.txt';
		$texto_log = "ENTIDAD:".$entidad." ----- Funcionalidad: ".$method."\r\n";
		if($archivo = fopen($log, "a"))
		{
			fwrite($archivo, date("d m Y H:m:s"). " ". $texto_log);
			fclose($archivo);
		}
		
		
	
		
// ==================================================================================================================================================
		// AGREGAR LOS METODOS QUE EJECUTARÁ LA API CON EL VERBO HTTP CORRESPONDIENTE

		switch ($entidad){
			

			case 'api_post':
				if ($verbo == 'POST'){
					
					include_once "POST/post_metodos.cls.php";
					$objpres = new post_nombre_clase();

					if (method_exists($objpres,$method))
					{	
						call_user_func(array($objpres, $method));
					}
					else
					{	
						$metodo_inexistente = 1;
					}
				}else if ($verbo == 'GET'){

					include_once "GET/get_metodos.cls.php";
					$objpres = new get_nombre_clase();

					if (method_exists($objpres,$method))
					{	
						call_user_func(array($objpres, $method));
					}
					else
					{	
						$metodo_inexistente = 1;
					}

				}	
				
				break;


			
				
			default:
			
				break;
		}
// ==================================================================================================================================================		
		if($metodo_inexistente == 1)
		{
			//** Respondemos error 
			$objrespuesta = new lista_respuestas_cls();
			$objrespuesta->codigo = 404;
			$respuesta = $objrespuesta->mensajes_de_respuesta();
			echo json_encode($respuesta );	
		}
		
    }


  
}
//end class