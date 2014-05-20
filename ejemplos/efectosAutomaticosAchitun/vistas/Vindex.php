<?php
function index(){
   echo fDiv("ct",null,"top:25px");//abrimos un div
   //pintamos varios iconos
   echo fIcono("help",64,64);
   echo fIcono("information",64,64);
   echo fIcono("group",64,64);
   echo fIcono("sport_soccer",64,64);
   echo fIcono("user",64,64);
   echo fIcono("television",64,64);
   echo fFinDiv();//cerramos el div
   echo fEfectosAutomaticos("ct",$efecto=10,$velocidad=300,$tiempo=2000,$pausa=0);
}


?>
