<?php
/*
  modCONTROLES.php: Modulo que Contiene las Funciones de Gestion del Marco de Trabajo
       
   Copyrigth 2013 Miguel Hernandez Liebano <mhliebano@gmail.com;mhernandez@unerg.edu.ve>
       
   This file is part of Achitun 
   Achitun is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Achitun is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Achitun.  If not, see <http://www.gnu.org/licenses/>
 */

function L_salirV(){
    global $objSes;
    echo "<h1>Hasta Pronto</h1>";
    flog("Se desconecto el USUARIO ".fUsuarioID());
    $objSes->fSalir();
    fRedir("./");
}

function fBloqueo(){
    global $objSes;
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fBloqueo: ";
    if($objSes->fProtegido()){
        if (depuracion())
            $traza.=" Bloqueado</p></div>";
        return true;
    }
    if (depuracion())
        $traza.=" Desbloqueado</p></div>";
    return false;
}

function fBuscar($multiple=true,$tabla=null,$msg=true){
    global $objBd;
    global $datos;
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fBuscar</p>";
    if (is_null($multiple)){
        if (depuracion())
            $traza.="<p style='color:#A52A2A;font-weight: bold'>Error 207 No se indico el parametro multiple[true,false]</p></div>";
        if($msg)
            echo "<h1 style='color:red'>Error 207: NO se indico el parametro multiple[true,false]</h1>";
         return 207;
    }
    if(!is_bool($multiple)){
        if (depuracion())
            $traza.="<p style='color:#A52A2A;font-weight: bold'>Error 208 Se esperaba que parametro multiple fuese un Booleano[true,false]</p></div>";
        if($msg)
            echo "<h1 style='color:red'>Error 208 Se esperaba que parametro multiple fuese un Booleano[true,false]]</h1>";
         return 208;
    }
    
    if($tabla==null){
        if($datos==null){
            if($msg)
                echo "<h1 style='color:red'>Error 202:El Argumento Tabla no esta Definido</h1>";
            return 202;
        }else{
           $tabla=$datos;
        }
    }
    $vari=func_get_args();
    if(func_num_args()>2){
        for($i=3;$i<func_num_args();$i++){
            $opciones=explode(";",$vari[$i]);
            $op="=";
            if(substr($opciones[0],0,1)=="!"){
                $op="<>";
                $opciones[0]=substr($opciones[0],1,strlen($opciones[0]));
            }
            if($i==func_num_args()-1){
                switch ($opciones[0]){
                    case "id":
                        $crit.=" ".$tabla.".id$op'".$opciones[1]."'";
                        break;
                    case "elm":
                        $crit.=" ".$tabla.".eliminado$op'".$opciones[1]."'";
                        break;
                    case "ord":
                        $crit=substr($crit,1,strlen($crit)-5);
                        $crit.=" ORDER BY ".$opciones[1];
                        break;
                    case "lkl":
                        $crit.=" ".$opciones[1];
                        break;
                    default:
                        $crit.=$opciones[0]."$op'".$opciones[1]."'";
                        break;
                }
            }else{
                switch ($opciones[0]){
                    case "id":
                        $crit.=" ".$tabla.".id$op'".$opciones[1]."' AND ";
                        break;
                    case "elm":
                        $crit.=" ".$tabla.".eliminado$op'".$opciones[1]."' AND ";
                        break;
                    default:
                        $crit.=" ".$opciones[0]."$op'".$opciones[1]."' AND ";
                        break;
                }
            }
        }
    }else{
        $crit="";
    }
    $c= fMostrarRelaciones($tabla);
    $c=trim($c," AND ");
    if($crit==""){
        $crit=$c;
    }else{
        if($c!="")
            $crit=$c." AND ".$crit;
        else
            $crit=$crit;
    }
    $com=fMostrarTablasRelacionadasBD($tabla);
    $ta=trim($com,",");
    if($crit==""){
        $f= $objBd->fSelect($ta);
    }else{
        $f=$objBd->fSelect($ta,"*",$crit);
    }
    if($f==null){
        echo "<h1 style='color:red'>Error 109:Error de Sintaxis en la Consulta SQL</h1>";
        if(depuracion())
            $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR BD 109: la Consulta ha Fallado</p></div>";
        return 109;
    }
    if (depuracion()){
        $cc=$objBd->fCantidadCampos();
        $cr=$objBd->fCantidadRegistros();
        $cp=$objBd->fNombresCampos();
        $traza.="<table><tr>";
        for($i=0;$i<$cc;$i++){
            $traza.="<th>$cp[$i]-($i)</th>";
        }
        $traza.="</tr>";
        
    }
    if($multiple){
        while($a=$objBd->fConsultaArreglo()){
            $t[]=$a;
            if (depuracion()){
                $traza.="<tr>";
                for($i=0;$i<$cc;$i++){
                    $traza.="<td>$a[$i]</td>";
                }
                $traza.="</tr>";
            }
        }
        flog("Se consultarón multiples datos en la tabla ".$tabla." devolviendo ($cr) datos");
    }else{
        $t=$objBd->fConsultaArreglo();
        if (depuracion()){
                $traza.="<tr>";
                for($i=0;$i<$cc;$i++){
                    $traza.="<td>$t[$i]</td>";
                }
                $traza.="</tr>";
        }
        flog("Se consulto un dato en la tabla ".$tabla." devolviendo ($cr) datos");
    }
    if (depuracion())
        $traza.="</table></div>";
    return $t;
}

function fCalendario($anio, $mes,$contenido){
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fCalendario</p>";
    if($mes<=0 && $mes>12){
        if (depuracion())
             $traza.="<p style='color:#A52A2A;font-weight: bold'>Error 010 Se esperaba que el parametro mes fuese un entero entre [1,12]</p></div>";
        return 10;
    }
    if($anio<=1900 && $anio>2050){
        if (depuracion())
             $traza.="<p style='color:#A52A2A;font-weight: bold'>Error 010 Se esperaba que el parametro año fuese un entero entre [1900,2050]</p></div>";
        return 10;
    }
    if(!is_array($contenido)){
        if (depuracion())
             $traza.="<p style='color:#A52A2A;font-weight: bold'>Error 011 Se esperaba que el parametro contenido fuese un arreglo</p></div>";
        return 11;
    }
    $day_name_length = 5;
    $inicio = gmmktime(0,0,0,$mes,1,$anio);
    $day_names = array(Domingo,Lunes,martes,Miercoles,Jueves,Viernes,Sabado);
    $meses=array(Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre,Octubre,Noviembre,Diciembre);
    list($weekday) = explode(',',gmstrftime('%w',$inicio));
    $weekday = ($weekday + 7) % 7; #adjust for $first_day
   $calendar = '<table class="calendario"><caption class="etiqueta">'.$meses[$mes-1].'  '.$anio."</caption>\n<tr>";
   foreach($day_names as $d)
        $calendar .= '<th class="nombreDia">'.$d.'</th>';
    $calendar .= "</tr><tr class='semana'>";

    if($weekday > 0) 
        $calendar .= '<td colspan="'.$weekday.'"  class="sindia">&nbsp;</td>'; #initial 'empty' days
    for($day=1,$days_in_month=gmdate('t',$inicio); $day<=$days_in_month; $day++,$weekday++){
        if($weekday == 7){
            $weekday   = 0; #start a new week
            $calendar .= "</tr>\n<tr class='semana'>";
        }
        $calendar .= '<td class="celda"><span class="dia">'.$day.'</span><span class="contenido">'.$contenido[$day].'</span></td>';
    }
    if($weekday != 7)
        $calendar .= '<td colspan="'.(7-$weekday).'" class="sindia">&nbsp;</td>';
    return $calendar."</tr>\n</table>\n";
}

function fCaptcha($ruta='nucleo/tmp/'){

    #create image and set background color
	$captcha = imagecreatetruecolor(120,35);
	$color = rand(128,160);
	$background_color = imagecolorallocate($captcha, $color, $color, $color);
	imagefill($captcha, 0, 0, $background_color);
	
	#draw some lines
	for($i=0;$i<10;$i++){
		$color = rand(48,96);
		imageline($captcha, rand(0,130),rand(0,35), rand(0,130), rand(0,35),imagecolorallocate($captcha, $color, $color, $color));
	}
	
	#generate a random string of 5 characters
	$string = fClaveAleatoria();
    $nombre= fClaveAleatoria(10);
	#make string uppercase and replace "O" and "0" to avoid mistakes
	$string = strtoupper($string);

	#save string in session "captcha" key
    if(file_exists($ruta.$_SESSION["imagenCaptcha"].".jpg")){
        unlink($ruta.$_SESSION["imagenCaptcha"].".jpg");
    }
    $imagen=$ruta.$nombre.".jpg";
	$_SESSION["captcha"]=$string;
    $_SESSION["imagenCaptcha"]=$nombre;
	#place each character in a random position
	putenv('GDFONTPATH=' . realpath('.'));
	$font = 'arial.ttf';
	for($i=0;$i<5;$i++){
		$color = rand(0,32);
		if(file_exists($font)){
			$x=4+$i*23+rand(0,6);
			$y=rand(18,28);
			imagettftext  ($captcha, 15, rand(-25,25), $x, $y, imagecolorallocate($captcha, $color, $color, $color), $font, $string[$i]);
		}else{
			$x=5+$i*24+rand(0,6);
			$y=rand(1,18);
			imagestring($captcha, 5, $x, $y, $string[$i], imagecolorallocate($captcha, $color, $color, $color));
		}
	}
	
	#applies distorsion to image
	$matrix = array(array(1, 1, 1), array(1.0, 7, 1.0), array(1, 1, 1));
	imageconvolution($captcha, $matrix, 16, 32);
	imagejpeg($captcha,$imagen);
    return "<div id='cpt'><img src='$imagen' class='captcha' id='captcha'/>".fCampo(capt,"Ingrese el Texto de la Imagen",texto).fAjax(captcha,click,captcha,cpt,null,"u=0")."</div>";
}

