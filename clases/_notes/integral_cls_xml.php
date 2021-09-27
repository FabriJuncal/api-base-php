<?php

include_once $_SESSION['url_root'] ."clases/datos/funciones.cls.php";

class cls_xml 
{
	var $urlXML;
	var $cadena;
	var $valor;
	var $nombre;
	var $apellido;
	var $idlocalidad;
	var $iddocumento_tipo;
	var $nrodoc;
	var $numero_adherente;
	var $numero_afiliado;
	var $cuil;
	var $fecha_nacimiento;
	var $sexo;
	var $id_estado_civil;
	var $idusuario_registro;
	var $idplan_mutual;
	var $idnacionalidad;
	var $estado_peticion;
	var $mensaje_error_conexion;
	var $numeroafil_nobis = 0;
	var $numero_afiliado_nobis =0;
	var $peringreso;
	var $idgrupo_integrante_tipo;
	var $mensaje_peticion;
	var $nombre_plan;
	var $error;
	
	function cls_xml()
	{
		$this->mensaje_error_conexion = "No se pudo conectar al servicio de Integral. <br>Presione F5 e intente nuevamente o bien COMUNICARSE CON MICAM AL 0800.";
		
		//para que sepa solo donde conectarse

		if (substr($_SERVER['PHP_SELF'],0,13) == '/autorizador/')
		{
			$this->url_gilsa = "http://190.2.50.89:8000/peticionPruebas.asp?";	
			
		}
		else
		{
			$this->url_gilsa = "http://190.2.50.89:8000/peticion.asp?";
		}
		
		//$this->url_gilsa = "http://gilsacba.sytes.net:8000";
	}
	
	//constructor
	function cls_xml_conector($ruta)
	{
			
			$this->urlXML = $ruta;
			
	}	
	
	
	//elegibilidad por nom y ape
	function Elegibilidad_Afiliado_Nombre_Apellido($nombre)
	{
		//Separo las palabras en un array
		$nombre= split('[ ]',$nombre);
		
		$this->urlXML = $this->url_gilsa.'peticion=202&palabra1="'.$nombre[0].'"&palabra2="'.$nombre[1].'"&palabra3="'.$nombre[2].'"';

		//echo $this->urlXML;
		
		$xml = simplexml_load_file(urlencode($this->urlXML));
		
		
		if (!is_object($xml))
		{
			
			$resultado.="<tr class='servicesT'>
					<td align='left' colspan='5' >
						<div align='center' class='colorrojobold'>".$this->mensaje_error_conexion."</div>
					</td>
					</tr>";	
		
		}
		else
		{
			$resultado = $xml;
		}
	
		return $resultado;
	}//fin elegibilidad por nom y ape





