<?php
function index(){
    //invocamos a la funcion para saber si una sesion valida
    if (fBloqueo()){
        echo fSalir(); // renderizamos el enlace para cerrar la sesion
        echo fNuevo("post",null,true,null,fUsuarioID().";Escribe aqui tu post"); //llamamos al formulario para un un nuevo post
        /*fNuevo(tabla,no mostramos el campo relacionado,modo automatico,mostramos los campos necesarios,indicamos los valores por defecto en los campos)         */
    }else{
        echo fLogin();//renderizamos el formulario de acceso
        foreach(fBuscar(true,"post") as $t){//consultamos los datos del post y los mostramos
            echo $t[6]." ".$t[7]." Dijo:";
            echo fSalto();//salto de linea (<br/>)
            echo $t[1];
            echo fSeparador();//(<hr/>)
        }
    }
}
?>
