<?php
function cargaArchivo(){
    $x=fEnviadoPost();//recibo los datos
    if (fSubirImagen("imagenes",$x[0],"jpg")==0){//llamo a FAP de subir imagen (ruta endonde se guarda,nombre del archivo,extension)
        echo fMensaje(1,"Imagen guardada");//mensaje de exito
        echo fImagen($x[0].".jpg",320,240,null,null,"conf/datos/imagenes");//muestro la imagen 
    }else{
        echo fMensaje(3,"Oh oh algo salio mal oppss!!!");//mensaje de algo salio mal
    }
}
?>
