<?php
/*
  modJSCRIPT.php: Modulo que Contiene las Funciones Gestion Javascript con JQUERY del Marco de Trabajo
       
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
function fpreCarga(){
    $a= '$(document).ready(function(){
        $("body").css({"overflow-y":"hidden"});
        var alto=$(window).height();
        $("body").append("<div id=\'pre-load-web\'><div id=\'imagen-load\'><img src=\'conf/img/carga.gif\'  /><br />Espere a que se Cargue el Contenido...</div>");
        $("#pre-load-web").css({height:alto+"px"});
        $("#imagen-load").css({"margin-top":(alto/2)-30+"px"});
        }) 
        $(window).load(function(){
           $("#pre-load-web").fadeOut(1000,function(){ 
               //eliminamos la capa de precarga
               $(this).remove();
               //permitimos scroll
               $("body").css({"overflow-y":"auto"}); 
           });        
        })' ;
     return $a;       
}
function fAcordeon($id){
        $a='<script>$(function(){$( "#'.$id.'" ).accordion({heightStyle: "content"});});</script><div id="'.$id.'">';
        $vari=func_get_args();
        if(func_num_args()>1){
            for($i=1;$i<func_num_args();$i++){
               if(($i%2)!=0){
                    $e=$i+1;
                    $a.="<h3>$vari[$i]</h3><div>$vari[$e]</div>";
                }
            }
        }
         $a.="</div>";
       return $a;
}
function fLlamaFuncion($idCon,$evento,$funcion){
    echo "<script type='text/javascript'>\$(document).ready(function(){\$('$idCon').$evento(function(){ $funcion })});</script>";
}
function fAjax($idCon,$evento,$funcion,$capa,$valor=null,$url){
    $vari=func_get_args();
    $a= "<script type='text/javascript'>\$(document).ready(function(){\$('$idCon').$evento(function(){
            var parametros = {";
                if (func_num_args()>6){
                    for($i=6;$i<func_num_args();$i++){
                       if ($i==func_num_args()-1){
                           $a.=  "$vari[$i]:$(\"#$vari[$i]\").val()";
                       }else{
                            $a.=  "$vari[$i]:$(\"#$vari[$i]\").val(),";
                        }
                    }
                }
    if($valor!=null)
		$a.= "};\$.ajax({data:new FormData($('#".$valor."')[0]),url:'?m=".$_GET['m']."&ajax=$funcion&$url',type:  'post', cache: false,contentType: false, processData: false,beforeSend: function (){\$('#$capa').html('Procesando, espere por favor...');},success:  function (response){\$('#$capa').html(response);},error: function(objeto, quepaso, otroobj){\$('#$capa').html('Ocurrio un error, rayos!!');}});});});</script>";
	else
		$a.= "};\$.ajax({data:parametros,url:'?m=".$_GET['m']."&ajax=$funcion&$url',type:  'post',beforeSend: function (){\$('#$capa').html('Procesando, espere por favor...');},success:  function (response){\$('#$capa').html(response);},error: function(objeto, quepaso, otroobj){\$('#$capa').html('Ocurrio un error, rayos!!');}});});});</script>";
return $a;
}
function fAlerta($id,$titulo,$contenido,$ancho,$largo,$objeto=null,$evento=null,$animado=true,$modal=true,$efc=0){
       $anm=array("blind","bounce","clip","drop","explode","fade","fold","puff","pulsate","shake","slide");
       if($animado==false && $modal==false){
            if($objeto!=null){
                $a.="<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: false,autoOpen: false,resizable: false,buttons:{Ok: function(){\$( this ).dialog( 'close' );}}});\$('#$objeto').$evento(function() {\$('#".$id."' ).dialog('open');});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
                return $a;
            }else{
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: false,autoOpen: true,resizable: false,buttons:{Ok: function(){\$( this ).dialog( 'close' );}}});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
            }
       }
       if($animado==true && $modal==false){
            if($objeto!=null)
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: false,autoOpen: false,resizable: false,show: {effect: '".$anm[$efc]."',duration: 1000},hide: {effect: '".$anm[$efc]."',duration: 1000},buttons:{Ok: function(){\$( this ).dialog( 'close' );}}});\$('#$objeto').$evento(function() {\$('#".$id."' ).dialog('open');});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
            else
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: false,autoOpen: true,resizable: false,show: {effect: '".$anm[$efc]."',duration: 1000},hide: {effect: '".$anm[$efc]."',duration: 1000},buttons:{Ok: function(){\$( this ).dialog( 'close' );}}});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
       }
       if($animado==false && $modal==true){
            if($objeto!=null)
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: true,autoOpen: false,resizable: false,buttons:{Ok: function(){\$( this ).dialog( 'close' );}}});\$('#$objeto').$evento(function() {\$('#".$id."' ).dialog('open');});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
            else
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: true,autoOpen: true,resizable: false,buttons:{Ok: function(){\$( this ).dialog( 'close' );}}});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
       }
       if($animado==true && $modal==true){
            if($objeto!=null)
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: true,autoOpen: false,resizable: false,show: {effect: '".$anm[$efc]."',duration: 1000},hide: {effect: '".$anm[$efc]."',duration: 1000},buttons:{Ok: function(){\$( this ).dialog( 'close' );}}});\$('#$objeto').$evento(function() {\$('#".$id."' ).dialog('open');});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
            else
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: true,autoOpen: true,resizable: false,show: {effect: '".$anm[$efc]."',duration: 1000},hide: {effect: '".$anm[$efc]."',duration: 1000},buttons:{Ok: function(){\$( this ).dialog( 'close' );}}});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
       }
}
function fBloquearControl($id,$ac=0,$automatico=true,$objeto=null,$evento=null){
    if($automatico){
        if($ac==0)
            $a= "<script type='text/javascript'>$('#$objeto').$evento(function(){\$('#".$id."' ).attr('disabled','-1');})</script>";
        if($ac==1)
            $a= "<script type='text/javascript'>$('#$objeto').$evento(function(){\$('#".$id."' ).removeAttr('disabled');})</script>";
        if($ac==2)
            $a= "<script type='text/javascript'>$('#$objeto').$evento(function(){if(\$('#".$id."' ).attr('disabled')){\$('#".$id."' ).removeAttr('disabled');}else{\$('#".$id."' ).attr('disabled','-1');};})</script>";
    }else{
        if($ac==0)
            $a= "<script type='text/javascript'>$('#$id').attr('disabled','-1')</script>";
        else
            $a= "<script type='text/javascript'>$('#$id').removeAttr('disabled')</script>";
    }
    return $a;
}
function fDialogo($id,$titulo,$contenido,$ancho,$largo,$objeto=null,$evento=null,$animado=0,$modal=0,$efc=0){
       $anm=array("blind","bounce","clip","drop","explode","fade","fold","puff","pulsate","shake","slide");
       if($animado!=0 && $modal!=0){
            if($objeto!=null)
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: false,autoOpen: false,resizable: false});\$('#$objeto').$evento(function() {\$('#".$id."' ).dialog('open');});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
            else
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: false,autoOpen: true,resizable: false});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
       }
       if($animado==1 && $modal==0){
            if($objeto!=null)
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: false,autoOpen: false,resizable: false,show: {effect: 'slide',duration: 1000},hide: {effect: '".$anm[$efc]."',duration: 1000}});\$('#$objeto').$evento(function() {\$('#".$id."' ).dialog('open');});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
            else
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: false,autoOpen: true,resizable: false,show: {effect: 'slide',duration: 1000},hide: {effect: '".$anm[$efc]."',duration: 1000}});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
       }
       if($animado==0 && $modal==1){
            if($objeto!=null)
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: true,autoOpen: false,resizable: false,});\$('#$objeto').$evento(function() {\$('#".$id."' ).dialog('open');});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
            else
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: true,autoOpen: true,resizable: false});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
       }
       if($animado==0 && $modal==0){
            if($objeto!=null)
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: true,autoOpen: false,resizable: false,show: {effect: '".$anm[$efc]."',duration: 1000},hide: {effect: '".$anm[$efc]."',duration: 1000}});\$('#$objeto').$evento(function() {\$('#".$id."' ).dialog('open');});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
            else
                return "<script>\$(function() {\$( '#".$id."' ).dialog({height: $largo,width:$ancho,modal: true,autoOpen: true,resizable: false,show: {effect: '".$anm[$efc]."',duration: 1000},hide: {effect: '".$anm[$efc]."',duration: 1000}});});</script><div id='".$id."' title='$titulo'>$contenido</div>";
       }
}
function fEfectosAutomaticos($id,$efecto=0,$velocidad=300,$tiempo=2000,$pausa=0){ 
      //              0        1       2        3        4           5        6       7         8        9        10          11           12          13            14            15          16       17        18      19     20       21         22             23       24      25     26
      $tipo=array('blindX','blindY','blindZ','cover','curtainX','curtainY','fade','fadeZoom','growX','growY','scrollUp','scrollDown','scrollLeft','scrollRight','scrollVert','scrollHorz','shuffle','slideX','slideY','toos','turnUp','turnDown','turnLeft','turnRight','uncover','wipe','zoom');
      return "<script type='text/javascript'>
                $(document).ready(function() {
                    $('#$id').cycle({
                         fx:'".$tipo[$efecto]."', 
                        speed:    $velocidad, 
                        timeout:  $tiempo,
                        pause:  $pausa  
                    });
                });
            </script>";
}
function fEfectosInteractivos($objeto,$evento,$id,$modo=0,$efecto=0,$opciones=""){
    $anm=array("blind","bounce","clip","drop","explode","fade","fold","puff","pulsate","shake","slide");
    //blind';op: direction: up,down,left,rigth,vertical,horizontal
    //'bounce';//op times: int, distance: int
    //'clip';//op: direction: up,down,left,rigth,vertical,horizontal
    //'drop';//op: direction: up,down,left,rigth
    //'explode';//op pieces:int
    //'fade';
    //'fold';//op size:int horizFirst:bool
    //'highlight'; //op color:hex
    //'puff';//op: percent:int
    //'pulsate';//op: times:int
    //'slide';//op: direction:"left", "right", "up", "down","both" ,distance:int
    
    if($modo==0){
        return "<script type='text/javascript'>
                $(document).ready(function() {
                    $('#$objeto').$evento(function(){
                        var options = {".$opciones."};
                        $('#$id').toggle('".$anm[$efecto]."',options);
                    });
                });
            </script>";
    }
    if($modo==1)
        return "<script type='text/javascript'>
                $(document).ready(function() {
                    $('#$objeto').$evento(function(){
                        var options = {".$opciones."};
                        $('#$id').hide('".$anm[$efecto]."',options);
                    });
                });
            </script>";
    if($modo==2)
    return "<script type='text/javascript'>
                $(document).ready(function() {
                    $('#$objeto').$evento(function(){
                        var options = {".$opciones."};
                        $('#$id').show('".$anm[$efecto]."',options);
                    });
                });
            </script>";
   
}
function fEvitaEvento($id,$evento){
    $a="<script>$('#$id').$evento(function(event){event.preventDefault();})</script>";
    return $a;
}
function fFiltroTabla($id){
    $a= "<div class='q' style='margin-bottom:10px'><img src='nucleo/iconos/find.png' width='16px' height='16px' style:'float:left'/> <span style:'float:left'>Buscar:</span>".fCampo("q$id","","texto")."</div>";
        $a.= "<script>
                $(function() { 
                    var tbl = $('#$id')
                    $('#q$id').keyup(function() {
                    $.uiTableFilter(tbl, this.value );
                    })}); 
              </script>";
    return $a;
}
function fLimpiarControl($id,$objeto=null,$evento=null){
    if($objeto!=null)
		return "<script type='text/javascript'>\$(document).ready(function() {\$('#$objeto').$evento(function(){\$('#$id').val(null);});});</script>";
	else
		return "<script type='text/javascript'>\$(document).ready(function() {\$('#$id').val(null);});</script>";
}
function fLlenarControl($id,$txt,$objeto,$evento){
    return "<script type='text/javascript'>\$(document).ready(function() {\$('#$objeto').$evento(function(){\$('#$id').val('$txt');});});</script>";
}

function fOrdenaTabla($id){
    echo "<script>\$(document).ready(function(){\$('#".$id."').tablesorter();});</script>" ;
}

function fPestanas($id){
        $a= "<script>\$(function(){\$('#".$id."').tabs();});</script><div id='".$id."'>";
        $a.= "<ul>";
        $vari=func_get_args();
        if(func_num_args()>1){
            for($i=1;$i<func_num_args();$i++){
               if(($i%2)!=0)
                    $a.= "<li><a href='#fragment-$i'><span>$vari[$i]</span></a></li>";
            }
        }
        $a.= "</ul>";
            for($i=1;$i<func_num_args();$i++){
               if(($i%2)==0){
                    $e=$i-1;
                    $a.="<div id='fragment-$e'>$vari[$i] </div>";
                }
            }
         $a.="</div>";
       return $a;
}
function fReloj($id){
   return "<script language='JavaScript'> 
        \$(function(){   
                setInterval('mueveReloj()',1000);
            });
        mueveReloj=function(){ 
            momentoActual = new Date() 
            hora = momentoActual.getHours() 
            minuto = momentoActual.getMinutes() 
            segundo = momentoActual.getSeconds()
            if(segundo<10)
                seg='0'+segundo
            else
                seg=segundo
            if(minuto<10)
                min='0'+minuto
            else
                min=minuto
            if(hora<10)
                hor='0'+hora
            else
                hor=hora
            horaImprimible = hor + ' : ' + min + ' : ' + seg 
            \$('#$id').text(horaImprimible);
            setTimeout('mueveReloj()',1000) 
        } 

</script> ";

}
function fSeleccionaFila($id,$f1=null,$f2=null){
    $a= "<script>
          $('#$id tbody tr').click(function() {
                if($(this).hasClass('fila_selec')){
                    var ide=\$(this).attr('id');
                    $(this).removeClass('fila_selec');";
                    if($f2!=null)
                        $a.= $f2."(ide);";
            $a.=        "return;
                }
                $('#$id tbody tr').each(function (index) {
                    if($(this).hasClass('fila_selec')){
                        $(this).removeClass('fila_selec');
                    }
                });
                var ide=\$(this).attr('id');
                \$(this).addClass('fila_selec');";
                if($f1!=null)
                        $a.= $f1."(ide);";
        $a.= "return 2;
            });
            </script>";
    return $a;
}
function fValidacionCampos($id_formulario){
        $a= "<script type='text/javascript'>";
        $a.= "\$(function() {\$('#$id_formulario').validate({rules: {";
        $vari=func_get_args();
        for($i=1;$i<func_num_args();$i++){
            $c=explode(";",$vari[$i]);
            if(count($c)==2){
                if($i==func_num_args()-1){
                    $a.= "$c[0]:{required:true}";
                }else{
                    $a.= "$c[0]:{required:true},";
                    
                }
            }else{
                if($c[2]=="igual"){
                    if($i==func_num_args()-1){
                        $a.= "$c[0]:{required:true,equalTo:'#$c[0]B'}";
                    }else{
                        $a.= "$c[0]:{required:true,equalTo:'#$c[0]B'},";
                    }
                }else{
                    if($i==func_num_args()-1){
                        $a.= "$c[0]:{required:true,$c[2]:true}";
                    }else{
                        $a.= "$c[0]:{required:true,$c[2]:true},";
                    }
                }
            }
        }
        $a.= "}, messages:{";
        for($i=1;$i<func_num_args();$i++){
            $c=explode(";",$vari[$i]);
            if(count($c)==2){
                if($i==func_num_args()-1){
                    $a.= "$c[0]:{required:'$c[1]'}";
                }else{
                    $a.= "$c[0]:{required:'$c[1]'},";
                }
            }else{
                if($c[2]=="igual"){
                    if($i==func_num_args()-1){
                        $a.= "$c[0]:{required:'$c[1]',equalTo:'No coinciden'}";
                    }else{
                        $a.= "$c[0]:{required:'$c[1]',equalTo:'No coinciden'},";
                    }
                }else{
                    if($i==func_num_args()-1){
                        $a.= "$c[0]:{required:'$c[1]',$c[2]:'$c[3]'}";
                    }else{
                        $a.= "$c[0]:{required:'$c[1]',$c[2]:'$c[3]'},";
                    }
                }
            }
        }
        $a.=   "} });});</script>";
        return $a;
}
function fValidar_CamposAutomatico($valido,$tipo){
    $a= "<script>\$('#nuevo').validate({rules: {";
    for($i=0;$i<count($valido);$i++){
        if ($i==count($valido)-1)
             if($tipo[$i]!="none")
                $a.= $valido[$i].":{required:true,$tipo[$i]:true}},messages:{";
            else
                $a.= $valido[$i].":{required:true}},messages:{";
        else
            if($tipo[$i]!="none")
                $a.= $valido[$i].":{required:true,$tipo[$i]:true},";
            else
                $a.= $valido[$i].":{required:true},";
    }
    for($i=0;$i<count($valido);$i++){
        if ($i==count($valido)-1){
            if($tipo[$i]!="none"){
                switch($tipo[$i]){
                    case "digits":
                        $a.= $valido[$i].":{required:\"Campo Obligatorio\",$tipo[$i]:\"Ingrese Solo Numeros Enteros\"}}";
                        break;
                    case "number":
                        $a.= $valido[$i].":{required:\"Campo Obligatorio\",$tipo[$i]:\"Ingrese Solo Numeros  con decimales\"}}";
                        break;
                }
            }else{
                $a.= $valido[$i].':{required:"Campo Obligatorio"}}';
            }
        }else{
            if($tipo[$i]!="none"){
                switch($tipo[$i]){
                    case "digits":
                        $a.= $valido[$i].":{required:\"Campo Obligatorio\",$tipo[$i]:\"Ingrese Solo Numeros Enteros\"},";
                        break;
                    case "number":
                        $a.= $valido[$i].":{required:\"Campo Obligatorio\",$tipo[$i]:\"Ingrese Solo Numeros  con decimales\"},";
                        break;
                }
            }else{
                $a.= $valido[$i].':{required:"Campo Obligatorio"},';
            }
        }        
    }
    $a.="});</script>";
    return $a;
}
?>