function fEliminaCaptcha($ruta='nucleo/tmp/'){
    if(file_exists($ruta.$_SESSION["imagenCaptcha"].".jpg")){
        unlink($ruta.$_SESSION["imagenCaptcha"].".jpg");
    }
}

function A_captcha(){
    echo fCaptcha();
}
function fCapturaEvento(){
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fCapturaEvento</p><p>Evento Capturado ".$_GET['e']."</p></div>";
    return $_GET['e'];
        
}

function fCapturaModulo($modulo){
    if($_GET['m']==$modulo)
        return true;
    else
        return false;
}

function fEdicion(){
    return $_SESSION['ed'];
}

function fEliminar($tabla=null,$msg=true,$id=0){
    global $objBd;
    global $datos;
    global $traza;
    $_SESSION['el']=0;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fEliminar</p>";
    if($id==0)
        $id=fId();
    if($id==null){
        if($msg)
            echo "<h1 style='color:red'>Error 210:No se ha Definido Identifcador</h1>";
         if (depuracion())
            $traza.="</div>";
        return;
    }
    if($datos==null && $tabla==null && $_SESSION['tabla']==null){
         if($msg)
            echo "<h1 style='color:red'>Error 202:El Argumento Tabla no esta Definido</h1>";
         if (depuracion())
            $traza.="<p>El Argumento Id no se ha definido</p></div>";
        return 210;
    }else{
        if($tabla!=null){
            $tabla=$tabla;
        }else{
            if($_SESSION['tabla']!=null)
                $tabla=$_SESSION['tabla'];
            else
                if($tabla==null)
                    $tabla=$datos;
        }
    }
    if (depuracion())
            $traza.="<p>Eliminando en la Tabla $tabla el indice: ".$id."</p>";
    if($objBd->fUpdate($tabla,"eliminado=1,modificado='".fFechaActualMysql()."'","$tabla.id=".$id)){
        if (depuracion())
            $traza.="<p>Operacion Exitosa</p></div>";
        if($msg)
            fMensaje(1,"Se ha Eliminado el Registro");
        flog("Se ha eliminado el Registro N° ".$id."de la Tabla ".$tabla);
        return 0;
    }else{
        if (depuracion())
            $traza.="<p>Operacion Fallida</p></div>";
        if($msg)
           fMensaje(3,"Ocurrio un Error al Eliminar el Registro");
        flog("Se Intento(fallido) Eliminar el Registro N° ".fId()."de la Tabla ".$tabla);
        return -1;
    }
    
}

function fEnviadoPost(){
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fEnviadoPost</p>";
    $data='';
    if (count($_POST)==0)
        return null;
    foreach ($_POST as  $key=>$value) {
        $m=substr($value,3,2);
        $sp1=substr($value,2,1);
        $d=substr($value,0,2);
        $sp2=substr($value,5,1);
        $a=substr($value,6,4);
            if(is_numeric($m) && is_numeric($d) && is_numeric($a) && $sp1=="/" && $sp2=="/")
                if(checkdate($m,$d,$a))
                    $value=fFechaMysql($value);
            $value=addslashes($value);
            $value=htmlentities($value,ENT_IGNORE,"utf-8");
            $value=trim($value);
            $kv[] = "$value";
            if (depuracion()){
                $campos.=$key.",";
                $traza.="<p style='color:#095909;font-weight: bold'>$key=$value</p>";
             }
        }
        if (depuracion())
                $traza.="<p>Campos: $campos</p></div>";
        array_pop($kv);
        return $kv;
}

function fEnviarCorreo($asunto,$mensaje,$desde,$firma,$pie=null,$correo=null,$msg=false){
    global $objBd;
     global $traza;
     $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
     $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
     $cabeceras .= 'From: '.$desde.'\r\n';
     

    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fEnviarCorreo</p></div>";
    $mensaje="<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>".$mensaje."\n\n\n".$firma."... \n\n\n".$pie."\n\n</body></html>";
    
    if($correo==null){
        $objBd->fSelect("info_admin","*","eliminado=0");
        while($x=$objBd->fConsultaArreglo()){
            if (mail($x['correo'], $asunto, $mensaje,$cabeceras)){
                if($msg)
                    echo "Enviado con exito a ".$x['nombres']." ".$x['apellidos']." (".$x['correo'].") </br>";
            }else{
                if($msg)
                    echo "Error al Enviar a ".$x['nombres']." ".$x['apellidos']." (".$x['correo'].") </br>";
            }
        }
        
    }else{
        if (mail($correo, $asunto, $mensaje,$cabeceras)){
            if($msg)
                echo "Enviado con exito a ".$correo.") </br>";
            return 0;
        }else{
            if($msg)
                echo "Falló el intento de enviar correo a ".$correo.") </br>";
            return -1;
        }
    }
}

function fEscribirArchivo($archivo,$texto){
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fEscribirArchivo</p></div>";
        $fichero = fopen($archivo,"a");
        fwrite($fichero, $texto);
        fclose($fichero);
}

function fEvento($accion,$num=1){
    global $traza;
    if($num==1){
        if (depuracion())
            $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fEvento</p><p>funcion: $accion</p></div>";
        if($_GET['e']==1){
            if(function_exists($accion)){
                echo eval($accion());
            }else{
                echo "<h1 style='color:red'>Error 301: La Accion Solicitada No esta Disponible</h1>";
            }
        }
    }else{
        if (depuracion())
            $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fEvento</p><p>funcion: $accion</p></div>";
        if($_GET['e']==$num){
            if(function_exists($accion)){
                echo eval($accion());
            }else{
                echo "<h1 style='color:red'>Error 301: La Accion Solicitada No esta Disponible</h1>";
            }
        }
    }
}

function fEventoClick($accion){
    global $traza;

    if($_POST['boton']!=null){
        if (depuracion())
            $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fEventoClick</p><p>funcion: $accion</p></div>";
        if(function_exists($accion)){
               echo eval($accion());
         }else{
            echo "<h1 style='color:red'>Error 301: La Accion Solicitada No esta Disponible</h1>";
         }
     }
}

function fExisteTabla($tabla){
     global $objBd;
     global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fExisteTabla</p>";
    if($objBd->fDatos_tabla($tabla)!=-1){
        if (depuracion())
            $traza.="<p>La tabla Existe</p></div>";
        return 0;
    }else{
        if (depuracion())
            $traza.="<p>La tabla No Existe</p></div>";
        return 1;
    }
}

function fForzaDescarga(){
    $fl=fId().".".fItemId();
    $rut=$_GET['rut'];
    $dst=$_GET["des"];
    $fileUrl="conf/datos/".$rut."/".$fl;
    if (substr($fileUrl,0,4)=='http'){
        $fileSize = array_change_key_case(get_headers($fileUrl, 1),CASE_LOWER);
        if ( strcasecmp($fileSize[0], 'HTTP/1.1 200 OK') != 0 ) {
            $fileSize = $fileSize['content-length'][1]; 
        }else{ 
            $fileSize = $fileSize['content-length']; 
        }
    } else { 
        $fileSize = @filesize($fileUrl); 
    }
 
	// download file
	$ctype="application/octet-stream";
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("Content-Type: $ctype");
 
	header("Content-Disposition: attachment; filename=\"".basename($fileUrl)."\";" );
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".$fileSize);
	readfile("$fileUrl");
    if($dst!=null){
       unlink($fileUrl);
       fRedir("./");
    }
	exit();
}