	// FUNCIONES -----------------------------------------------------------------------------
	function Elegibilidad_Afiliado_Documento_Credencial($txt_dni,$txt_beneficiario){		
	
		// ARMO LA PETICION  ---

		if ($txt_dni != "" )
		{	
			//insertamos el dni en la peticion 
			$this->urlXML = $this->url_gilsa.'peticion=201&nroDoc='.trim($txt_dni).'&nrocredencial=&prestadorMedico=00';
			
			//echo $this->urlXML;
			$peticion_por_dni = true;
		}
		else if($txt_beneficiario != "" )
		{ 
		
			// Formateamos el numero de afiliado como integral
			/*$numero_adherente = '-'.substr($txt_beneficiario, -2);
			$numero_afiliado = substr($txt_beneficiario, 0, strlen($txt_beneficiario)-2 ).$numero_adherente;
			$numero_afiliado = number_format($numero_afiliado , 0, ",",".").$numero_adherente;
			*/	
			
			$this->urlXML= $this->url_gilsa.'peticion=201&nroDoc=00&nrocredencial='.trim($txt_beneficiario).'&prestadorMedico=00'; 
			//echo $this->urlXML;
			$peticion_por_dni = false;
			
		}		
		
		//guardo una copia del TXT en la carpeta integral
		$directory = $_SESSION['url_root'].'integral/elegibilidad_documento_micam/'.$txt_dni.'-'.$txt_beneficiario.'.txt';
		unlink($directory);
		if ($fp = fopen ($directory,"a")) 
		{
				fwrite($fp,"$this->urlXML\r\n");
		}
		fclose($fp);
		
		// EJECUTO LA PETICION ---
		$xml = simplexml_load_file($this->urlXML);
		$this->cadena = '';
		
		
		if (!is_object($xml))
		{
			$this->estado_peticion.="<div align='center' class='colorrojobold'><br>".$this->mensaje_error_conexion."</div>";
			$this->error = '1';		
		
			//$this->cadena=' *  El servicio de peticiones no esta funcionando correctamente ';
			
		}
		else
		{	
			$this->error = '0';
			$cont = 0;
			//***** ojo nueva parte controlar **	
			foreach ($xml->_parametro2 as $item)
			{
				if ($cont == 0)
				{
					$this->numeroafil_nobis = $item->numeroafil;
					$this->numero_familiar_nobis =$item->nrofamiliar;	
				}
				else
				{
					break;
				}
				$cont++;
				
			}
			//**** 
			
			return $xml; // retornamos el XML
		
			
		}//cierre else validacion xml

		
		
	}//cierre funcion
	
	
	
	
	
	function Agregar_Solicitud($cantidad_items, $is_internacion)
	{

		$url = '';
		$url = $this->url_gilsa."peticion=203";
		$url .= "&idsolicitudexterno=".$this->idsolicitudexterno;//es el nro micam (externo para ellos)
		$url .= "&tipoautorizacion=%27".urlencode($this->tipoautorizacion)."%27";
		$url .= "&fechaprescripcion=%27".urlencode($this->fechaprescripcion)."%27";
		$url .= "&fechapresentacion=%27".urlencode($this->fechapresentacion)."%27";
		$url .= "&numeroafil=".$this->numeroafil;
		$url .= "&nrofamiliar=".$this->nrofamiliar;
		$url .= "&prestadormedico=".$this->prestadormedico;
		$url .= "&juridiccion=".$this->juridiccion;
		$url .= "&tipomatricula=".$this->tipomatricula;
		$url .= "&nromatricula=".$this->nromatricula;
		$url .= "&nombremedico=%27".urlencode($this->nombremedico)."%27";
		$url .= "&diagnostico=%27".urlencode(rtrim($this->diagnostico))."%27";
		$url .= "&tipointernacion=%27".$this->tipointernacion."%27";
		$url .= "&fechaingreso=%27".$this->fechaingreso."%27";
		$url .= "&fechaegreso=%27".$this->fechaegreso."%27";
		$url .= "&nuevainternacion=%27".$this->nuevainternacion."%27";
		$url .= "&idinternacion=".$this->idinternacion;
		$url .= "&modointernacion=%27".$this->modointernacion."%27";
		$url .= "&condicion=%27".$this->condicion."%27";
		for ($i = 1; $i <= $cantidad_items; $i++) 
		{
			$url .= $this->{"item".$i};
		}
		
		
		
		//guardo una copia del TXT en la carpeta integral
		if ($is_internacion != 1)
		{
			$directory = $_SESSION['url_root'].'integral/ambulatorio/registro_amb_micam/'.$this->idsolicitudexterno.'-peticion203-solic-Micam'.'.txt';
		}
		else
		{
			$directory = $_SESSION['url_root'].'integral/internado/registro_int_micam/'.$this->idsolicitudexterno.'-peticion203-solic-Micam'.'.txt';
		}
		unlink($directory);
		if ($fp = fopen ($directory,"a")) 
		{
				fwrite($fp,"$url\r\n");
		}
		fclose($fp);
		
	
		//ejecuto la peticion y guardo resultado en un TXT
		$xml = simplexml_load_file($url);		
		
		//GENERO TXT DE DEVOLUCION
		if ($is_internacion != 1)
		{
			$directory = $_SESSION['url_root'].'integral/ambulatorio/respuesta_amb_integral/'.$this->idsolicitudexterno.'-peticion203_solic-nroIntegral-'.$item->idsolicitud.'.txt';
		}
		else
		{
			$directory = $_SESSION['url_root'].'integral/internado/respuesta_int_integral/'.$this->idsolicitudexterno.'-peticion203_solic-nroIntegral-'.$item->idsolicitud.'.txt';
		}
		unlink($directory);
		
		foreach ($xml->_parametro2 as $item)
		{	
			if ($fp = fopen ($directory,"a")) 
			{
				fwrite($fp,"$devolucion\r\n");

				$devolucion =  'idsolicitud_integral: '.$item->idsolicitud;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = 'idinternacion_integral: '.$item->idinternacion;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = 'estadoitem_integral: '.$item->estadoitem;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = 'iditemautorizacion_integral: '.$item->iditemautorizacion;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = 'cantidadautorizada: '.$item->cantidadautorizada;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = 'observacion: '.$item->observacion;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = 'fechaestado: '.$item->fechaestado;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = 'controlautomatizado: '.$item->controlautomatizado;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = 'iditem_micam: '.$item->iditemexterno;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = 'codnomenclador: '.$item->codnomenclador;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = 'descripnomenclador: '.$item->descripnomenclador;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = 'statuspeticion: '.$item->statuspeticion;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = 'condicion: '.$item->condicion;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = 'montocoseguro: '.$item->montocoseguro;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = '\r\n';
				fwrite($fp,"$devolucion\r\n");
				
				$idsolicitud_integral = $item->idsolicitud;
			
			}
			fclose($fp);
			
		}
			
		//guardo una copia del TXT en la carpeta integral_peticiones_respondidas (guardo primero nuestro nro solic
		
		return $xml;
	}
		
		
		
