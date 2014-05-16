<?php
function index(){
    echo fTitulo(2,"La Carga de Archivos de Imagenes");//<h2>
    echo "<p>ATENCION: ejemplo con fines explicativos</p>";
    echo fEventoClick(cargaArchivo);//llamo a una funcion en el controlador
    echo fFormulario("form1",null,null,null,1);//formulario(idDOM,no action,no clase,sin estilo,tipo 1 form/part)
        echo fCampo("nombre","Ingrese el nombre de la imagen","texto");//campo texto(idDOM y nombre,etiqueta(label),tipo de campo)
        echo fCampo("arc","Indique la imagen","archivo");//campo de archivo (debe llamarse arc)
        echo fCampo("boton","Procesar","submit");//campo boton
    echo fFinFormulario();//cierra el formulario

}


?>
