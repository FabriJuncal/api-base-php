<?php
class cls_funciones
{
	
	/*********************************************************************
    * Lista de los metodos GET
    *********************************************************************/
    var $array;
	

	function cls_funciones(){
		$this->array = '';		
	}
	
	// Funcion que quita los caracteres especiales 
	// La utilizamos para enviarla antes que devolvamos el array de respuesta
	function utf8_converter(){
	    array_walk_recursive($this->array, function(&$item, $key){
	        if(!mb_detect_encoding($item, 'utf-8', true)){
	                $item = utf8_encode($item);
	        }
	    });
	 
	    return $this->array;
	}
	
	function distanciaGeodesica($lat1, $long1, $lat2, $long2){ 

		$degtorad = 0.01745329; 
		$radtodeg = 57.29577951; 

		$dlong = ($long1 - $long2); 
		$dvalue = (sin($lat1 * $degtorad) * sin($lat2 * $degtorad)) 
		+ (cos($lat1 * $degtorad) * cos($lat2 * $degtorad) 
		* cos($dlong * $degtorad)); 

		$dd = acos($dvalue) * $radtodeg; 

		$miles = ($dd * 69.16); 
		$km = ($dd * 111.302); 
		//$km = $km/1000;

		return $km; 
	}

	//funcion para ordenar los arrays
	function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
		    $sort_col = array();
		    foreach ($arr as $key=> $row) {
		        $sort_col[$key] = $row[$col];
		    }

		    array_multisort($sort_col, $dir, $arr);
	}

	// Petición PHP - cURL
	// Parametros - Tipos Datos:
	// $URL:        String
	// $tipo_envio: string
	// $body:       array
	// $header:     json
	function peticion_HTTP($URL, $tipo_envio, $body = '', $header = ''){

		if(!function_exists('curl_init')) {
			die('cURL no disponible!');
		}
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $URL);
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		// Quita la validación del Certificado SSL
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

		switch ($tipo_envio) {
			case 'post':
				# Se formatea los datos para el envio
				//$postString = http_build_query($datos_autenticacion, '', '&');
				$postString = ($body != '' ? json_encode($body) : '');

				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $postString);
				break;
			
			default:

				break;
		}


		
		$respuesta = curl_exec($curl);
		if ($respuesta === FALSE) {
			return 'Ah ocurrido un error: ' . curl_error($curl) . PHP_EOL;
		}
		else {
			return json_decode($respuesta, true);
		}
	}
	
	function autenticacion_api_externa(){
		// Obtenemos la Autenticación en la API Externa
		include_once "clases/datos/datos_autenticacion_api_externa.php";
		$respuesta = $this->peticion_HTTP(
										  $url_autenticacion,
										  'post',
										  $datos_autenticacion, 
									      ''
										);

		return $respuesta;
	}

	function insert_api_externa($token, $contacto){

		// Obtenemos las rutas para realizar el Insert con la API Externa
		include_once "clases/datos/datos_insert_api_externa.php";

		$header = array(
			"Authorization" => $token,
			"Url" => $url_insert
		);

		$datos_insert =        '{
			\n  "SourceId": 0,
			\n  "Name": "'.$contacto['nombre'].' '.$contacto['apellido'].'",
			\n  "PhoneNumber": "'.$contacto['telefono'].'",
			\n  "PhoneDescription": 0,
			\n  "PhoneTimeZoneId": "",
			\n  "PhoneNumber2": "",
			\n  "PhoneDescription2": 0,
			\n  "PhoneTimeZoneId2": "",
			\n  "PhoneNumber3": "",
			\n  "PhoneDescription3": 0,
			\n  "PhoneTimeZoneId3": "",
			\n  "PhoneNumber4": "",
			\n  "PhoneDescription4": 0,
			\n  "PhoneTimeZoneId4": "",
			\n  "PhoneNumber5": "",
			\n  "PhoneDescription5": 0,
			\n  "PhoneTimeZoneId5": "",
			\n  "PhoneNumber6": "",
			\n  "PhoneDescription6": 0,
			\n  "PhoneTimeZoneId6": "",
			\n  "PhoneNumber7": "",
			\n  "PhoneDescription7": 0,
			\n  "PhoneTimeZoneId7": "",
			\n  "PhoneNumber8": "",
			\n  "PhoneDescription8": 0,
			\n  "PhoneTimeZoneId8": "",
			\n  "PhoneNumber9": "",
			\n  "PhoneDescription9": 0,
			\n  "PhoneTimeZoneId9": "",
			\n  "PhoneNumber10": "",
			\n  "PhoneDescription10": 0,
			\n  "PhoneTimeZoneId10": "",
			\n  "Priority": 100,
			\n  "Comments": "",
			\n  "Scheduled": false,
			\n  "ScheduleDate": "",
			\n  "ScheduleTime": "",
			\n  "CapturingAgent": 0,
			\n  "CustomData1": "",
			\n  "CustomData2": "",
			\n  "CustomData3": "",
			\n  "CallerId": "",
			\n  "CallerName": "",
			\n  "CustomerId": ""
			\n}
			\n';


			$respuesta = $this->peticion_HTTP(
				$url_admin,
				'post',
				$datos_insert, 
				$header
			  );

			
			return $respuesta;
	
	
		
	}
	
}
?>