	//Elimina solic
	function Eliminar_Solicitud($idsolicitud_integral,$is_internacion,$idsolicitud_micam)
	{
	
		$this->urlXML = $this->url_gilsa.'peticion=205&idsolicitud='.$idsolicitud_integral;
		//echo $this->urlXML;
		
		
		//guardo una copia del TXT en la carpeta integral
		if ($is_internacion != 1)
		{
			$directory = $_SESSION['url_root'].'integral/ambulatorio/registro_eliminacion_amb_micam/'.$idsolicitud_micam.'-peticion205-solic-Micam--NroInteg-'.$idsolicitud_integral.'.txt';
		}
		else
		{
			$directory = $_SESSION['url_root'].'integral/internado/registro_eliminacion_int_micam/'.$idsolicitud_micam.'-peticion205-solic-Micam--NroInteg-'.$idsolicitud_integral.'.txt';
		}
		unlink($directory);
		if ($fp = fopen ($directory,"a")) 
		{
				fwrite($fp,"$this->urlXML\r\n");
		}
		fclose($fp);
		
		
		
		$xml = simplexml_load_file($this->urlXML);
		
		
		
		if (!is_object($xml))
		{
			return  0;
		}
		else
		{
			//GENERO TXT DE DEVOLUCION
			if ($is_internacion != 1)
			{
				$directory = $_SESSION['url_root'].'integral/ambulatorio/respuesta_eliminacion_amb_integral/'.$idsolicitud_micam.'-peticion205-solic--NroInteg-'.$idsolicitud_integral.'.txt';
			}
			else
			{
				$directory = $_SESSION['url_root'].'integral/internado/respuesta_eliminacion_int_integral/'.$idsolicitud_micam.'-peticion205-solic--NroInteg-'.$idsolicitud_integral.'.txt';
			}
			unlink($directory);
			
			foreach ($xml->_parametro2 as $item)
			{
				if ($fp = fopen ($directory,"a")) 
				{
					fwrite($fp,"$devolucion\r\n");
	
					$devolucion =  'idsolicitud_integral: '.$item->idsolicitud;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'idinternacion_integral: '.$item->idinternacion;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'estadoitem_integral: '.$item->estadoitem;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'iditemautorizacion_integral: '.$item->iditemautorizacion;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'cantidadautorizada: '.$item->cantidadautorizada;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'observacion: '.$item->observacion;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'fechaestado: '.$item->fechaestado;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'controlautomatizado: '.$item->controlautomatizado;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'iditem_micam: '.$item->iditemexterno;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'codnomenclador: '.$item->codnomenclador;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'descripnomenclador: '.$item->descripnomenclador;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'statuspeticion: '.$item->statuspeticion;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = '\r\n';
					fwrite($fp,"$devolucion\r\n");
			
				}
				fclose($fp);
				
				
				
				if ($item->estadoitem == 2)//es el estado Anulado para Integral
				{
					return 1;
				}
				else
				{
					return $item->statuspeticion;	
				}
			}
		}
	}
	
	
	