function fGuardar($tabla=null,$accion=0,$msg=true){
    global $datos;
    global $objBd;
    global $traza;
    $vari=func_get_args();
    $_SESSION['gr']=0;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fGuardar</p><p>Modo Edicion: ".fEdicion()."</p>";
    
    if($datos==null && $tabla==null && $_SESSION['tabla']==null){
        echo "<h1 style='color:red'>Error 202:El Argumento Tabla no esta Definido</h1></div>";
        return 202;
    }else{
        if($tabla!=null){
            $tabla=$tabla;
        }else{
            if($_SESSION['tabla']!=null)
                $tabla=$_SESSION['tabla'];
            else
                if($tabla==null)
                    $tabla=$datos;
        }
    }
    
    
    $cantCampos=0;
    $cantDatos=0;
    if (count(fEnviadoPost())<1){
        return;
    }
switch($accion){
    case 0:
        foreach ($_POST as  $key => $value) {
                if($key=="capt")
                    continue;
                if(!is_numeric($value)){
                    $m=substr($value,3,2);
                    $b1=substr($value,2,1);
                    $b2=substr($value,5,1);
                    $d=substr($value,0,2);
                    $a=substr($value,6,4);
                    if(is_numeric($m) && is_numeric($d) && is_numeric($a) && $b1=="/" && $b2=="/")
                        if(checkdate($m,$d,$a))
                            $value=fFechaMysql($value);
                }
                 $value=addslashes($value);
                $value=htmlentities($value,ENT_IGNORE,"utf-8");
                $value=trim($value);
                $kv[] ="'$value'";
                $campos[] = $key;
                if (depuracion())
                    $traza.="<p>$key=$value</p>";
            }
            array_pop($kv);
            array_pop($campos);
        if(fId()!=0 && $_SESSION['ed']==1){
            $campos[]="modificado";
            $kv[]=fFechaActualMysql();
            for($i=0;$i<count($campos);$i++){
                if($i==(count($campos)-1))
                    $data.=$campos[$i]."=".$kv[$i];
                else
                    $data.=$campos[$i]."=".$kv[$i].",";
            }
            if (depuracion())
                $traza.="Actualizando ('Modo Automatico 0') datos en  $tabla con los valores $data </p>";
            if($objBd->fUpdate($tabla,"$data","id=".fId())){
                if (depuracion())
                        $traza.="<p>Operacion Exitosa</p></div>";
                    if($msg)
                        fMensaje(1,"La actualizacion de Datos ha Sido Exitosa");
                    flog("Se actualizaron datos en modo automatico en la tabla ".$tabla);
                    return 0;
            }else{
                if (depuracion())
                        $traza.="<p>Operacion Fallida</p></div>";
                    if($msg)
                        fMensaje(1,"La actualizacion de Datos ha Fallado");
                    flog("Se Intentó(fallido) datos en modo automatico en la tabla ".$tabla);
                    return -1;
            }
        }else{
        //guardar nuevo registro
            $data='';$cpos='';
            for($i=0;$i<count($kv);$i++){
                if($i==(count($kv)-1)){
                    $data.=$kv[$i];
                    $cpos.=$campos[$i];
                }else{
                    $data.=$kv[$i].",";
                    $cpos.=$campos[$i].",";
                }
            }
            $data.= ",'".fFechaActualMysql()."','".fFechaActualMysql()."',0";
            $cpos.=",creado,modificado,eliminado";
            if (depuracion())
                $traza.="Guardando ('Modo Automatico 0') en la Tabla $tabla los valores: $data en los Siguientes campos $cpos</p>";
                if($objBd->fInsert($tabla,$data,$cpos)){
                     if (depuracion())
                        $traza.="<p>Operacion Exitosa</p></div>";
                    if($msg)
                        fMensaje(1,"El Registro de Datos ha Sido Exitoso");
                    $_SESSION['tabla']="";
                    $_SESSION['ed']=0;
                    flog("Se guardaron datos en modo automatico en la tabla ".$tabla);
                    return 0;
                }else{
                    if (depuracion())
                        $traza.="<p>Operacion Fallida</p></div>";
                    if($msg)
                        fMensaje(3,"Ocurrio algun Error al Registrar los Datos");
                        $_SESSION['tabla']="";
                        $_SESSION['ed']=0;
                    flog("Se Intentó guardar datos en modo automatico en la tabla ".$tabla);
                    return -1;
                }
        }
        break;
    case 1:
        //guardar manualmente
        if(func_num_args()!=5){
            echo "<h1 style='color:red'>Error 201: NUmero de Argumentos de la Funcion No son Correctos</h1>";
            return 201;
        }
        $valores= $vari[3].",'".fFechaActualMysql()."','".fFechaActualMysql()."',0";
        $campos=$vari[4].",creado,modificado,eliminado";
        if (depuracion())
            $traza.="Guardando ('Modo Manual 1') en la Tabla $tabla los valores: $valores en los Siguientes campos $campos</p>";
        if($objBd->fInsert($tabla,$valores,$campos)){
            if (depuracion())
                $traza.="<p>Operacion Exitosa</p></div>";
            if($msg)
                fMensaje(1,"El Registro de Datos ha Sido Exitoso");
            $_SESSION['tabla']="";
            $_SESSION['ed']=0;
            flog("Se guardaron datos en modo manual en la tabla ".$tabla);
            return 0;
        }else{
            if (depuracion())
                $traza.="<p>Operacion Fallida</p></div>";
            if($msg)
                fMensaje(3,"Ocurrio algun Error al Registrar los Datos");
            $_SESSION['tabla']="";
            $_SESSION['ed']=0;
            flog("Se Intentó(fallo) guardar datos en modo manual en la tabla ".$tabla);
            return -1;
        }
        break;
    case 2:
        //actualizar manualmente
        if($vari[3]==null)
            $id="=".fId();
        else
            $id=$vari[3];
        if(func_num_args()>4){
            for($i=4;$i<func_num_args();$i++){
                $campos[]=$vari[$i];
            }
        }
        
        $campos[]="modificado='".fFechaActualMysql()."'";
        for($i=0;$i<count($campos);$i++){
            if($i==(count($campos)-1))
                $data.=$campos[$i];
            else
                $data.=$campos[$i].",";
        }
        if (depuracion())
            $traza.="Actualizando ('Modo Manual 2') datos en  $tabla  con los valores $data </p>";
        if($objBd->fUpdate($tabla,"$data","id$id")){
           if (depuracion())
                $traza.="<p>Operacion Exitosa</p></div>";
            if($msg)
                fMensaje(1,"La Actualizacion de Datos ha Sido Exitosa");
            $_SESSION['tabla']="";
            $_SESSION['ed']=0;
            flog("Se Actualizaron datos en modo manual en la tabla ".$tabla);
            return 0;
        }else{
            if (depuracion())
                $traza.="<p>Operacion Fallida</p></div>";
            if($msg)
                fMensaje(3,"Ocurrio algun Error al Actualizar los Datos".mysql_error());
            $_SESSION['tabla']="";
            $_SESSION['ed']=0;
            flog("Se Intentó actualizar datos en modo automatico en la tabla ".$tabla);
            return -1;
        }
        break;
}
    
}

function fId(){
    return $_GET['id'];
}

function fImprimir($contenido,$nombre,$forzado=1,$estilo='imprimir',$orientacion=0){
    if (file_exists("./nucleo/tmp/$nombre.pdf"))
        unlink("./nucleo/tmp/$nombre.pdf");
    require_once("nucleo/clases/dompdf_config.inc.php");
    $dompdf = new DOMPDF();
    if($orientacion==0)
        $dompdf->set_paper("letter", "portrait");
    else
        $dompdf->set_paper("letter", "landscape");
    $dompdf->body=true;
    $contenido=" <!doctype html><html><head><link rel='stylesheet' href='./conf/estilos/$estilo.css' type='text/css' /> </head> <body>".$contenido."</body> </html>"; 
    $dompdf->load_html($contenido);
    $dompdf->render();
    $pdf = $dompdf->output();
    file_put_contents("./nucleo/tmp/$nombre.pdf", $pdf);
    if($forzado!=1)
        return "<a href='./nucleo/tmp/$nombre.pdf'><img src='nucleo/iconos/printer.png'/>Descargar</a>";
}

function fItemId(){
    return $_GET['itemid'];
}

function fLeerArchivo($archivo,$modo=0){
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fLeerArchivo</p>";
    
    if(file_exists($archivo)){
        if($modo==0){
            $fichero = fopen ($archivo, "r");
            $contenido= fread($fichero, filesize($archivo));
        }else{
            $fichero = fopen ($archivo, "r");
            while(!feof($archivo))
                $contenido[]=fgets($file);
        }
        fclose($fichero);
         if (depuracion())
            $traza.="</div>";
        return $contenido;
        
    }else{
        if (depuracion())
            $traza.="</div>";
         echo "<h1 style='color:red'>Error 501:No se Encontro el Archivo</h1>";
        return 501;
    }
}

function fListaArchivo($ruta,$relativa=false){
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion Lista Archivos</p><p>Buscando en el directorio $ruta</p>";
    if (!is_dir($ruta)){
        if (depuracion())
            $traza.="<p style='color:#A52A2A;font-weight: bold'>El paramentro $ruta no es valido o no existe</p></div>";
        return false;
    }
    $info = array();
    if ($manejador = opendir($ruta)){
        while (false !== ($archiv = readdir($manejador))){
            if (!is_dir($ruta.'/'.$archiv)){
                if ($archiv != '..' && $archiv != '.' && $archiv != ''){
                    if ($relativa)
                        $info[] = $ruta.'/'.$archiv;
                    else
                        $info[] = $archiv;
                }
            }
        }
        closedir($manejador);
        if (depuracion())
            $traza.="</div>";
        return $info;
    }else{
        if (depuracion())
            $traza.="<p style='color:#A52A2A;font-weight: bold'>El paramentro $ruta no es valido o no existe</p></div>";
        return false;
    }
}

