<?php
/*
  modHTML.php: Modulo que Contiene las Funciones Gestion HTML del Marco de Trabajo
       
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
global $crg;
function fCampo($nombre,$etiqueta,$tipo,$valor=null,$protegido=0,$clase=null,$estilo=null){
    $soloLectura="";
    global $crg;
    if($protegido!=0)
        $soloLectura="readonly='readonly'";
            switch($tipo){
            case "texto":
                $a= "<div class='cpoForm'><label id='etq$nombre'>$etiqueta</label><input type='text' name='$nombre' id='$nombre' value='$valor' ".$soloLectura." class='$clase' style='$estilo'/></div>";
                break;
            case "area":
                $a= "<div class='cpoForm'><label id='etq$nombre'>$etiqueta</label><textarea name='$nombre' cols='10' rows='5' id='$nombre'  $soloLectura class='$clase' style='$estilo'>$valor</textarea></div>";
                break;
            case "editor":
                $a= "<div class='cpoForm'><label id='etq$nombre'>$etiqueta</label><textarea name='$nombre' cols='10' rows='5' id='$nombre'  $soloLectura class='$clase' style='$estilo'>$valor</textarea></div>";
                $a.="<script>new nicEditor({fullPanel : true}).panelInstance('".$nombre."');</script>";
                break;
            case "clave":
                $a ="<div class='cpoForm'><label id='etq$nombre'>$etiqueta</label><input type='password' name='$nombre' id='$nombre' value='$valor' $soloLectura style='$estilo'/></div>";
                break;
            case "oculto":
                $a= "<input type='hidden' name='$nombre' id='$nombre' value='$valor'/>";
                break;
            case "check":
                $a= "<div class='cpoForm'><label id='etq$nombre'>$etiqueta</label><input type='checkbox' name='$nombre' id='$nombre' value='$valor' style='$estilo'/></div>";
                break;
            case "radio":
                $vari=explode("+",$valor);
                if($vari[1]=="*")
                    $c= "checked='checked'";
                $a= "<div class='cpoForm'><label>$etiqueta</label><input type='radio' name='$nombre' id='$nombre' value='$valor' style='$estilo' $soloLectura $c/></div>";
                break;
            case "fecha":
                if($crg==0){
                    echo '<script src="nucleo/js/jquery-ui.js" type="text/javascript"></script> ';
                    echo '<link href="nucleo/estilos/jquery-ui-1.8.21.custom.css" rel="stylesheet" type="text/css"/>';
                    $crg=1;
                }
                $a= "<div class='cpoForm'><label id='etq$nombre'>$etiqueta</label><input type='text' name='$nombre' id='$nombre' value='$valor' class='dateES' $soloLectura style='$estilo'/></div>";
                $a.= "<script type='text/javascript'>\$(function(){\$('#$nombre').datepicker({dateFormat: 'dd/mm/yy'});});</script>";
                break;
            case "archivo":
                $a= "<div class='cpoForm'><label id='etq$nombre'>$etiqueta</label><input type='file' name='$nombre' id='$nombre' value='$valor' $soloLectura style='$estilo'/></div>";
                break;
            case "seleccion":
                $a= "<div class='cpoForm'><label id='etq$nombre'>$etiqueta</label> <select name='$nombre' id='$nombre' class='cpoForm' $soloLectura style='$estilo'><option value=''></option></div>";
                $vari=explode("+",$valor);
                    $c=$vari[0];
                for($i=1;$i<count($vari);$i++){
                    $opciones=explode(";",$vari[$i]);
                    if(($c+3)==$i)
                        $a.= "<option value='$opciones[0]' selected>".$opciones[1]."</option>";
                    else
                        $a.= "<option value='$opciones[0]'>".$opciones[1]."</option>";
                    }
                $a.= "</select></div>";
                return $a;
                break;
            case "submit":
                $a= "<div class='cpoForm'><input id='$nombre' type='submit' name='$nombre' value='$etiqueta' style='$estilo' class='$clase'/></div>";
                break;
            case "boton":
                $a= "<div class='cpoForm'><input id='$nombre' type='button' name='$nombre' value='$etiqueta' style='$estilo' class='$clase'/></div>";
                break;
        }
        return $a;
}

function fDiv($id=null,$clase=null,$estilo=null){
    return "<div id='$id' class='$clase' style='$estilo'>";
}        

function fEnlace($nombre,$modulo=null,$vista=null,$evento=0,$id=0,$itemid=0,$variables=null,$dId=null,$clase=null,$img=null,$lado=1){
    if($modulo==null)
        $modulo=$_GET['m'];
    if($vista==null)
        $vista=$_GET['v'];
    if($vista=="index")
		$vista="";
    if($img==null)
        $a= "<a href='?m=$modulo&amp;v=$vista&amp;e=$evento&amp;id=$id&amp;itemid=$itemid&amp;$variables' class='$clase' id='$dId'>$nombre</a>";
    else
        if($lado==1)
            $a= "<a href='?m=$modulo&amp;v=$vista&amp;e=$evento&amp;id=$id&amp;itemid=$itemid&amp;$variables' class='$clase' id='$dId'><img src='nucleo/iconos/$img.png' alt='$img'/> $nombre</a>";
        else
            $a= "<a href='?m=$modulo&amp;v=$vista&amp;e=$evento&amp;id=$id&amp;itemid=$itemid&amp;$variables' class='$clase' id='$dId'>$nombre <img src='nucleo/iconos/$img.png' alt='$img'/></a>";
    return $a;
}

function fEnlaceExterno($url,$nombre,$img=null){
    $a="<a href='$url' target='_blank' onClick='window.open(this.href, this.target); return false;'>";
            if ($img!=null)
               $a.= "<img src='$img' />$nombre</a>";
            else
                $a.="$nombre</a>";
    return $a;
}

function fFila($id){
    $vari=func_get_args();
            if(func_num_args()>0){
                $a= "<tr class='fila' id='$vari[0]'>";
                for($i=1;$i<func_num_args();$i++){
                    $d=explode('~',$vari[$i]);
                    if($d[1]==null)
                        $a.= "<td>$vari[$i]</td>";
                    else
                        $a.= "<td colspan='$d[1]'>".utf8_encode($d[0])."</td>";
                }
                $a.= "</tr>";
            }
    return $a;
}

function fFinDiv(){
  return "</div>";
}

function fFinFormulario(){
    return "</form>";
}

function fFinTabla(){
    return "</tbody></table>";
}

function fFormulario($id,$accion=null,$clase=null,$stylo=null,$tip=0){
    if ($accion!=null)
        if ($tip==0)
            return "<form method='post' action='?$accion' id='$id' class='$clase' style='$stylo'>";
        else
            return "<form method='post' action='?$accion' id='$id' class='$clase' style='$stylo' enctype='multipart/form-data'>";
    else
        if ($tip==0)
            return "<form method='post' action='' id='$id' class='$clase' style='$stylo'>";
        else
            return "<form method='post' action='' id='$id' class='$clase' style='$stylo' enctype='multipart/form-data'>";
}

function fIcono($img,$w=null,$h=null,$id=null,$clase=null,$estilo=null){
    if($w!=null || $h!=null)
        return "<img src='nucleo/iconos/$img.png' width='".$w."px' height='".$h."px' alt='$img' id='$id' class='$clase' style='$estilo'/>";
    else
        return "<img src='nucleo/iconos/$img.png' alt='$img' id='$id' class='$clase' style='$estilo'/>";
}

function fIconoEdicion($modulo=null,$vista=null,$id=0,$itemid=0,$dId=null,$clase=null){
     if($modulo==null)
            $modulo=$_GET['m'];
     if($vista==null)
            $vista=$_GET['v'];
    $a= "<a href='?m=$modulo&amp;v=$vista&amp;id=$id&amp;itemid=$itemid&amp;e=95' id='$dId' class='$clase'><img src='nucleo/iconos/table_edit.png' alt='editar'/></a>";
    return $a;
}

function fIconoEliminar($modulo=null,$vista=null,$id=0,$itemid=0,$evento=null,$dId=null,$clase=null){
     if($modulo==null)
            $modulo=$_GET['m'];
     if($vista==null)
            $vista=$_GET['v'];
    if($evento==null)
        if($_GET['e']==94)
            fEliminar();
        $a= "<a href='?m=$modulo&amp;v=$vista&amp;id=$id&amp;itemid=$itemid&amp;e=94' class='$clase'><img src='nucleo/iconos/table_delete.png' alt='editar'/></a>";
    else
        $a= "<a href='?m=$modulo&amp;v=$vista&amp;id=$id&amp;itemid=$itemid&amp;e=$evento' class='$clase'><img src='nucleo/iconos/table_delete.png' alt='editar'/></a>";
    return $a;
}

function fImagen($img,$w=null,$h=null,$id=null,$clase=null,$ruta=null){
    if($w!=null || $h!=null)
        if($ruta==null)
            return "<img src='conf/img/$img' width='".$w."px' height='".$h."px' alt='$img' id='$id' class='$clase' />";
        else
            return "<img src='$ruta/$img' width='".$w."px' height='".$h."px' alt='$img' id='$id' class='$clase' />";
    else
        if($ruta==null)
            return "<img src='conf/img/$img' alt='$img' id='$id' class='$clase' />";
        else
           return "<img src='$ruta/$img' alt='$img' id='$id' class='$clase' />";
}

function fListasLI($class){

    $vari=func_get_args();
    if(func_num_args()>0){
        for($i=1;$i<func_num_args();$i++){
           $a.= "<li class=$class>";
            $a.= $vari[$i];
           $a.= "</li>";
        }
    }
return $a;
}

function fParrafo($txt,$id=null,$class=null,$estilo=null){
    return "<p id='$id' class='$class' style='$estilo'>$txt</p>";
}

function fSalto($cantidad=0){
    if ($cantidad==0){
        $a= "<br/>";
    }else{
        for($i=0;$i<$cantidad;$i++)
          $a.= "<br/>";
    }
    return $a;
} 

function fSelectFormBD($nombre,$etiqueta,$tablas,$campo=null,$seleccion=0,$multiple=0,$data=null){
    global $objBd;
    global $traza;
    if (depuracion())
        $traza.="<div style='background-color:#E5E5E5;margin-bottom:5px'><p style='color:#095909;font-weight: bold'>ModHTML: LLamado a la funcion fSelectFormBD: ";
    $objBd->fDatos_tabla($tablas);
    $i=0;
    if($campo==null){
        while($a=$objBd->fConsultaArreglo()){
            if($a[3]!=NULL){
                $campo=$i;
                break;
            }
            $i++;
        }
    }
    if (depuracion())
        $traza.="<p>Usando el Campo $campo</p>";
    if($multiple!=0)
        $ss="size=$multiple";
    $x=$objBd->fIdTabla($tablas);
    if($data==null)
        $objBd->fSelect($tablas,'*',"eliminado=0");
    else
        $objBd->fSelect($tablas,'*',"eliminado=0 and $data");
    $combo= "<div class='cpoForm' id='cpo$nombre'><label>$etiqueta</label><select name='$nombre' id='$nombre'><option value=''></option>";
        while($opciones=$objBd->fConsultaArreglo()){
            if($seleccion!=$opciones[$x])
                $combo.= "<option value='".$opciones[$x]."'>".$opciones[$campo]."</option>";
            else
                $combo.= "<option value='".$opciones[$x]."' selected>".$opciones[$campo]."</option>";
        }
    $combo.= "</select></div>";
    if (depuracion())
        $traza.="</div>";
    return $combo;
}
function fSeparador(){
    return "<hr/>";
}    
function fTabla($id='tabla',$clase='tabla1'){
        $a= "<table id='$id' class='$clase' >";
        $vari=func_get_args();
            if(func_num_args()>0){
                $a.= "<thead><tr class='columna_encabezado'>";
                for($i=2;$i<func_num_args();$i++){
                    $a.= "<th>$vari[$i]</th>";
                }
                $a.= "</thead></tr><tbody>";
            }
        return $a;
}

function fTitulo($tipo,$txt,$id=null,$class=null,$estilo=null){
    switch ($tipo){
        case 1:
            $a= "<h1 id='$id' class='$class' style='$estilo'>$txt</h1>";
            break;
        case 2:
            $a= "<h2 id='$id' class='$class' style='$estilo'>$txt</h2>";
            break;
        case 3:
            $a= "<h3 id='$id' class='$class' style='$estilo'>$txt</h3>";
            break;
        case 4:
            $a= "<h4 id='$id' class='$class' style='$estilo'>$txt</h4>";
            break;
        case 5:
            $a= "<h5 id='$id' class='$class' style='$estilo'>$txt</h5>";
            break;
        case 6:
            $a= "<h6 id='$id' class='$class' style='$estilo'>$txt</h6>";
            break;
    }
    return $a;
}
?>
