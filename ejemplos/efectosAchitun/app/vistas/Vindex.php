<?php
function index(){
   echo fDiv("ct");//abrimos un div
   echo fIcono("help",64,64,"ayuda");//pintamos un icono de ayuda
   echo fDiv("ay",null,"display:none").fParrafo("Haga click sobre el Icono de la Camara").fFinDiv();//hacemos un div de ayuda
   echo fEfectosInteractivos("ayuda","mouseover","ay",2);//efecto que muestra el div ay
   echo fEfectosInteractivos("ayuda","mouseout","ay",1);//efecto que oculta el div ay
   echo fSalto();
   echo fIcono("camera",64,64,"foto");//pintamos otro icono
   echo fEfectosInteractivos("foto","click","ft",0,10);//efecto que muestra el icono carro
   echo fSalto();
   echo fIcono("car",90,90,"ft",null,"display:none");//icono carro oculto
   echo fSalto();
   echo "<p>No hagas click sobre la bomba!!!!</p>";
   echo fIcono("bomb",64,64,"pum");//pintamos una bomba
   echo fEfectosInteractivos("pum","click","ct",1,4,"pieces:25");//efecto explode la hacer click en la bomba
   echo fFinDiv();//cerramos el div
   
}


?>