	//Obtiene el estado de cada item, para que nosotros luego actualicemos y veamos estado gral de solic. de nuestro sistema
	function Obtener_Estados_Solicitud($idsolicitud_integral)
	{
	
		$this->urlXML = $this->url_gilsa."peticion=204&idsolicitud=".$idsolicitud_integral;

		$xml = simplexml_load_file($this->urlXML);
			
		if (!is_object($xml))
		{
			return  0;
		}
		else
		{
			return $xml;
		}
	}
	
	
	function Obtener_Estados_MultiSolicitudes($string_ids, $cant_reg)
	{
	
		$cant_reg = str_pad($cant_reg,2,"0",STR_PAD_LEFT);
		$this->urlXML = $this->url_gilsa."peticion=207&cantRegistros=".$cant_reg."&ListaIDsolicitudes=%27".$string_ids."%27";
		
		//echo $this->urlXML;
		
		$xml = simplexml_load_file($this->urlXML);
			
		if (!is_object($xml))
		{
			echo "<tr class='servicesT'>
					<td align='left' colspan='5' >
						<div align='center' class='colorrojobold'>".$this->mensaje_error_conexion.' - Obtener_Estados_MultiSolicitudes'."</div>
					</td>
					</tr>";
			return  0;
		}
		else
		{
			//echo "ok";
			return $xml;
		}
	}
	
	
	function Actualizar_Solicitud_Integral($cantidad_items, $is_internacion)
	{
		$this->urlXML = $this->url_gilsa."peticion=206";
		$this->urlXML .= "&idsolicitud=".$this->idsolicitud;
		$this->urlXML .= "&observacion=%27".urlencode(rtrim($this->observacion))."%27";
		$this->urlXML .= "&condicion=%27".$this->Novedades_Obtener_Equivalencias_Micam_Integral($this->idnovedad)."%27";
		
		for ($i = 1; $i <= $cantidad_items; $i++) 
		{
			$this->urlXML .= $this->{"item".$i};
		}
		
		//echo $this->urlXML;
		
		
		//guardo una copia del TXT en la carpeta integral
		//genero un random ademas en el nombre dle txt por si audita mas de una vez la misma solic
		if ($is_internacion != 1)
		{
			$directory = $_SESSION['url_root'].'integral/ambulatorio/registro_auditoria_amb_micam/'.$this->idsolicitudexterno.'-peticion206-Actualiza_Solic_Integral-'.$this->idsolicitud_integral.'-rand-'.rand(0,100).'.txt';
		}
		else
		{
			$directory = $_SESSION['url_root'].'integral/internado/registro_auditoria_int_micam/'.$this->idsolicitudexterno.'-peticion206-Actualiza_Solic_Integral-'.$this->idsolicitud_integral.'-rand-'.rand(0,100).'.txt';
		}
		unlink($directory);
		if ($fp = fopen ($directory,"a")) 
		{
				fwrite($fp,"$this->urlXML\r\n");
		}
		fclose($fp);
		
		$xml = simplexml_load_file($this->urlXML);
		
		if (!is_object($xml))
		{
			return  0;
		}
		else
		{
			$random = rand(0,100);
			//GENERO TXT DE DEVOLUCION
			if ($is_internacion != 1)
			{
				$directory = $_SESSION['url_root'].'integral/ambulatorio/respuesta_auditoria_amb_integral/'.$this->idsolicitudexterno.'-peticion206_Actualiza_Solic_Integral-'.$this->idsolicitud_integral.'-rand-'.$random.'.txt';
			}
			else
			{
				$directory = $_SESSION['url_root'].'integral/internado/respuesta_auditoria_int_integral/'.$this->idsolicitudexterno.'-peticion206_Actualiza_Solic_Integral-'.$this->idsolicitud_integral.'-rand-'.$random.'.txt';
			}
			unlink($directory);
			//GUARDO LA DEVOLUCION EN TXT
			foreach ($xml->_parametro2 as $item)
			{
				
				if ($item->statuspeticion != '')
				{
					?>
                	<script>alert ('Problemas al auditar la solicitud de integral. Nro de transaccion para reclamos: <?php echo $this->idsolicitudexterno ?>. Intente nuevamente, o bien comuniquese con los administradores del sistema.');</script> <?php	
				}
				
				if ($fp = fopen ($directory,"a")) 
				{
					fwrite($fp,"$devolucion\r\n");
	
					$devolucion =  'idsolicitud_integral: '.$item->idsolicitud;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'idinternacion_integral: '.$item->idinternacion;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'estadoitem_integral: '.$item->estadoitem;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'iditemautorizacion_integral: '.$item->iditemautorizacion;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'cantidadautorizada: '.$item->cantidadautorizada;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'observacion: '.$item->observacion;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'fechaestado: '.$item->fechaestado;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'controlautomatizado: '.$item->controlautomatizado;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'iditem_micam: '.$item->iditemexterno;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'codnomenclador: '.$item->codnomenclador;
					fwrite($fp,"$devolucion\r\n");
				
					$devolucion = 'descripnomenclador: '.$item->descripnomenclador;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'statuspeticion: '.$item->statuspeticion;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'condicion: '.$item->condicion;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'montocoseguro: '.$item->montocoseguro;
					fwrite($fp,"$devolucion\r\n");
					
					$idsolicitud_integral = $item->idsolicitud;
				
				}
				fclose($fp);
				
			}// FIN FOR XML
			return $idsolicitud_integral;
		}
	}
		
		
	function Obtener_Estado_Item_Integral($idestado_micam)
	{
		switch ($idestado_micam)
		{
			
			case 1://micam-Pendiente de Auditoria
					return 1;//pendiente integral
					break;
			case 2://micam-Autorizado
					return 3;//autorizado integral
					break;
			case 3://micam-Rechazado
					return 4;//denegado integral
					break;
			case 4://micam-Con pedido de Estudio
					return 1;//pendiente integral
					break;
		}
	}	
	
	
	function Novedades_Obtener_Equivalencias_Micam_Integral($idnovedad)
	{
		switch ($idnovedad)
		{
			
			case 47://micam
					return 1;//Integral
					break;
			case 46://micam
					return 2;//integral
					break;
			case 7://micam
					return 3;//integral
					break;
			case 1://micam
					return 5;//integral
					break;				
		}
	}
	
	