function fListaDirectorio($ruta,$relativa=false){
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion Lista Archivos</p><p>Buscando en el directorio $ruta</p>";
    if (!is_dir($ruta)){
        if (depuracion())
        $traza.="<p style='color:#A52A2A;font-weight: bold'>El paramentro $ruta no es valido o no existe</p></div>";
        return false;
    }
    $info = array();
    if ($manejador = opendir($ruta)){
        while (false !== ($archiv = readdir($manejador))){
            if (is_dir($ruta.'/'.$archiv)){
                if ($archiv != '..' && $archiv != '.' && $archiv != ''){
                    if ($relativa)
                        $info[] = $ruta.'/'.$archiv;
                    else
                        $info[] = $archiv;
                }
            }
        }
        closedir($manejador);
        if (depuracion())
            $traza.="</div>";
        return $info;
    }else{
        if (depuracion())
            $traza.="<p style='color:#A52A2A;font-weight: bold'>El paramentro $ruta no es valido o no existe</p></div>";
        return false;
    }
}

function fLogin(){
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fLogin</p></div>";
    fEventoClick("fValidar");
    $dir="m=".$_GET['m']."&v=".$_GET['v']."&id=".$_GET['id'];
    $a= fFormulario("flogin",$dir);
        $a.= fCampo('usur','Usuario','texto');
        $a.= fCampo('clav','Clave','clave');
        $a.= fCampo('boton','Entrar','submit');
    $a.= fFinFormulario();
    $a.= fValidacionCampos("flogin","usur;Indique Usuario","clav;Indique Clave");
    return $a;
}

function fMensaje($tipo,$msg){
    switch($tipo){
        case 1:
        echo "<div class='msgOk' id='msg'>$msg</div>";
        break;
    case 2:
        echo "<div class='msgAd' id='msg'>$msg</div>";
        break;
    case 3:
        echo "<div class='msgEr' id='msg'>$msg</div>";
        break;
    }
}

function fMostrarRelaciones($tbl){
    global $objBd;
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fMostrarRelaciones</p>";
    
        $objBd->fRelaciones();
        //$relaciones=$objBd->fConsultaArreglo();foreach($relaciones as $t)
        while($t=$objBd->fConsultaArreglo()){
            if($t[0]==$tbl){
                $rel[]=$t[1];
                $s.=$tbl.".".$t[1]."=".$t[1].".id AND ";
            }
            $tablas[]= $t[0];
            $relacion[]=$t[1];
        }
        $tbl=$rel[0];
        while (count($rel)!=0){
            if(in_array($tbl,$tablas)){
                $x=array_search($tbl,$tablas);
                $s.=$tablas[$x].".".$relacion[$x]."=".$relacion[$x].".id AND ";
                unset($tablas[$x]);
                unset($relacion[$x]);
                $tablas=array_values($tablas);
                $relacion=array_values($relacion);
            }else{
                unset($rel[0]);
                $rel=array_values($rel);
                $tbl=$rel[0];
            }
        }
        if (depuracion()){
            if($s=="")
            $traza.= "<p>Sin relaciones</p></div>";
            else
            $traza.= "<p>$s</p></div>";
        }
        return $s;
}

function fMostrarTabla($tabla=null,$id=null,$campos=null,$crit=null,$ed=null,$el=null,$paginar=0,$class=null,$enlace=null){
    global $objBd;
    global $datos;
    global $traza;
    global $mascara;
    
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fMostrarTabla</p>";
    
    if($tabla==null){
        if($datos==null){
            if($msg)
                echo "<h1 style='color:red'>Error 202:El Argumento Tabla no esta Definido</h1>";
            return 202;
        }else{
           $tabla=$datos;
        }
    }
    if($campos!=null)
        $cmp=explode(";",$campos);
    if($enlace!=null)
        $enl=explode(";",$enlace);

    $a= "<table id='$id' class='$class'><thead><tr>";

    //verifico que existan las tablas relacionadas
    $com=fMostrarTablasRelacionadasBD($tabla);
    $com=trim($com,",");
    if($com=="")
        $ta=$tabla;
    else
        $ta=$com;
    //verifico los filtros
    $c=fMostrarRelaciones($tabla);
    
    if($crit==null){
        $criterio= trim($c, " AND ");   
    }else{
        if($c=="")
            $criterio= $criterio;   
        else
            $criterio= trim($c, " AND ")." AND ".$criterio;   
    }
    if($crit!=null)
        $criterio.=$crit;
    
    if($paginar!=0){
        if($_GET['ini']==null)
            $l=0;
        else
            $l=$_GET['ini'];
        $l2=$paginar;
        $paginacion="$tabla.id DESC LIMIT $l,$l2";
    }
    
    if($criterio=="")
        $objBd->fSelect($ta,"*",null,$paginacion);
    else
        $objBd->fSelect($ta,"*",$criterio,$paginacion);
        
        $objBd->fCantidadCampos();
        $regis=$objBd->fCantidadRegistros();
        $cam=$objBd->fNombresCampos();
        if($mascara==null){
            //muestro los encabezados originales de las tablas
            for($i=0;$i<count($cam);$i++){
                if($cam[$i]=='id')
                    $ids[]=$i;
                if($campos!=null){
                    if(in_array($i,$cmp))
                        $a.= "<th title=$i>$cam[$i]</th>";
                }else{
                    $cmp[]=$i;
                    $a.= "<th title=$i>$cam[$i]</th>";
                }
            }
        }else{
            for($i=0;$i<count($cam);$i++){
                if($cam[$i]=='id')
                    $ids[]=$i;
                if($campos!=null){
                    if(in_array($i,$cmp))
                        $a.= "<th title=$i>$mascara[$i]</th>";
                }else{
                    $cmp[]=$i;
                    if($mascara[$i]!=null)
                        $a.= "<th title=$i>$mascara[$i]</th>";
                    else
                        $a.= "<th title=$i>$cam[$i]</th>";
                }
            }
        }
        //agrego las columnas editar y anluar(si es necesario)
    if(!is_null($ed)){
        $a.= "<th>Editar</th>";
        if(!is_string($ed))
            $vista=$_GET['v'] ;
        else
            $vista=$ed;
    }

    if(!is_null($el)){
        $a.= "<th>Anular</th>";
        if($el==0)
            $_SESSION['el']=1;
        else
            $el=$el;
    } 
    if(!is_null($enlace)){
        $a.= "<th></th>";
    }
    $a.= "</tr></thead><tbody>";
        while($o=$objBd->fConsultaArreglo()){
            $a.= "<tr id='f-".$o[$ids[0]]."'>";
            for($i=0;$i<count($cam);$i++)
                if(in_array($i,$cmp))
                    $a.= "<td class='td$i'>$o[$i]</td>";
            if(!is_null($ed))
                $a.= "<td style='text-align:center' class='td$i'><a href='?m=".$_GET['m']."&v=".$vista."&id=".$o[$ids[0]]."&itemid=".fItemId()."&e=95'><img src='nucleo/iconos/table_edit.png'</a></td>";
            if(!is_null($el))
                $a.= "<td style='text-align:center' class='td$i'><a href='?m=".$_GET['m']."&v=".$_GET['v']."&id=".$o[$ids[0]]."&itemid=".fItemId()."&e=$el'><img src='nucleo/iconos/table_delete.png'</a></td>";
            if($enlace!=null)
                $a.= "<td style='text-align:center' class='td$i'><a href='?m=".$enl[0]."&v=".$enl[1]."&id=".$o[$ids[0]]."&e=".$enl[3]."'><img src='nucleo/iconos/".$enl[2].".png'/></a></td>";
            $a.= "</tr>";
        }
    $a.= "</tbody></table>";
    
    if($paginar!=0){
        $n=$l-$paginar;
        if($n<0)
            $n=0;
        //$nombre,$modulo=null,$vista=null,$evento=0,$id=0,$itemid=0,$variables=null,$dId=null,$clase=null,$img=null,$lado=1
        $a.= fEnlace("$paginar Anteriores","","",0,0,0,"ini=$n",null,null,"arrow_left");
        $a.= "         ";
        $a.= fEnlace("Siguientes $paginar","","",0,0,0,"ini=".($l+$paginar),null,null,"arrow_right",2);
    }
    if($datos==null)
        $_SESSION['tabla']=$tabla;
    if (depuracion())
        $traza.="</div>";
    if($regis<1)
        $a="<p>No hay Registros para Mostar</p>";
    return $a;
}

function fMostrarTablasBD(){
    global $objBd;
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fMostrarTablasBD</p>";
    $objBd->fTablasBD();
    while($t=$objBd->fConsultaArreglo()){
        if (depuracion())
            $traza.= $t[0]."<br/>";
        $a[]=$t[0];
    }
    $traza.="</div>";
    return $a;
}

