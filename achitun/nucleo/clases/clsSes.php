<?php
/*
  clsSes.php: Clase que gestiona las Sesiones en el Marco de Trabajo
       
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
class sesion{
    
function fValidar($usuario,$clave){
    global $objBd;
    $objBd->fSelect("info_admin","*","usuario='$usuario' AND clave='$clave' AND eliminado=0");
    if ($objBd->fCantidadRegistros()==1){
        $dat=$objBd->fConsultaArreglo();
        $_SESSION["id_usuario"]=$dat['id'];
        $_SESSION["usuario"]=$dat['nombres']." ".$dat['apellidos'];
        $_SESSION["autorizado"]=$dat['nivel'];
        $sesi=session_id();
        date_default_timezone_set("America/Caracas");
        $fecha= date("Y-m-d");
        $objBd->fUpdate("info_admin","sesion='$sesi',modificado='$fecha',logeado=1","id=".$dat['id']);
        $_SESSION["protegido"]=1;
        return 0;
    }else{
        $_SESSION["protegido"]=0;
        return 1;
    }
}
function fSalir(){
    global $objBd;
    $objBd->fUpdate("info_admin","sesion=0,logeado=0","id=".$this->fLogeado());
    $_SESSION["protegido"]=0;
    $_SESSION["id_usuario"]=0;
    session_destroy();
}
function fLogeado(){
    return $_SESSION["id_usuario"];
}
function fUsuario(){
    return $_SESSION["usuario"];
}
function fNivelUsuario(){
    return $_SESSION["autorizado"];
    echo $_SESSION["autorizado"];
}
function fSesion(){
    return session_id();
}
function fProtegido(){
    if ($_SESSION["protegido"]==0){
        return false;
    }else{
        return true;
    }
}
function fVerificar_logeo_previo(){
        global $objBd;
        $objBd->fSelect("info_admin","sesion","id=".$this->fLogeado());
        $datos=$objBd->fConsultaArreglo();
        $logi=$datos[0];
        if ($logi==0){
            return 0;
        }else{
            return 1;
        }
    }
    
function fVerificar_sesionActual(){
        global $objBd;
        $objBd->fSelect("info_admin","sesion","id=".$this->fLogeado());
        $datos=$objBd->fConsultaArreglo();
        $logi=$datos[0];
        if ($logi==$this->fSesion()){
            return 0;
        }else{
            return 1;
        }
}
    
function fCambio_clave($clave){
        global $objBd;
        $clave=md5($clave);
        if(!$objBd->fUpdate("info_admin","clave='$clave'","id=".$this->fLogeado()))
            return 1;
        else
            return 0;
    }
    
function fNuevaClave(){
        $salt = 'abchefghknpqrstuvwxyzACDEFHKNPRSTUVWXYZ0123456789';
        $i = 0;
        $str = '';
        srand((double)microtime()*1000000);
        while ($i < 5) {
            $num = rand(0, strlen($salt)-1);
            $str .= substr($salt, $num, 1);
            $i++;
        }
        return $str;
    }

    
}
global $objSes;
$objSes=new sesion();
?>