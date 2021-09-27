<?php
session_start();

function strToSQLDate($str){
	if (ereg ("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $str, $regs)) {
	   return "'$regs[3]-$regs[2]-$regs[1]'";
	} else {
		return 'NULL';
	}
}


//////////////////////////////////////////////////// 
//Convierte fecha de mysql a normal 
////////////////////////	//////////////////////////// 
function cambia_fecha_a_normal($fecha){ 
    ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha); 
    $lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1]; 

	if ($lafecha=='//'){
		return '';}
	else{	
	    return $lafecha; 
	}
} 

//////////////////////////////////////////////////// 
//Convierte fecha de normal a mysql 
//////////////////////////////////////////////////// 
function cambia_fecha_a_mysql($fecha){ 
    ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
    return $lafecha; 
} 
?>