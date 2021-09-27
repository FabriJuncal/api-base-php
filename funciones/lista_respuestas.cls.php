<?php
class lista_respuestas_cls
{
	
	/*********************************************************************
    * Lista de los metodos POST
    *********************************************************************/
    var $codigo;
	var $mensaje;
	var $estado;
	
	function lista_respuestas_cls()
	{
		$this->codigo = '';
		$this->mensaje = '';
		$this->estado = '';	
	}
	
	
	function mensajes_de_respuesta()
	{
		// respuesta esto va a ir en un php para hacer include
			
		
		
		switch ($this->codigo) {
			case '200': //Solicitud aceptada;
				
				// peticiones OK
				$this->estado =  "OK";
				
		
				break;       

			case '201': 
			
				$this->mensaje = "Agregado  correctamente" ;
				$this->estado = "OK" ;
				break;
			case '204': //Solicitud aceptada pero sin datos de respuesta;
			
				$this->mensaje = array() ;
				$this->estado = "OK" ;
				break;

			case '400':
				$this->estado = "NOK" ;
				$this->mensaje = "Revisar los datos de envio" ;
				
				break;      

			case '404':
				$this->mensaje = "Peticion no encontrada en el servidor";
				$this->estado = "NOK";
				break;

			case '500':
				$this->mensaje = "Se ha producido un error interno en el servidor";
				$this->estado = "NOK";
				break;

			case '501':
				$this->mensaje = "";
				$this->estado = "NOK";
				break;
			default: //metodo NO soportado
				echo 'MENSAJE INEXISTENTE';
				break;
			}
			
			$respuesta = array("status"=>$this->estado,"data"=>$this->mensaje);  
			//$respuesta = array("data"=>$this->mensaje);  
			
			/*
			
			Código  Descripción
			200 OK	Solicitud aceptada; la respuesta contiene el resultado. Este es un código de respuesta general a cualquier solicitud. En las solicitudes GET, el recurso o datos solicitados están en el cuerpo de la respuesta. En las solicitudes PUT o DELETE, la solicitud fue satisfactoria y la información acerca del resultado (como los identificadores de recursos nuevo o los cambios en el estado del recurso) se puede encontrar en el cuerpo de la respuesta.
			201 CREATED	Las operaciones PUT o POST devuelven este código de respuesta e indica que se ha creado un recurso de forma satisfactoria. El cuerpo de la respuesta podría, por ejemplo, contener información acerca de un nuevo recurso o información de validación (por ejemplo, cuándo se actualiza un activo).
			204 NO CONTENT	Indica que se ha aceptado la solicitud, pero no había datos para devolver. Este respuesta se devuelve cuando se ha procesado la solicitud, pero no se ha devuelto ninguna información adicional acerca de los resultados.
			400 BAD REQUEST	La solicitud no fue válida. Este código se devuelve cuando el servidor ha intentado procesar la solicitud, pero algún aspecto de la solicitud no es válido; por ejemplo, un recurso formateado de forma incorrecta o un intento de despliegue de un proyecto de sucesos no válido en el tiempo de ejecución de sucesos. La información acerca de la solicitud se proporciona en el cuerpo de la respuesta e incluye un código de error y un mensaje de error.
			401 UNAUTHORIZED	El servidor de aplicaciones devuelve este código de respuesta cuando está habilitada la seguridad y faltaba la información de autorización en la solicitud.
			403 FORBIDDEN	Indica que el cliente ha intentado acceder a un recurso al que no tiene acceso. Podría producirse si el usuario que accede al recurso remoto no tiene privilegios suficientes; por ejemplo, con el rol WBERestApiUsers o WBERestApiPrivilegedUsers. Los usuarios que intenten acceder a proyectos de sucesos privados que son propiedad de otros podrían recibir también este error, pero solo si tienen el rol WBERestApiUsers en lugar de WBERestApiPrivilegedUsers.
			404 NOT FOUND	Indica que el recurso de destino no existe. Esto podría deberse a que el URI no está bien formado o a que se ha suprimido el recurso.
			405 METHOD NOT ALLOWED	Se produce cuando el recurso de destino no admite el método HTTP solicitado; por ejemplo, el recurso de funciones solo permite operaciones GET.
			406 NOT ACCEPTABLE	El recurso de destino no admite el formato de datos solicitado en la cabecera de Accept o el parámetro accept. Es decir, el cliente ha solicitado la devolución de los datos en un determinado formato, pero el servidor no puede devolver datos en ese formato.
			409 CONFLICT	Indica que se ha detectado un cambio conflictivo durante un intento de modificación de un recurso. El cuerpo de la respuesta contiene más información.
			415 UNSUPPORTED MEDIA TYPE	El recurso de destino no admite el formato de datos del cuerpo de la solicitud especificado en la cabecera de Content-Type.
			500 INTERNAL SERVER ERROR	Se ha producido un error interno en el servidor. Esto podría indicar un problema con la solicitud o un problema en el código del lado del servidor. Se puede encontrar información acerca del error en el cuerpo de respuesta.
			
			*/
			return $respuesta;
			
	
	}
		
	
}
?>