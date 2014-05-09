<?php 
/*
  fncVarias.php: Funciones Varias para el Marco de Trabajo
       
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
 function fRedir($url,$tie=1){
    print "<META HTTP-EQUIV=Refresh CONTENT=$tie;URL=$url>";
}
function fFechaNormal($fecha){
    $lafecha=preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $fecha);
    return $lafecha;
} 
function fFechaMysql($fecha){
    $lafecha=preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $fecha);
    return $lafecha;
} 
function fClaveAleatoria($long = 5) {
    $salt = 'abchefghknpqrstuvwxyzACDEFHKNPRSTUVWXYZ0123456789';
    if (strlen($salt) == 0) {
    return '';
    }
    $i = 0;
    $str = '';
    srand((double)microtime()*1000000);
    while ($i < $long) {
    $num = rand(0, strlen($salt)-1);
    $str .= substr($salt, $num, 1);
    $i++;
    }
    return $str;
}
function fHoy($formato=null){
    date_default_timezone_set("America/Caracas");
    if ($formato==null)
        $fecha=date("d/m/Y");
    else
        $fecha=date($formato);
    return $fecha;
}
function fFechaActualMysql(){
        date_default_timezone_set("America/Caracas");
        $fecha=date("Y-m-d");
        return $fecha;
}
function depuracion(){
    global $dep;
    if ($dep==1)
        return true;
    return false;
}

function fCreditos(){
    $cont='<img src="nucleo/iconos/logo.gif" width="20%" height="25%" style="float:left"/>'.fTitulo(1,"Achitun").fParrafo("Es una palabra en idioma Pemon que significa 'Viento' o 'Aire' y presisamente este Marco de Desarrollo permite ser Libre como el Aire ('quien puede atrapar el Aire?, No puedes comprar el Viento!!!); La intención es proporcinar un medio agil para el desarrollo de aplicaciones web de forma rapida y funcional",null,null,"background-color:#ffffff;text-align:justify;border:1px solid #000000;padding: 5px 5px 5px 5px").fDiv(panel,null,"width:400px;height:100px;text-align:center;padding: 25px 25px 25px 25px").fParrafo("Desarrollado por:<br/> Miguel Hernandez Liebano<br/>mhliebano@gmail.com",null,null,"width:320px;height:120px;margin-left:45px;font-weight:bolder").fParrafo("Librerias Usadas:<br/> jquery-1.9.1",null,null,"width:320px;height:120px;margin-left:45px;font-weight:bolder").fParrafo("Librerias Usadas:<br/> jquery.validate.min",null,null,"width:320px;height:120px;margin-left:45px;font-weight:bolder").fParrafo("Librerias Usadas:<br/> jquery-ui",null,null,"width:320px;height:120px;margin-left:45px;font-weight:bolder").fParrafo("Librerias Usadas:<br/> tablefilter.js",null,null,"width:320px;height:120px;margin-left:45px;font-weight:bolder").fParrafo("Librerias Usadas:<br/> cycle.all.js",null,null,"width:320px;height:120px;margin-left:45px;font-weight:bolder").fParrafo("Laboratorio de Contenidos Digitales:<br/> Area de Ingeieria de Sistemas<br/> Universidad  Nacional Experimental <br/>Romulo Gallegos",null,null,"width:320px;height:120px;margin-left:45px;font-weight:bolder").fFinDiv().fEfectosAutomaticos(panel,11,300,4000);
    $a=fIcono(application_go,null,null,imgn).fDialogo(acerca,"Acerca de Achitun",$cont,480,370,imgn,click,0,0);
    return $a;
    
}
?>