function fMostrarTablasRelacionadasBD($tbl=null){
    global $objBd;
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fMostrarTablasRelacionadasBD</p>";
    $objBd->fRelaciones();
    
    if($tbl!=null){
        $s.=$tbl.",";
        while($t=$objBd->fConsultaArreglo()){
            if($t[0]==$tbl){
                 if (depuracion())
                    $traza.= $t[0]."-".$t[1]."<br/>";
                $rel[]=$t[1];
                $s.=$t[1].",";
            }
            $tablas[]= $t[0];
            $relacion[]=$t[1];
        }
        $tbl=$rel[0];
        while (count($rel)!=0){
            if(in_array($tbl,$tablas)){
                $x=array_search($tbl,$tablas);
                $s.=$relacion[$x].",";
                unset($tablas[$x]);
                unset($relacion[$x]);
                $tablas=array_values($tablas);
                $relacion=array_values($relacion);
            }else{
                unset($rel[0]);
                $rel=array_values($rel);
                $tbl=$rel[0];
            }
        }
        $repa=explode(",",$s);
        $d=array();
        $s="";
        for ($i=0;$i<count($repa)-1;$i++){
            if(!in_array($repa[$i],$d)){
                $d[]=$repa[$i];
                $s.=$repa[$i].",";
            }
        }
        if (depuracion()){
            $traza.= "<p>$s</p></div>";
        }
        return $s;
    }else{
        if (depuracion()){
            while($t=$objBd->fConsultaArreglo()){
                $traza.= $t[0]."-".$t[1]."<br/>";
            }
            $traza.="</div>";
        }
        return $objBd->fConsultaArreglo();
    }
}

function fNivel(){
    global $objSes;
    return $objSes->fNivelUsuario();
}

function fNregistrosConsulta($tabla=null){
    global $objBd;
    global $datos;
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px;margin-bottom:3px;'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fNregistrosConsulta</p>";
    if($datos==null && $tabla==null){
        echo "<h1 style='color:red'>Error 202:El Argumento Tabla no esta Definido</h1>";
        return 202;
    }
    if($datos==null && $tabla!=null){
        $_SESSION['tabla']=$tabla;
        
    }
    if($datos!=null){
        $tabla=$datos;
    }
    $vari=func_get_args();
    if(func_num_args()>1){
        for($i=1;$i<func_num_args();$i++){
            $opciones=explode(";",$vari[$i]);
            if($i==func_num_args()-1){
                switch ($opciones[0]){
                    case "id":
                        $crit.=" ".$tabla.".id='".$opciones[1]."'";
                        break;
                    case "elm":
                        $crit.=" ".$tabla.".eliminado='".$opciones[1]."'";
                        break;
                    case "ord":
                        $crit=substr($crit,1,strlen($crit)-5);
                        $crit.=" ORDER BY ".$opciones[1];
                        break;
                    default:
                        $crit.=$opciones[0]."='".$opciones[1]."'";
                        break;
                }
            }else{
                switch ($opciones[0]){
                    case "id":
                        $crit.=" ".$tabla.".id='".$opciones[1]."' AND ";
                        break;
                    case "elm":
                        $crit.=" ".$tabla.".eliminado='".$opciones[1]."' AND ";
                        break;
                    default:
                        $crit.=" ".$opciones[0]."='".$opciones[1]."' AND ";
                        break;
                }
            }
        }
    }
    $objBd->fSelect($tabla,"*",$crit);
    if (depuracion())
        $traza.="</div>";
    return $objBd->fCantidadRegistros();
}

function fNregistros($tabla=null){
    global $objBd;
    global $datos;
    global $traza;
    
    $vari=func_get_args();
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px;margin-bottom:3px;'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fNregistros</p>";
    if($datos==null && $tabla==null){
        echo "<h1 style='color:red'>Error 202:El Argumento Tabla no esta Definido</h1>";
        return 202;
    }else{
        if($tabla!=null){
            $tabla=$tabla;
        }else{
            $tabla=$datos;
        }
    }
    if($vari[1]!=null)
        $objBd->fSelect($tabla,"id",$vari[1]);
    else
        $objBd->fSelect($tabla,"id");
    if (depuracion())
        $traza.="</div>";
    return $objBd->fCantidadRegistros();
}

