<?php
function index(){
    echo fTitulo(2,"Manejando Eventos (pseudo Eventos click");//<h2>
    echo "<p>ATENCION: ejemplo con fines explicativos</p>";
    echo fEnlace("Saludame","index","index",1);
    echo fSalto();//<br/>
    echo fEnlace("Dime la Fecha","index","index",2);
    echo fSalto();
    echo fEnlace("Muestra una foto","index","index",3);
    echo fSalto();
    echo fEnlace("Despidete","index","index",4);
    echo fSalto();
    echo fEvento("saludo",1);
    echo fEvento("fecha",2);
    echo fEvento("foto",3);
    echo fEvento("chao",4);
}


?>
