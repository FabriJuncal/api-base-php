<?php

include_once "funciones/funciones.cls.php";

class presence_metodos
{
	
	function insertar_call()
	{
		$objJson = json_decode( file_get_contents("php://input") ); 
		
		$a = $objJson->vid;
		
		$log = 'POST/logs/log_hs_presence.txt';
		if($archivo = fopen($log, "a"))
	    {
	        fwrite($archivo, $a."\r\n");
	        fclose($archivo);
	    }
		
		
		
		$mi_respuesta='Hola: '.$objJson->idusuario;
		
		$objrespuesta = new lista_respuestas_cls();
		$objutf = new cls_funciones(); 
		
		//cambiar por try catch
		//if ($a=='Fabricio')
		//{
			
			$objrespuesta->codigo = 200;
			$objrespuesta->mensaje = $mi_respuesta;
			$mje_rta = $objrespuesta->mensajes_de_respuesta();
			 
			
			//$objutf->array = $mje_rta;
			$objutf->array = $a;
			
			echo json_encode( $objutf->utf8_converter() ); 
		/*
		}
		else 
			{
				
				//LOG DE ERROR
				if($archivo = fopen($log, "a"))
				{
					fwrite($archivo, "Error Fabri"."\r\n");
					fclose($archivo);
				}
				
				//** Respondemos error 
				$objrespuesta->codigo = 400;
				$mje_rta = $objrespuesta->mensajes_de_respuesta();
				
				$objutf->array = $mje_rta;
			
				echo json_encode( $objutf->utf8_converter() ); 
			}
		*/


	}	





}



?>