function fNuevo($tabla=null,$rel=null,$auto=true,$campos=null,$data=null,$cap=false){
    global $objBd;
    global $datos;
    global $mascara;
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px;margin-bottom:5px;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fNuevo</p>";
    if($datos==null && $tabla==null){
        echo "<h1 style='color:red'>Error 202:El Argumento Tabla no esta Definido</h1>";
        return 202;
    }else{
        if($datos==null && $tabla!=null){
            $_SESSION['tabla']=$tabla;
        }else{
            if($datos!=null && $tabla!=null){
                $tabla=$tabla;
                 $_SESSION['tabla']=$tabla;
            }else{
                $tabla=$datos;
            }
        }
    }
    
    $i=0;
    $id=fId();
    $item=fItemId();
    if($rel!=null)
        $relaciones=explode(";",$rel);
    if($campos!=null){
        $cmp=explode(";",$campos);
    }
    if($data!=null){
        if (depuracion())
            $traza.="<p>Datos Pasados como Argumento $data</p>";
        $registro=explode(";",$data);
    }
   if($auto){
        if($id!=0 && $_SESSION['ed']==1){
             if (depuracion())
                    $traza.="<p>Buscando Registro en la BD</p>";
            $cr="$tabla.id;".$id;
            $registro=fBuscar(false,$tabla,false,$cr);
            $r="m=".$_GET['m']."&v=".$_GET['v']."&id=".$id."&itemid=".$item;
        }else{
            $id=  ($id == null) ? '0' : $id;
            $item= ($item == null) ? '0' : $item;
            $r="m=".$_GET['m']."&v=".$_GET['v']."&id=".$id."&itemid=".$item;
        }
    }
    $objBd->fDatos_tabla($tabla);
    $x=$objBd->fCantidadRegistros();
    while($a=$objBd->fConsultaArreglo())
       $t[]=$a;
    if (depuracion())
            $traza.="<p>Abriendo Formulario</p>";
    $a= fFormulario("nuevo",$r);
    if($campos!=null){
        for($i=0;$i<count($cmp);$i++){
            if (depuracion())
                 $traza.="<p>********** Apertura de Campo $i ****************</p>";
            $v=$cmp[$i];
            $c=substr($t[$v][1],0,3);
            if($mascara[$i]!=null)
                $nm=$mascara[$i];
            else
                $nm=$t[$v][0];
            $m=1;
            switch ($c){
                case "var":
                    $campoTipo="texto";
                    $tipo[]="none";
                    if (depuracion())
                        $traza.="<p>Campo de tipo varchar, valor $registro[$v]</p>";
                        break;
                case "int":
                    $campoTipo="texto";
                    $tipo[]="digits";
                    if (depuracion())
                        $traza.="<p>Campo de tipo integer, valor $registro[$v]</p>";
                    break;
                case "tex":
                    $campoTipo="area";
                    $tipo[]="none";
                    if (depuracion())
                        $traza.="<p>Campo de tipo text, valor $registro[$v]</p>";
                    break;
                case "dat":
                    $campoTipo="fecha";
                    $tipo[]="none";
                    if (depuracion())
                        $traza.="<p>Campo de tipo fecha, valor $registro[$v]</p>";
                    break;
                case "flo":
                    $campoTipo="texto";
                    $tipo[]="number";
                    if (depuracion())
                        $traza.="<p>Campo de tipo float, valor $registro[$v]</p>";
                    break;
                case "dou":
                    $campoTipo="texto";
                    $tipo[]="number";
                    if (depuracion())
                        $traza.="<p>Campo de tipo double, valor $registro[$v]</p>";
                    break;
                 case "num":
                    $campoTipo="texto";
                    $tipo[]="number";
                    if (depuracion())
                        $traza.="<p>Campo de tipo numero, valor $registro[$v]</p>";
                    break;
                case "tin":
                    $m=0;
                    if($t[$v][3]!=NULL){
                        if($rel[$v]!=NULL)
                            $a.= fSelectFormBD($t[$v][0],$nm,$t[$v][0],$relaciones[$i],$registro[$v]);
                        else
                            $a.= fCampo($t[$v][0],"",oculto,$registro[$v]);
                    }else{
                        $a.="<div class='cpoForm'><label class='etqTit'>$nm</label>".fCampo($t[$v][0],"Si",radio,1).fCampo($t[$v][0],"No",radio,2)."</div>";
                    }
                    $tipo[]="number";
                    if (depuracion())
                        $traza.="<p>Campo de tipo radio, valor $registro[$v]</p>";
                    break;
                case "boo":
                    $m=0;
                    if($t[$v][3]!=NULL){
                        if($rel[$v]!=NULL)
                            $a.= fSelectFormBD($t[$v][0],$nm,$t[$v][0],$relaciones[$i],$registro[$v]);
                        else
                            $a.= fCampo($t[$v][0],"",oculto,$registro[$v]);
                    }else{
                        $a.="<div class='cpoForm'><label class='etqTit'>$nm</label>".fCampo($t[$v][0],"Si",radio,1).fCampo($t[$v][0],"No",radio,2)."</div>";
                    }
                    $tipo[]="number";
                    if (depuracion())
                        $traza.="<p>Campo de tipo booleano, valor $registro[$v]</p>";
                    break;
            }
            if($m!=0){
                if($t[$v][3]!=NULL){
                    if($rel[$v]!=NULL)
                        $a.= fSelectFormBD($t[$v][0],$nm,$t[$v][0],$relaciones[$i],$registro[$v]);
                    else
                        $a.= fCampo($t[$v][0],"",oculto,$registro[$v]);
                }else{
                    $a.= fCampo($t[$v][0],$nm,$campoTipo,$registro[$v]);
                }
            }
            if($t[$v][2]=="NO")
                $valido[]=$t[$v][0];
            if (depuracion())
                $traza.="<p>********** Cierre de Campo $i ****************</p>";
        }
    }else{
        for($v=0;$v<$x;$v++){
            $i++;
            if($i>$x-4)
                break;
            $c=substr($t[$v][1],0,3);
            if($mascara[$v]!=null)
                $nm=$mascara[$v];
            else
                $nm=$t[$v][0];
            $m=1;
            switch ($c){
                case "var":
                    $campoTipo="texto";
                    $tipo[]="none";
                    if (depuracion())
                        $traza.="<p>Campo de tipo varchar, valor $registro[$i]</p>";
                        break;
                case "int":
                    $campoTipo="texto";
                    $tipo[]="digits";
                    if (depuracion())
                        $traza.="<p>Campo de tipo integer, valor $registro[$i]</p>";
                    break;
                case "tex":
                    $campoTipo="area";
                    $tipo[]="none";
                    if (depuracion())
                        $traza.="<p>Campo de tipo text, valor $registro[$i]</p>";
                    break;
                case "dat":
                    $campoTipo="fecha";
                    $tipo[]="none";
                    if (depuracion())
                        $traza.="<p>Campo de tipo fecha, valor $registro[$i]</p>";
                    break;
                case "flo":
                    $campoTipo="texto";
                    $tipo[]="number";
                    if (depuracion())
                        $traza.="<p>Campo de tipo float, valor $registro[$i]</p>";
                    break;
                case "dou":
                    $campoTipo="texto";
                    $tipo[]="number";
                    if (depuracion())
                        $traza.="<p>Campo de tipo double, valor $registro[$i]</p>";
                    break;
                 case "num":
                    $campoTipo="texto";
                    $tipo[]="number";
                    if (depuracion())
                        $traza.="<p>Campo de tipo numero, valor $registro[$i]</p>";
                    break;
                case "tin":
                    $m=0;
                    if($t[$v][3]!=NULL){
                        if($rel[$v]!=NULL)
                            $a.= fSelectFormBD($t[$v][0],$nm,$t[$v][0],$relaciones[$i],$registro[$v]);
                        else
                            $a.= fCampo($t[$v][0],"",oculto,$registro[$v]);
                    }else{
                        $a.="<div class='cpoForm'><label class='etqTit'>$nm</label>".fCampo($t[$v][0],"Si",radio,1).fCampo($t[$v][0],"No",radio,2)."</div>";
                    }
                    $tipo[]="number";
                    if (depuracion())
                        $traza.="<p>Campo de tipo radio, valor $registro[$v]</p>";
                    break;
                case "boo":
                    $m=0;
                    if($t[$v][3]!=NULL){
                        if($rel[$v]!=NULL)
                            $a.= fSelectFormBD($t[$v][0],$nm,$t[$v][0],$relaciones[$i],$registro[$v]);
                        else
                            $a.= fCampo($t[$v][0],"",oculto,$registro[$v]);
                    }else{
                        $a.="<div class='cpoForm'><label class='etqTit'>$nm</label>".fCampo($t[$v][0],"Si",radio,1).fCampo($t[$v][0],"No",radio,2)."</div>";
                    }
                    $tipo[]="number";
                    if (depuracion())
                        $traza.="<p>Campo de tipo booleano, valor $registro[$v]</p>";
                    break;
            }
            if($m!=0){
                if($t[$v][3]!=NULL){
                    if($rel[$v]!=NULL)
                        $a.= fSelectFormBD($t[$v][0],$nm,$t[$v][0],$relaciones[$v],$registro[$v]);
                    else
                        $a.= fCampo($t[$v][0],"",oculto,$registro[$v]);
                }else{
                    $a.= fCampo($t[$v][0],$nm,$campoTipo,$registro[$v]);
                }
            }
            if($t[$v][2]=="NO")
                $valido[]=$t[$v][0];
        }
    }
    if($cap==true){
        $a.=fCaptcha();
        $valido[]="capt";
        $tipo[]="none";
    }
    $a.= fCampo("boton","Guardar","submit",null,0,boton1);
    $a.= fFinFormulario();
    if (depuracion()){
        for($i=0;$i<count($valido);$i++)
            $traza.="<p>$valido[$i] = $tipo[$i]</p>";
    }
    $a.= fValidar_CamposAutomatico($valido,$tipo);
    if (depuracion())
        $traza.="</div>";
    $_SESSION['gr']=1;
    return $a;
}

function fProbarConexionServidor(){
    global $objBd;
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fProbarConexionServdiro</p>";
    $a= $objBd->fConectar();
    $objBd->fCerrar();
    if (depuracion()){
        $traza.="<p> $a (0:Exito,1:Error en Conexion,2:Base de datos no Seleccionada)</p></div>";
    }
    return $a;
}

function fRedimensionImagen($archivo,$extension,$ancho,$largo){
    $extension = strtolower($extension);
    $imagen=$archivo.".".$extension;
    if($extension=="jpg" || $extension=="jpeg" ){ 
        $src = imagecreatefromjpeg($imagen); 
    } 
    else if($extension=="png"){ 
        $src = imagecreatefrompng($imagen); 
    }else{ 
        $src = imagecreatefromgif($imagen); 
    } 
    $tmp=imagecreatetruecolor($ancho,$largo); 
    list($ancho_s,$alto_s)=getimagesize($imagen); 
    $back = imagecolorallocate($tmp, 255, 255, 255);
    imagefill($tmp, 0, 0, $back);
    imagecopyresampled($tmp,$src,0,0,0,0,$ancho,$largo,$ancho_s,$alto_s); 
    if($extension=="jpg" || $extension=="jpeg" ){ 
        imagejpeg($tmp,$imagen, 100); 
    }else if($extension=="png"){ 
        imagePNG($tmp, $imagen, 0); 
    }else{ 
        imageGIF($tmp, $imagen, 100); 
    } 
    imagedestroy($src); 
    imagedestroy($tmp); 
}

function fSalir($texto="Salir del Sistema",$imagen='nucleo/iconos/door_in.png'){
    return "<a href='?m&v=salirV'><img src='$imagen' alt='$img'/> $texto</a>"  ;
}

function fSubirArchivo($tipos,$ext,$nombreArchivo=null,$ruta=null){
    global $traza;
    if (depuracion()){
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fSubirArchivos</p><p> Tipos Permitidos:";
        for($i=0;$i<count($tipos);$i++)
            $traza.="$tipos[$i],";
        $traza.="</p>";
    }
     $permitidos = $tipos;
     $tmp=$_FILES['arc']['tmp_name'];$non=$_FILES['arc']['name'];$tam=$_FILES['arc']['size'];$tip=$_FILES['arc']['type'];
     
     if (depuracion())
        $traza.="<p>Información del Archivo: Nombre Temporal= $tmp - Nombre Real= $non - Tamaño $tam - Tipo= $tip</p>";

     if (is_uploaded_file($tmp)) {
        if($non==null || $tam==0){
            if (depuracion())
                $traza.="<p style='color:#FFA500;font-weight: bold'>No se Cargo el Archivo</p></div>";
            return 1; //no se cargo el archivo
        }else{
             if (!in_array($tip, $permitidos)) {
                    if (depuracion())
                        $traza.="<p style='color:#FFA500;font-weight: bold'>Tipo de Archivo no Permitido</p></div>";
                    return 2; //tipo de archivo no permitido
            }else{
                  if ($nombreArchivo==null){
                    $n=$nom.".".$ext;
                  }else{
                    $n=$nombreArchivo.".".$ext;
                  }
                  if (depuracion())
                        $traza.="<p>NUevo Nombre archivo $n</p>";
                 
                 if ($ruta!=null)
                    $rut="conf/datos/".$ruta."/".$n;
                  else
                    $rut="conf/datos/".$n;
                 
                 if (move_uploaded_file($tmp,$rut)) {
                    if (depuracion())
                        $traza.="<p style='color:#316308;font-weight: bold'>Operación Exitosa</p></div>";
                    return 0; //exito logrado al subir el archivo
                 }else{
                     if (depuracion())
                        $traza.="<p style='color:#FFA500;font-weight: bold'>No se logro MOver el Archivo a su Destino $rut</p></div>";
                     return 3; //No se pudo mover el archivo a su destino
                 }
            }
        }
     }else{
         if (depuracion())
            $traza.="<p style='color:#FFA500;font-weight: bold'>Error al Subir el Archivo</p></div>";
        return 4; //No se pudo subir el archivo
    }
}

