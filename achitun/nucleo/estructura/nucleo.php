<?php
    /*
  nucleo.php: Archivo Ensamble del todo el Marco de Trabajo
       
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
    $inicio=microtime();	 
    global $dep;
    include_once "nucleo/clases/clsSes.php";
    include_once "nucleo/clases/clsBd.php";
    include_once "nucleo/clases/fncVarias.php";
    include_once "nucleo/clases/modHTML.php";
    include_once "nucleo/clases/modJSCRIPT.php";
    include_once "nucleo/clases/modCONTROLES.php";
    if (file_exists("conf/config.php")){
        if($dep==1){
            if(fVerificaFunciones(1,null)==0){
                include_once "conf/config.php";
                global $ns;
                session_name($ns);
                session_start();
            }else{
                return;
            }
        }else{
            include_once "conf/config.php";
            global $ns;
            session_name($ns);
            session_start();
        }
    }else{
        echo "<h1 style='color:red'>Error 005:No existe el archivo config.php dentro del directorio conf</h1>";
        return;
    }
    
    if($_GET["m"]!=null){
        $app=$_GET["m"];
    }else{
        if($inicio!=null)
            $app=$inicio;
        else
            $app=index;
    }
     
    if (file_exists("app/modelos/M$app.php")){
        if($dep==1){
            if(fVerificaFunciones(2,$app)==0)
                include "app/modelos/M$app.php";
            else
                return;
        }else{
            include "app/modelos/M$app.php";
        }
    }else{
        echo "<h1 style='color:red'>Error 001:No existe un modelo Asociado para acceder</h1>";
        return;
    }
    
    if (file_exists("app/vistas/V$app.php")){
        if($dep==1){
            if(fVerificaFunciones(3,$app)==0)
                include "app/vistas/V$app.php";
            else
                return;
        }else{
            include "app/vistas/V$app.php";
        }
    }else{
        echo "<h1 style='color:red'>Error 002:No existe una Vista Asociado para acceder</h1>";
        return;
    }

    if (file_exists("app/controles/C$app.php")){
        if($dep==1){
            if(fVerificaFunciones(2,$app)==0){
                include "app/controles/C$app.php";
            }else{
                return;
            }
        }else{
            include "app/controles/C$app.php";
        }
    }else{
        echo "<h1 style='color:red'>Error 003:No existe un Controlador Asociado para acceder</h1>";
        return;
    }
    if($ctrlIndex)
        include_once "app/controles/Cindex.php";
    $objBd->fConectar();
    if($datos!=null &&  $dep==1){
        switch(fVerificaTabla($datos)){
            case 101:
                echo "<h1 style='color:red'>Error 101:No existe la Tabla Indicada en el Modelo</h1>";
                return;
                break;
            case 103:
                echo "<h1 style='color:red'>Error 103:El Campo no es del tipo admitido por el sistema</h1>";
                return;
                break;
            case 104:
                echo "<h1 style='color:red'>Error 104:La estructura de la es Incorrecta</h1>";
                return;
                break;
            case 108:
                echo "<h1 style='color:red'>Error 108:Los Campos de la estructura base no pueden contener valores nulos</h1>";
                return;
                break;
            case 102:
                echo "<h1 style='color:red'>Error 102:No existe una clave primaria</h1>";
                return;
                break;
        }
    }
    
    if($_GET['ajax']!=null){
        $fun="A_".$_GET['ajax'];
       if(function_exists($fun)){
            echo eval($fun());
        }else{
            echo "<h1 style='color:red'>Error 008:La vista Solicitada No existe</h1>";
        }
        return;
    }
    if($_GET['down']!=null){
        fForzaDescarga();
    }
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es"><head><title>';
echo $titulo."::".$titulo_modelo;
echo '</title><link rel="Shortcut Icon" href="conf/img/logo.ico" type="image/x-icon" />
 <meta http-equiv="content-type" content="text/html;charset=utf-8" />
 <meta name="generator" content="Geany 0.19.1" />
 <script src="nucleo/js/jquery-1.9.1.js" type="text/javascript"></script>    
 <script src="nucleo/js/jquery-ui.js" type="text/javascript"></script>    
 <script src="nucleo/js/jquery.validate.min.js" type="text/javascript"></script>    
 <script src="nucleo/js/jquery-anexos.js" type="text/javascript"></script>    
 <script src="nucleo/js/jQuery.tablefilter.js" type="text/javascript"></script>    
 <script src="nucleo/js/jquery.cycle.all.js" type="text/javascript"></script>    
 <script src="nucleo/js/jquery.tablesorter.min.js" type="text/javascript"></script>
 <script type="text/javascript" src="nucleo/js/nicEdit.js"></script>';
 /*echo '<style> #pre-load-web {width:100%;position:absolute;background:#EDEDED;left:0px;top:0px;z-index:100000} #pre-load-web #imagen-load{left:50%;margin-left:-30px;position:absolute}     </style>';
        echo "<script>".fpreCarga()."</script>";*/
        if(count($estilo)==0){
            echo '<link href="nucleo/estilos/index.css" rel="stylesheet" type="text/css"/>';
        }else{
            for($i=0;$i<count($estilo);$i++)
                echo '<link href="conf/estilos/'.$estilo[$i].'.css" rel="stylesheet" type="text/css"/>';
        }
       
        if(count($estilo_modelo)!=0){
            for($i=0;$i<count($estilo_modelo);$i++)
                echo '<link href="conf/estilos/'.$estilo_modelo[$i].'.css" rel="stylesheet" type="text/css"/>';
        }
        
        if(count($script)!=0){
            for($i=0;$i<count($script);$i++)
                echo '<script src="conf/jscript/'.$script[$i].'.js" type="text/javascript"></script>';
        }
       
        if(count($script_modelo)!=0){
            for($i=0;$i<count($script_modelo);$i++)
                echo '<script src="conf/jscript/'.$script_modelo[$i].'.js" type="text/javascript"></script>';
        }
echo '</head><body><noscript><p style="position:absolute;width:100%;height:150%;background:black;color:red;text-align:center">POR FAVOR ACTIVE EL USO DE JAVASCRIPT PARA CONTINUAR</p></noscript>';
     if($usarModeloGeneral){
        include_once("./conf/plantilla.php");
    }else{
        index();
    }
    $objBd->fCerrar();
    if($dep==1){
        echo "<div style='position:absolute;top:0px;width:100%;background-color: #ffffff;border:5px solid #000000;padding:10px 10px 10px 10px;display:block'>";
        echo fTitulo(4,"Modo Desarrollo","trz",null,"margin:0");
        echo "<div id='traza' style='display:none'>";
        echo $traza;
        echo fSeparador();
        $fin = microtime();
        $tiempo = $fin - $inicio;
        echo "Tiempo Ejecucion: " . round($tiempo,4);
        echo "</div>";
        echo "</div>";
        echo fEfectosInteractivos("trz","click","traza");
    }
    
echo "</body></html>";
?>
