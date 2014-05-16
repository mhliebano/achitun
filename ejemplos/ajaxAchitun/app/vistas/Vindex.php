<?php
function index(){
    echo fTitulo(2,"Trabajando con Ajax");//<h2>
    echo "<p>Para ayuda haz click en el icono</p>";
    echo fIcono("help",16,16,"ayuda");//renderiza un icono precargado (nombreIcono,ancho,alto,idDom)
    echo fDiv("divAyuda",null,"display:block").fFinDiv();//abre un div (idDOM,ninguna clase,estilos personalizado). se cierra el div
    echo fAjax("#ayuda","click","vistAyuda","divAyuda",null,"x=0");//funcion ajax (idDom que desencadena el llamado, evento que activa el ajax,vista a cargar,capa en donde se carga,valor no necesario,parametros por url)
    echo fDiv("divDatos",null,"display:block;background-color:#E5E5E5").fFinDiv();
    echo fFormulario("form1");//formulario(idDOM)
        echo fCampo("nombre","Ingrese su nombre","texto");//campo texto(idDOM y nombre,etiqueta(label),tipo de campo)
        echo fCampo("fecha","Ingrese su fecha de Nacimiento","fecha");//campo de fecha
        echo fCampo("boton","Procesar","boton");//campo boton
    echo fFinFormulario();//cierra el formulario
    echo fAjax("#boton","click","procesaDatos","divDatos",null,"x=0","nombre","fecha","boton");//funcion ajax (idDom que desencadena el llamado, evento que activa el ajax,vista a cargar,capa en donde se carga,valor no necesario,parametros por url,valor a enviar, valor a enviar, valor a enviar)
    
}

function A_vistAyuda(){
    echo "Esta ayuda fue cargada con Ajax: Ingrese su nombre, su fecha de Nacimiento y haga click en el boton";
}

function A_procesaDatos(){
    $datosRecibidos=fEnviadoPost();//recogo los valores enviados en un arreglo
    /*RENDERIZO CON TITULOS H1,H2,H3*/
    echo fTitulo(1,"Hola ".$datosRecibidos[0]);
    echo fTitulo(2,"Me indicas que nacistes el ".$datosRecibidos[1]);
    echo fTitulo(3,"Ves lo facil que es trabajar con ajax desde ACHITUN");
    
}

?>