	function Consumo($grupo_familiar, $documento_af)
	{
		//ejecuto peticion 201 para obtener id de afiliado de Integral
		$this->Elegibilidad_Afiliado_Documento_Credencial($documento_af,'');
		//formateo a 2 digitos
		$this->numero_familiar_nobis = str_pad(trim($this->numero_familiar_nobis),2,"0",STR_PAD_LEFT);
		
		$fecha_desde = date('d-m-o',strtotime('-5 year'));
		$fecha_hasta = date('d-m-o');
	
		
		$this->urlXML = $this->url_gilsa."peticion=208&GrupoFamiliar=%27".$grupo_familiar."%27&numeroAfil=".$this->numeroafil_nobis."&nroFamiliar=".$this->numero_familiar_nobis."&fechaDesde=%27".$fecha_desde."%27&fechaHasta=%27".$fecha_hasta."%27";

		
		$xml = simplexml_load_file($this->urlXML);
			
		if (!is_object($xml))
		{
			return  0;
		}
		else
		{
			return $xml;
		}
	}
	
	
	function Obtener_Solicitud_Impresion($idsolicitud_integral)
	{
	
		$this->urlXML = $this->url_gilsa."peticion=214&idsolicitud=".$idsolicitud_integral;

		$xml = simplexml_load_file($this->urlXML);
			
		if (!is_object($xml))
		{
			return  0;
		}
		else
		{
			return $xml;
		}
	}
	
	
	function Obtener_Coseguro($idsolicitud_integral,$idsolicitud_micam)
	{
	
		$this->urlXML = $this->url_gilsa."peticion=214&idsolicitud=".$idsolicitud_integral;

		$random = rand(0,100);
		
		$directory = $_SESSION['url_root'].'integral/ambulatorio/registro_coseguro/'.$idsolicitud_micam.'-peticion214_Coseguro-'.$idsolicitud_integral.'-rand-'.$random.'.txt';
		
		unlink($directory);
		if ($fp = fopen ($directory,"a")) 
		{
				fwrite($fp,"$this->urlXML\r\n");
		}
		fclose($fp);
		
		$xml = simplexml_load_file($this->urlXML);
		
		$directory = $_SESSION['url_root'].'integral/ambulatorio/respuesta_coseguro/'.$idsolicitud_micam.'-peticion214_Coseguro-'.$idsolicitud_integral.'-rand-'.$random.'.txt';
		
		unlink($directory);
		//GUARDO LA DEVOLUCION EN TXT
		foreach ($xml->_parametro2 as $item)
		{
			
			if ($item->statuspeticion != '')
			{
				?>
				<script>alert ('Problemas al obtener coseguro de la solicitud de integral. Nro de transaccion para reclamos: <?php echo $idsolicitud_micam ?>. Intente nuevamente, o bien comuniquese con los administradores del sistema.');</script> <?php	
			}
			
			if ($fp = fopen ($directory,"a")) 
			{
				fwrite($fp,"$devolucion\r\n");

				$devolucion =  'idsolicitud_integral: '.$item->idsolicitud;
				fwrite($fp,"$devolucion\r\n");
				
				$devolucion = 'montocoseguro: '.$item->montocoseguro;
				fwrite($fp,"$devolucion\r\n");
				
				$coseguro = $item->montocoseguro;
			
			}
			fclose($fp);
			
		}// FIN FOR XML
		
			
		if (!is_object($xml))
		{
			return  0;
		}
		else
		{
			return $coseguro;
		}
	}

