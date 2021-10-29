<?php

include_once "funciones/get_funciones.cls.php";

class get_nombre_clase
{
	
	function get_nombre_metodo(){	  
		
        // ===============================================================================================
		// SE OBTIENE Y FORMATEA LOS DATOS OBTENIDOS DE LA PETICIÓN

		$objJson = json_decode( file_get_contents("php://input"), true ); 
		
		// Se define en variables los datos obtenidos de la petición
		$nombre =   $objJson['properties']['firstname']['value'];
		$apellido = $objJson['properties']['lastname']['value'];
		$telefono = $objJson['properties']['phone']['value'];

		// Almacenadmos en un array los datos obtenidos de la petición
		// Para agregarlo al log y enviar como parametro para realizar el Insert en la API Externa
		$contacto = array(
			'Fecha'    => date(DATE_RSS),
			'nombre'   => $nombre,
			'apellido' => $apellido,
			'telefono' => $telefono
		);
		
		// Guardamos los datos obtenidos de la petición
		$log = 'POST/logs/log_hs_get.txt';
		if($archivo = fopen($log, "a"))
	    {
			foreach ($contacto as $key => $value) {
				fwrite($archivo, $key.": ".$value."\r\n");
				
			}
			fwrite($archivo,"============================================================="."\r\n");
			fclose($archivo);
	    }
		
        // ===============================================================================================		
		// INGRESAR LOGICA QUE TENDRÁ EL METODO

		// Instanciamos las funciones a utilizar
		$objutf = new cls_funciones();

		$respuesta = NULL;


		











        // ===============================================================================================		
		// SE PREPARA LA RESPUESTA
		$objrespuesta = new lista_respuestas_cls();
		try {
			
			$objrespuesta->codigo = 200;
			$objrespuesta->mensaje = $respuesta;//$respuesta;
			$mje_rta = $objrespuesta->mensajes_de_respuesta();			
			$objutf->array = $mje_rta;
			
			echo json_encode( $objutf->utf8_converter() ); 

		} catch (\Exception $e) {
				//LOG DE ERROR
				if($archivo = fopen($log, "a"))
				{
					fwrite($archivo, $e."\r\n");
					fclose($archivo);
				}
				
				//** Respondemos error 
				$objrespuesta->codigo = 400;
				$mje_rta = $objrespuesta->mensajes_de_respuesta();
				
				$objutf->array = $mje_rta;
			
				echo json_encode( $objutf->utf8_converter() ); 
		}

	}	





}



?>