function fSubirImagen($ruta=null,$nombreImagen=null,$exte=null){
    global $traza;
    $permitidos = array("image/bmp","image/gif","image/jpeg","image/pjpeg","image/png","image/x-png");
    if (depuracion()){
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fSubirArchivos</p><p> Tipos Permitidos:";
        for($i=0;$i<count($permitidos);$i++)
            $traza.=$permitidos[$i].",";
        $traza.="</p>";
    }
     $tmp=$_FILES['arc']['tmp_name'];$non=$_FILES['arc']['name'];$tam=$_FILES['arc']['size'];$tip=$_FILES['arc']['type'];
     if (depuracion())
        $traza.="<p>Información del Archivo: Nombre Temporal= $tmp - Nombre Real= $non - Tamaño $tam - Tipo= $tip</p>";
     
     if (is_uploaded_file($tmp)) {
        if($non==null || $tam==0){
            if (depuracion())
                $traza.="<p style='color:#FFA500;font-weight: bold'>No se Cargo el Archivo</p></div>";
            return 1; //no se cargo el archivo
        }else{
             if (!in_array($tip, $permitidos)) {
                    if (depuracion())
                        $traza.="<p style='color:#FFA500;font-weight: bold'>Tipo de Archivo no Permitido</p></div>";
                    return 2; //tipo de archivo no permitido
            }else{
                  switch($tip){
                    case "image/bmp":
                        $ext="bmp";
                        break;
                    case "image/jpeg":
                        $ext="jpg";
                        break;
                    case "image/png":
                        $ext="png";
                        break;
                    case "image/gif":
                        $ext="gif";
                        break;
                  }
                  if ($nombreImagen==null){
                        $n=$non;
                  }else{
                      if($exte==null)
                        $n=$nombreImagen.".".$ext;
                    else
                        $n=$nombreImagen.".".$exte;
                  }
                 $rut="conf/datos/".$ruta."/".$n;
                 if (move_uploaded_file($tmp,$rut)) {
                    if (depuracion())
                        $traza.="<p style='color:#316308;font-weight: bold'>Operación Exitosa</p></div>";
                    return 0; //exito logrado al subir el archivo
                 }else{
                     if (depuracion())
                        $traza.="<p style='color:#FFA500;font-weight: bold'>No se logro MOver el Archivo a su Destino $rut</p></div>";
                     return 3; //No se pudo mover el archivo a su destino
                 }
            }
        }
     }else{
         if (depuracion())
            $traza.="<p style='color:#FFA500;font-weight: bold'>Error al Subir el Archivo</p></div>";
        return 4; //No se pudo subir el archivo
    }
}

function fUltimoRegistro($tabla=null,$msg=true,$limite=1){
    global $objBd;
    global $datos;
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fUltimoRegistro</p>";

    if($tabla==null){
        if($datos==null){
            if($msg)
                echo "<h1 style='color:red'>Error 202:El Argumento Tabla no esta Definido</h1>";
            return 202;
        }else{
            $tabla=$datos;
        }
    }
    $vari=func_get_args();
    if(func_num_args()>2){
        for($i=3;$i<func_num_args();$i++){
            $opciones=explode(";",$vari[$i]);
            if($i==func_num_args()-1){
                switch ($opciones[0]){
                    case "id":
                        $crit.=" ".$tabla.".id='".$opciones[1]."'";
                        break;
                    case "elm":
                        $crit.=" ".$tabla.".eliminado='".$opciones[1]."'";
                        break;
                    case "ord":
                        $crit=substr($crit,1,strlen($crit)-5);
                        $crit.=" ORDER BY ".$opciones[1];
                        break;
                    default:
                        $crit.=$opciones[0]."='".$opciones[1]."'";
                        break;
                }
            }else{
                switch ($opciones[0]){
                    case "id":
                        $crit.=" ".$tabla.".id='".$opciones[1]."' AND ";
                        break;
                    case "elm":
                        $crit.=" ".$tabla.".eliminado='".$opciones[1]."' AND ";
                        break;
                    default:
                        $crit.=" ".$opciones[0]."='".$opciones[1]."' AND ";
                        break;
                }
            }
        }
    }else{
        $crit="";
    }
    $c= fMostrarRelaciones($tabla);
    $c=trim($c," AND ");
    if($crit==""){
        $crit=$c;
    }else{
        if($c!="")
            $crit=$c." AND ".$crit;
        else
            $crit=$crit;
    }
        $com=fMostrarTablasRelacionadasBD($tabla);
    $ta=trim($com,",");
    if($crit==""){
        $f= $objBd->fSelect($ta,null,null,"$tabla.id DESC LIMIT $limite");
    }else{
        $f=$objBd->fSelect($ta,"*",$crit,"$tabla.id DESC LIMIT $limite");
    }
    if($f==null){
        echo "Error de Mysql: Fallo en la Consulta SQL";
        if(depuracion())
            $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR MYSQL: la Consulta ha Fallasdo</p>";
        return -1;
    }
    if (depuracion()){
        $cc=$objBd->fCantidadCampos();
        $cr=$objBd->fCantidadRegistros();
        $traza.="<table><tr>";
        for($i=0;$i<$cc;$i++){
            $traza.="<th>$i</th>";
        }
        $traza.="</tr>";
    }
    if($limite>1){
        while($a=$objBd->fConsultaArreglo()){
            $t[]=$a;
            if (depuracion()){
                $traza.="<tr>";
                for($i=0;$i<$cc;$i++){
                    $traza.="<td>$a[$i]</td>";
                }
                $traza.="</tr>";
            }
        }
    }else{
        $t=$objBd->fConsultaArreglo();
        if (depuracion()){
                $traza.="<tr>";
                for($i=0;$i<$cc;$i++){
                    $traza.="<td>$t[$i]</td>";
                }
                $traza.="</tr>";
        }
    }
    if (depuracion())
        $traza.="</table></div>";
    return $t;
}

function fUsuario(){
    global $objSes;
    return $objSes->fUsuario();
}

function fUsuarioID(){
    global $objSes;
    return $objSes->fLogeado();
}

function fValidar(){
    global $objSes;
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fValidar</p>";
    if(!empty($_POST['usur'])  or !empty($_POST['clav'])){
        $usuario=addslashes($_POST['usur']);
        $clave=addslashes($_POST['clav']);
        $d=$objSes->fValidar($usuario,md5($clave));
        if($d==0){
            flog("Se conecto el USUARIO ".fUsuarioID());
            fRedir("./?m=".$_GET['m']."&v=".$_GET['v']."&id=".$_GET['id']."&itemid=".$_GET['itemid']."&e=".$_GET['e']);
        }else{
            flog("Intento Fallido de Acceso al Sistema");
            echo "<div class='respuesta' id='rsp'>Datos Incorrectos</div>";
            echo fEfectosInteractivos(usur,focus,rsp,1,5);
        }
    }
    if (depuracion())
        $traza.="</div>";
}