	function Obtener_Descripcion_Estado_Integral($idestado_item_integral)
	{
		if ($idestado_item_integral == 1)
		{
			return "Pendiente";
		}
		if ($idestado_item_integral == 2)
		{
			return "Anulado";
		}
		if ($idestado_item_integral == 3)
		{
			return "Autorizado";
		}
		if ($idestado_item_integral == 4)
		{
			return "Denegado";
		}
		if ($idestado_item_integral == 5)
		{
			return "Autorizado";
		}
	}
	
	
	
	function Cierre_Internacion()
	{
		$this->urlXML = $this->url_gilsa."peticion=210";
		$this->urlXML .= "&idInternacion=".$this->idinternacion;
		$this->urlXML .= "&fechaEgreso=%27".date("d").'-'.date("m").'-'.date("o")."%27";
		$this->urlXML .= "&modoEgreso=%27".$this->modoegreso."%27";
		
		$directory = $_SESSION['url_root'].'integral/internado/cierre_int_micam/'.$this->idinternacion.'-peticion210_Cierre_Internacion_Integral-'.$this->idinternacionexterno.'-rand-'.rand(0,100).'.txt';
		unlink($directory);
		if ($fp = fopen ($directory,"a")) 
		{
				fwrite($fp,"$this->urlXML\r\n");
		}
		fclose($fp);
		
		$xml = simplexml_load_file($this->urlXML);
		
		if (!is_object($xml))
		{
			return  0;
		}
		else
		{
			$random = rand(0,100);
			//GENERO TXT DE DEVOLUCION
			$directory = $_SESSION['url_root'].'integral/internado/respuesta_cierre_integral/'.$this->idinternacion.'-peticion210_Cierre_Internacion_Integral-'.$this->idinternacionexterno.'-rand-'.$random.'.txt';
			unlink($directory);
			//GUARDO LA DEVOLUCION EN TXT
			foreach ($xml->_parametro2 as $item)
			{
				
				if ($item->statuspeticion != '')
				{
					?>
                	<script>alert ('Problemas al cerrar la internacion de integral. Nro de transaccion para reclamos: <?php echo $this->idinternacionexterno ?>. Intente nuevamente, o bien comuniquese con los administradores del sistema.');</script> <?php	
				}
				
				if ($fp = fopen ($directory,"a")) 
				{
					fwrite($fp,"$devolucion\r\n");
	
					$devolucion =  'idinternacion_integral: '.$item->idinternacion;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'numeroAfiliado: '.$item->numeroafiliado;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'nrofamiliar: '.$item->familiar;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'Prest Medico: '.$item->prestadormedico;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'Fecha Ingreso: '.$item->fechaingreso;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'Fecha Egreso: '.$item->fechaegreso;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'diagnostico: '.$item->diagnostico;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'Tipo Internacion: '.$item->tipointernacion;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'Modo Egreso: '.$item->modoegreso;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'Observacion: '.$item->observacion;
					fwrite($fp,"$devolucion\r\n");
				
					$devolucion = 'IdInternacion Micam: '.$item->idintexterno;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'Modo Internacion: '.$item->modointernacion;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'statuspeticion: '.$item->statuspeticion;
					fwrite($fp,"$devolucion\r\n");
					
					$idinternacion_integral = $item->idinternacion;
				
				}
				fclose($fp);
				
			}// FIN FOR XML
			return $idinternacion_integral;
		}
	}
	
	
	function Registrar_Impresion($idsolicitud_integral, $idsolicitud_micam, $is_internacion)
	{
		
		$this->urlXML = $this->url_gilsa."peticion=215";
		$this->urlXML .= "&idSolicitud=".$idsolicitud_integral;
		$this->urlXML .= "&fechaImpresion=%27".date("d").'-'.date("m").'-'.date("o").' '.date("H").':'.date("i").':'.date("s")."%27";
		$url = urlencode($this->urlXML );
		if ($is_internacion != 1)
		{
			$directory = $_SESSION['url_root'].'integral/ambulatorio/registro_impresion_solicitud/'.$idsolicitud_micam.'-peticion215_impresion_solicitud-nrointegral-'.$idsolicitud_integral.'.txt';			
		}
		else
		{
			$directory = $_SESSION['url_root'].'integral/internado/registro_impresion_solicitud/'.$idsolicitud_micam.'-peticion215_impresion_solicitud-nrointegral-'.$idsolicitud_integral.'.txt';		
		}
		
		unlink($directory);
		
		if ($fp = fopen ($directory,"a")) 
		{
				fwrite($fp,"$this->urlXML\r\n");
		}
		fclose($fp);
		
		$xml = simplexml_load_file($url);
		
		
		if (!is_object($xml))
		{
			return  0;
		}
		else
		{
			
			if ($is_internacion != 1)
			{
				$directory = $_SESSION['url_root'].'integral/ambulatorio/respuesta_impresion_solicitud/'.$idsolicitud_micam.'-peticion215_impresion_solicitud-nrointegral-'.$idsolicitud_integral.'.txt';
			}
			else
			{
				$directory = $_SESSION['url_root'].'integral/internado/respuesta_impresion_solicitud/'.$idsolicitud_micam.'-peticion215_impresion_solicitud-nrointegral-'.$idsolicitud_integral.'.txt';	
			}
			unlink($directory);
			
			//GUARDO LA DEVOLUCION EN TXT
			foreach ($xml->_parametro2 as $item)
			{
				if ($fp = fopen ($directory,"a")) 
				{
					
					fwrite($fp,"$devolucion\r\n");
	
					$devolucion =  'idsolicitud_integral: '.$item->idsolicitud;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'idinternacion_integral: '.$item->idinternacion;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'estadoitem: '.$item->estadoitem;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'iditemautorizacion: '.$item->iditemautorizacion;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'iditemexterno_micam: '.$item->iditemexterno;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'controlautomatizado: '.$item->controlautomatizado;
					fwrite($fp,"$devolucion\r\n");
					
					$devolucion = 'statuspeticion: '.$item->statuspeticion;
					fwrite($fp,"$devolucion\r\n");

					$idsolicitud = $item->idsolicitud;
				
				}
				fclose($fp);
				
			}// FIN FOR XML
			return $idsolicitud;
		}
	}
		
		
		
