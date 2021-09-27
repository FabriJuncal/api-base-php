<?php
class cls_funciones
{
	
	/*********************************************************************
    * Lista de los metodos POST
    *********************************************************************/
    var $array;
	

	function cls_funciones()
	{
		$this->array = '';
			
	}
	
	// Funcion que quita los caracteres especiales 
	// La utilizamos para enviarla antes que devolvamos el array de respuesta
	function utf8_converter()
	{
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

		
	
}
?>