function fVerificaFunciones($archivo,$nombre){
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fVerificaArchivo</p>";
    switch($archivo){
        case 1:
            $content = file_get_contents("conf/config.php");
            preg_match_all("/(function )(\S*\(\S*\))/", $content, $matches);
            foreach($matches[2] as $match) {
                $function[] = trim($match);
            }
            if(count($function)>0){
                echo "<h1 style='color:red'>Error 006:No Se Esperaba 1 función en el archivo</h1>";
                return 6;
            }else{
                if (depuracion())
                    $traza.="<p style='font-weight: bold'>Archivo Config Ok</p></div>";
                return 0;
            }
            break;
        case 2:
            $content = file_get_contents("app/modelos/M$nombre.php");
            preg_match_all("/(function )(\S*\(\S*\))/", $content, $matches);
            foreach($matches[2] as $match) {
                $function[] = trim($match);
            }
            if(count($function)>0){
                echo "<h1 style='color:red'>Error 008: Imposible declarar funciones en este archivo M$nombre.php</h1>";
                return 8;
            }else{
                if (depuracion())
                        $traza.="<p style='font-weight: bold'>Archivo M$nombre Ok</p></div>";
                return 0;
            }
            break;
        case 3:
            $content = file_get_contents("app/vistas/V$nombre.php");
            preg_match_all("/(function )(\S*\(\S*\))/", $content, $matches);
            foreach($matches[2] as $match) {
                $function[] = trim($match);
            }
            if(count($function)==0){
                echo "<h1 style='color:red'>Error 009: El archivo no contiene funciones validas V$nombre.php</h1>";
                return 9;
            }else{
                if (strcasecmp( $function[0],"index()")!=0){
                    echo "<h1 style='color:red'>Error 004:La funcion index debe ser declara al principio de la Vista</h1>";
                    return 4;
                }else{
                    for($i=1;$i<count($function);$i++){
                        $prefijo=substr($function[$i],0,2);
                        if (strcasecmp($prefijo,"A_")!=0){
                            if (strcasecmp($prefijo,"X_")!=0){
                                if (strcasecmp($prefijo,"L_")!=0){
                                    if (strcasecmp($prefijo,"P_")!=0){
                                         if (strcasecmp($prefijo,"D_")!=0){
                                            echo "<h1 style='color:red'>Error 010:La funcion $function[$i] no se reconoce en las vistas</h1>";
                                            return 10;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (depuracion())
                        $traza.="<p style='font-weight: bold'>Archivo V$nombre Ok</p></div>";
                    return 0;
                }
            }
            break;
        case 4:
            $content = file_get_contents("app/controles/C$nombre.php");
            preg_match_all("/(function )(\S*\(\S*\))/", $content, $matches);
            foreach($matches[2] as $match) {
                $function[] = trim($match);
            }
            if (strcasecmp( $function[0],"index()")==0){
                  echo "<h1 style='color:red'>Error 011:La funcion index debe ser declara al principio de la Vista NO en el Control</h1>";
                  return 11;
             }else{
                for($i=1;$i<count($function);$i++){
                    $prefijo=substr($function[$i],0,2);
                    if (strcasecmp($prefijo,"A_")==0 || (strcasecmp($prefijo,"X_")==0) || (strcasecmp($prefijo,"L_")==0) || (strcasecmp($prefijo,"P_")==0)){
                        echo "<h1 style='color:red'>Error 012:La funcion $function[$i] debe ser declarada en las vistas</h1>";
                        return 12;
                    }
                  }
               }
               if (depuracion())
                    $traza.="<p style='font-weight: bold'>Archivo C$nombre Ok</p></div>";
               return 0;
            break;
    }
}

function fVerificaRegistro($tabla=null,$msg=true){
    global $datos;
    global $objBd;
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fVerificaRegistro</p>";
    
    if($tabla==null){
        if($datos==null){
            if($msg)
                echo "<h1 style='color:red'>Error 202:El Argumento Tabla no esta Definido</h1>";
            return 202;
        }else{
            $tabla=$datos;
        }
    }
    $vari=func_get_args();
        if(func_num_args()>2){
            for($i=2;$i<func_num_args();$i++){
                $opciones=explode(";",$vari[$i]);
                if($i==func_num_args()-1){
                    switch ($opciones[0]){
                        case "id":
                            $crit.=" ".$tabla.".id='".$opciones[1]."'";
                            break;
                        case "elm":
                            $crit.=" ".$tabla.".eliminado='".$opciones[1]."'";
                            break;
                        case "ord":
                            $crit=substr($crit,1,strlen($crit)-5);
                            $crit.=" ORDER BY ".$opciones[1];
                            break;
                        default:
                            $crit.=$opciones[0]."='".$opciones[1]."'";
                            break;
                    }
                }else{
                    switch ($opciones[0]){
                        case "id":
                            $crit.=" ".$tabla.".id='".$opciones[1]."' AND ";
                            break;
                        case "elm":
                            $crit.=" ".$tabla.".eliminado='".$opciones[1]."' AND ";
                            break;
                        default:
                            $crit.=" ".$opciones[0]."='".$opciones[1]."' AND ";
                            break;
                    }
                }
            }
        }
    $f=$objBd->fSelect($tabla,"*",$crit);
        if($f==null){
            echo "Error de Consulta: Fallo en la Consulta a la BD";
            if(depuracion())
            $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR MYSQL: la Consulta ha Fallasdo</p>";
            return -1;
        }else{
            $cr=$objBd->fCantidadRegistros();
            if($cr>0){
                if (depuracion()){
                    $cc=$objBd->fCantidadCampos();
                    $t=$objBd->fConsultaArreglo();
                    $traza.="<table><tr>";
                    for($i=0;$i<$cc;$i++){
                        $traza.="<th>$i</th>";
                    }
                    $traza.="</tr>";
                    $traza.="<tr>";
                    for($i=0;$i<$cc;$i++){
                        $traza.="<td>$t[$i]</td>";
                    }
                    $traza.="</tr></table></div>";
                }
                return 0;
            }else{
                return 1;
            }
        }
}



function fVerificaTabla($tabla=null,$msg=true){
    global $objBd;
    global $datos;
    global $traza;
    
    if($tabla==null){
        if($datos==null){
            if($msg)
                echo "<h1 style='color:red'>Error 202:El Argumento Tabla no esta Definido</h1>";
            return 202;
        }else{
            $tabla=$datos;
        }
    }
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModCon: LLamado a la funcion fVerificaTabla, Verificando a $tabla</p>";
    $objBd->fDatos_tabla($tabla);
     while($a=$objBd->fConsultaArreglo()){
            $t[]=$a;
    }
    $x=$objBd->fCantidadRegistros();
    $campos=array(array("id","int","NO","PRI"),array("eliminado","int","NO"),array("modificado","dat","NO"),array("creado","dat","NO"));
    $tipos=array("var","int","tex","dat","flo","dou","num","boo","tin");
    if($objBd->fDatos_tabla($tabla)!=-1){
        for($i=0;$i<$x;$i++){
            $ind=$x-($i+1);
            if (depuracion())
                $traza.="<p>Verificando Campo ".$t[$ind][0].": ";
            if($i>3){
                if(!in_array(substr($t[$ind][1],0,3),$tipos)){
                    if (depuracion())
                        $traza.="El Campo no es del tipo admitido por el sistema</p></div>";
                    return 103;
                }
                if (depuracion())
                    $traza.="Ok</p>";
                continue;
            }
            if($t[$ind][0]!=$campos[$i][0]){
                if (depuracion())
                    $traza.="El Campo no Existe</p></div>";
                return 104;
            }
            if(substr($t[$ind][1],0,3)!=$campos[$i][1]){
                if (depuracion())
                    $traza.="El Campo no es tipo $campos[$i][1]</p></div>";
                return 103;
            }
            if($t[$ind][2]!=$campos[$i][2]){
                if (depuracion())
                    $traza.="El Campo No Puede Contener valores Nulos</p></div>";
                return 108;
            }
            if($i==0){
                if($t[$ind][3]!=$campos[$i][3]){
                    if(strcmp(substr($t[$ind][3],-4),'pkey')!=0){
                        if (depuracion())
                            $traza.="El Campo debe ser Clave Primaria</p></div>";
                        return 102;
                    }
                }
            }
        if (depuracion())
            $traza.="Ok</p>";
        }
    }else{
        if (depuracion())
            $traza.="<p>La Tabla del Modelo no Existe</p></div>";
        if($msg)
            echo "<h1 style='color:red'>Error 101:La Tabla Indicada en el Modelo No Existe</h1>";
            return 101;
    }
    if (depuracion())
        $traza.="</div>";
    return 0;
}

function vistas($defecto=0){
    global $objSes;
    
    if($_SESSION['gr']==1){
        fGuardar(null,0);
    }
   
    if($_SESSION['el']==1)
        fEliminar();

    if($_GET['e']==95){
        $_SESSION['ed']=1;
    }else{
        $_SESSION['ed']=0;
    }
    $fun=$_GET['v'];
    if($fun!=null){
        if(!$objSes->fProtegido()){
            $fun="P_".$fun;
        }else{
            $fun="L_".$fun;
        }
        if(function_exists($fun)){
            echo eval($fun());
            flog("Acceso a la vista $fun del Modulo ".$_GET['m']);
        }else{
            $fun="D_".$_GET['v'];
            if(function_exists($fun)){
                echo eval($fun());
                flog("Acceso a la vista $fun del Modulo ".$_GET['m']);
            }else{
                flog("Acceso a la vista $fun pero NO existe en el Modulo ".$_GET['m']);
                echo "<h1 style='color:red'>Error 008:La vista Solicitada No existe</h1>";
            }
        }
    }else{
        if($defecto==0){
            flog("Acceso a la vista index del Modulo ".$_GET['m']);
            index();
        }
    }
   echo $_SESSION['gr'];
}

function vistas_auxiliares($vista,$auxiliar){
    if($_GET['v']==$vista){
        $fnc="X_".$auxiliar;
        if(function_exists($fnc)){
            echo eval($fnc());
        }
    }
}

function flog($txt){
    $usu=(fUsuarioID()==0?"Usuario Anonimo":fUsuarioID());
    $txt="{".$usu."}{".$txt."}{".fHoy("Y/m/d H:i:s")."}{".$_SERVER['REMOTE_ADDR']."}{".$_SERVER['HTTP_USER_AGENT']."}\n";
    $archivo=fFechaMysql(fHoy())."log";
    $p=substr(sprintf('%o', fileperms('nucleo/.log/'.$archivo.'.log')), -4);
    if($p!=0333){
        chmod('nucleo/.log/'.$archivo.'.log',0333);
    }
    $fp = fopen('nucleo/.log/'.$archivo.'.log', 'a');
    fwrite($fp, $txt);
    fclose($fp);
}
?>