	function Obtener_Alertas($nrodocumento)
	{
		$this->Elegibilidad_Afiliado_Documento_Credencial($nrodocumento,'');
		
		$this->urlXML = $this->url_gilsa."peticion=209&numeroAfil=".$this->numeroafil_nobis."&nroFamiliar=".str_pad(trim($this->numero_familiar_nobis),2,"0",STR_PAD_LEFT);
		$xml = simplexml_load_file($this->urlXML);
		
		if (!is_object($xml))
		{

			return  0;
		}
		else
		{
			return $xml;
		}
	}

	function obtenertodo_para_combo_novedades($idnovedad, $numeroafil_integral, $nrofamiliar_integral)
	{

		$this->urlXML = $this->url_gilsa."peticion=216";
		$this->urlXML .= "&numeroAfil=".$numeroafil_integral;
		$this->urlXML .= "&nroFamiliar=".$nrofamiliar_integral;
		
		$cadena= '<option></option>';

		$xml = simplexml_load_file($this->urlXML);
		
		
		if (is_object($xml))
		{
			foreach ($xml->_parametro2 as $item)
			{
				$idnovedad_cbo = $this->Novedades_Obtener_Equivalencias_Integral_Micam($item->condicion); 
				if ($idnovedad != $idnovedad_cbo)
				{
					$cadena.= "<option value=\"".$idnovedad_cbo."\">".$item->descripcioncondicion."</option>";
				}
				else
				{	
					$cadena.= "<option value=\"".$idnovedad_cbo."\" selected>".$item->descripcioncondicion."</option>";			
				}
			}
		}
		
		return $cadena;	
	}


	function Novedades_Obtener_Equivalencias_Integral_Micam($idnovedad)
	{
		switch ($idnovedad)
		{
			
			case 1://integral
					return 47;//micam
					break;
			case 2://integral
					return 46;//micam
					break;
			case 3://integral
					return 7;//micam
					break;
			case 5://integral
					return 1;//nuestro
					break;				
		}
	}
	
}
?>