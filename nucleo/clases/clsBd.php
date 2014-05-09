<?php
/*
  clsBd.php: Clase que gestiona las conexiones y transacciones de la Base de Datos y el Marco de Trabajo
       
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
class clsBd{
    private $sServidor;
    private $sUsuario;
    private $sClave;
    private $sBaseDeDatos;  
    private $iConexion; 
    private $vQuery;
    private $vCampo;
    
     function __construct(){
        if(file_exists("conf/config.php"))
            require_once "conf/config.php";
        $this->sServidor    = $servidor;
        $this->sUsuario     = $usuario;
        $this->sClave       = $clave;
        $this->sBaseDeDatos = $bd;
        $this->sConector    = $tipo;

    }
    
function fCantidadCampos(){
        global $traza;
        if($this->vQuery!=null){
            if($this->sConector=="pg"){
                if(depuracion())
                    $traza.="<p style='color:#000000;font-weight: bold'>ModBD: Cantidad Campos Consulta: ".pg_num_fields($this->vQuery)."</p>";
                return pg_num_fields($this->vQuery);
            }else{
                if(depuracion())
                    $traza.="<p style='color:#000000;font-weight: bold'>ModBD: Cantidad Campos Consulta: ".mysql_num_fields($this->vQuery)."</p>";
                return mysql_num_fields($this->vQuery);
            }
            
        }else{
            if(depuracion())
                $traza.="<p style='color:#000000;font-weight: bold'>ModBD: Cantidad Registros Consulta: -1 </p>";
            return -1;
        }
}

function fCantidadRegistros(){
        global $traza;
        if($this->vQuery!=null){
            if($this->sConector=="pg"){
                if(depuracion())
                    $traza.="<p style='color:#000000;font-weight: bold'>ModBD: Cantidad Registros Consulta: ".pg_num_rows($this->vQuery)."</p>";
                return pg_num_rows($this->vQuery);
            }else{
                if(depuracion())
                    $traza.="<p style='color:#000000;font-weight: bold'>ModBD: Cantidad Registros Consulta: ".mysql_num_rows($this->vQuery)."</p>";
                return mysql_num_rows($this->vQuery);
            }
        }else{
            if(depuracion())
                $traza.="<p style='color:#000000;font-weight: bold'>ModBD: Cantidad Registros Consulta: -1 </p>";
            return -1;
        }
}

function fConectar(){
        global $traza;
        if($this->sConector=="pg"){
            $strCnx = "host=".$this->sServidor." dbname=".$this->sBaseDeDatos." user=".$this->sUsuario." password=".$this->sClave;
            $this->iConexion = pg_connect($strCnx);
        }else{
            $this->iConexion = mysql_connect($this->sServidor,$this->sUsuario,$this->sClave);
            if(!mysql_select_db($this->sBaseDeDatos, $this->iConexion)){
                if(depuracion())
                    $traza.="<p style='color:#FFA500;font-weight: bold'>No se pudo establcer la Conexion con la Base de Datos</p>";
                return 2;
            }
        }
        if ($this->iConexion==null){
            if(depuracion())
                $traza.="<p style='color:#FFA500;font-weight: bold'>No se pudo establcer la Conexion con el Servidor</p>";
            return 1;
        }
        if(depuracion())
            $traza.="<p style='color:#0000FF;font-weight: bold;text-indent:10px'>Conexion Exitosa</p>";
        return 0;
}

function fCerrar(){
    global $traza;
    if(depuracion())
        $traza.="<p style='color:#FF0000;font-weight: bold;text-indent:10px'>Conexion Finalizada</p>";
    $this->fLiberar();
    if($this->sConector=="pg"){
        pg_close($this->iConexion);
    }else{
        mysql_close($this->iConexion);
    }
}

function fConsultaArreglo(){
    global $traza;
    if($this->sConector=="pg"){
        if($this->vQuery!=null){
           return pg_fetch_array($this->vQuery);
        }else{
            if(depuracion())
                $traza.="<p style='color:#FFA500;font-weight: bold;text-indent:10px''>ERROR POSTGRES: la Consulta ha Fallasdo</p>";
            return null;
        }
    }else{
        if($this->vQuery!=null){
            return mysql_fetch_array($this->vQuery);
        }else{
            if(depuracion())
                $traza.="<p style='color:#FFA500;font-weight: bold;text-indent:10px''>ERROR MYSQL: la Consulta ha Fallasdo</p>";
            return null;
        }
    }
    
}

function fConsultaAsociada(){
    global $traza;
    if($this->sConector=="pg"){
        if($this->vQuery!=null){
            return pg_fetch_assoc($this->vQuery);
        }else{
            if(depuracion())
                $traza.="<p style='color:#FFA500;font-weight: bold;text-indent:10px''>ERROR POSTGRES: la Consulta ha Fallasdo</p>";
            return null;
        }
    }else{
        if($this->vQuery!=null){
            return mysql_fetch_array($this->vQuery);
        }else{
            if(depuracion())
                $traza.="<p style='color:#FFA500;font-weight: bold;text-indent:10px''>ERROR MYSQL: la Consulta ha Fallasdo</p>";
            return null;
        }
    }
} 

function fLiberar(){
    mysql_free_result($this->vQuery);
}

function fNombresCampos(){
        global $traza;
        if($this->sConector=="pg"){
            if($this->vQuery!=null){
                for($i=0;$i<(pg_num_fields($this->vQuery));$i++){
                    $s.=pg_field_name($this->vQuery,$i)."($i),";
                    $t[]=pg_field_name($this->vQuery,$i);
                }
            }
        }else{
            if($this->vQuery!=null){
                for($i=0;$i<(mysql_num_fields($this->vQuery));$i++){
                    $s.=mysql_field_name($this->vQuery,$i)."($i),";
                    $t[]=mysql_field_name($this->vQuery,$i);
                }
            }
        }
        if(depuracion())
            $traza.="<p style='color:#000000;font-weight: bold'>ModBD: Nombres de Campos Consulta: ".$s."</p>";
        return $t;

}
    
function fSelect($sTabla, $sCampos="*", $sWhere=NULL, $sOrder=NULL, $sInner=NULL){
        global $traza;
        if (is_null($sCampos)) $sCampos = "*";
        $sSelect = "SELECT $sCampos FROM $sTabla";
        if (!is_null($sWhere)) $sSelect .= " WHERE $sWhere";
        if (!is_null($sInner)) $sSelect .= " $sInner";
        if (!is_null($sOrder)) $sSelect .= " ORDER BY $sOrder";
        if(depuracion())
            $traza.="<p style='color:#000000;font-weight: bold'>ModBD: $sSelect</p>";
        if($this->sConector=="pg"){
            $this->vQuery = pg_query($this->iConexion,$sSelect);
            if (pg_last_error()){
                if(depuracion())
                    $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR POSTGRES:".pg_last_error()."</p>";
                return;
            }
        }else{
            $this->vQuery = mysql_query($sSelect, $this->iConexion);
            if (mysql_error()){
                if(depuracion())
                    $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR MYSQL:".mysql_error()."</p>";
                return;
            }
        }
        return $this->vQuery;
}

function fInsert($sTabla, $vValores, $sCampos=NULL){
        global $traza;
        if ($sCampos==NULL):
            $sInsert = "INSERT INTO {$sTabla} VALUES({$vValores});";            
        else:
            $sInsert = "INSERT INTO {$sTabla} ({$sCampos}) VALUES ({$vValores});";
        endif;
        if(depuracion())
            $traza.="<p style='color:#000000;font-weight: bold'>ModBD: $sInsert</p>";
       if($this->sConector=="pg"){
            $this->vQuery = pg_query($this->iConexion,$sInsert);
            if (pg_last_error()){
                if(depuracion())
                    $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR POSTGRES:".pg_last_error()."</p>";
                return;
            }
        }else{
            $this->vQuery = mysql_query($sInsert, $this->iConexion);
            if (mysql_error()){
                if(depuracion())
                    $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR MYSQL:".mysql_error()."</p>";
                return;
            }
        }
        return $this->vQuery;
}

function fUpdate($sTabla, $sCampos, $sWhere=1){
        global $traza;
        $sUpdate = "UPDATE $sTabla SET $sCampos WHERE $sWhere;";
        if(depuracion())
            $traza.="<p style='color:#000000;font-weight: bold'>ModBD: $sUpdate</p>";
        if($this->sConector=="pg"){
            $this->vQuery = pg_query($this->iConexion,$sUpdate);
            if (pg_last_error()){
                if(depuracion())
                    $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR POSTGRES:".pg_last_error()."</p>";
                return;
            }
        }else{
            $this->vQuery = mysql_query($sUpdate, $this->iConexion);
            if (mysql_error()){
                if(depuracion())
                    $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR MYSQL:".mysql_error()."</p>";
                return;
            }
        }
        return $this->vQuery;
}

function fDelete($sTabla, $sRestri){
      
        $sDelete = "DELETE FROM $sTabla WHERE $sRestri;";
       if(depuracion())
            $traza.="<p style='color:#000000;font-weight: bold'>ModBD: $sUpdate</p>";
        if($this->sConector=="pg"){
            $this->vQuery = pg_query($this->iConexion,$sDelete);
            if (pg_last_error()){
                if(depuracion())
                    $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR POSTGRES:".pg_last_error()."</p>";
                return;
            }
        }else{
            $this->vQuery = mysql_query($sDelete, $this->iConexion);
            if (mysql_error()){
                if(depuracion())
                    $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR MYSQL:".mysql_error()."</p>";
                return;
            }
        }
        return $this->vQuery;
       
}

function fCrearTabla($sTabla,$sCampos){
    $sCrear="CREATE TABLE $sTabla($sCampos)";
    $id_result = mysql_query($sCrear);
    return $id_result;
}
    
function fModificar($sTabla,$sCampo){
        $modif="ALTER TABLE $sTabla ADD $sCampo";
        $id_result = mysql_query($modif);
        return $id_result; 
}
    
function fRelaciones(){
    global $traza;
    if(depuracion())
        $traza.="<p style='color:#000000;font-weight: bold'>ModBD: Tablas de la BD</p>";
        if($this->sConector=="pg"){
            $this->vQuery =pg_query("SELECT table_name, column_name FROM information_schema.key_column_usage WHERE constraint_catalog='".$this->sBaseDeDatos."' AND constraint_name like '%fkey'");
            if (pg_last_error()){
                if(depuracion())
                    $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR POSTGRES:".pg_last_error()."</p>";
                return -1;
            }
        }else{
            $this->vQuery = mysql_query("SELECT table_name AS 'Tabla', referenced_table_name AS 'references' FROM information_schema.key_column_usage WHERE CONSTRAINT_SCHEMA ='".$this->sBaseDeDatos."' and referenced_table_name IS NOT NULL",$this->iConexion);
            if (mysql_error()){
                if(depuracion())
                    $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR MYSQL:".mysql_error()."</p>";
                return -1;
            }
        }
        return $this->vQuery;
}
    
function fTablasBD(){
        global $traza;
        if(depuracion())
            $traza.="<p style='color:#000000;font-weight: bold'>ModBD: Tablas de la BD</p>";
        if($this->sConector=="pg"){
            $this->vQuery =pg_query("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
            if (pg_last_error()){
                if(depuracion())
                    $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR POSTGRES:".pg_last_error()."</p>";
                return -1;
            }
        }else{
            $this->vQuery = mysql_query("Show Tables");
            if (mysql_error()){
                if(depuracion())
                    $traza.="<p style='color:#FFA500;font-weight: bold'>ERROR MYSQL:".mysql_error()."</p>";
                return -1;
            }
        }
        return $this->vQuery;
}
    
function fDatos_tabla($tabla){
    global $traza;
    if(depuracion())
            $traza.="<p style='color:#000000;font-weight: bold'>ModBD: Consultando datos de la Taba $tabla</p>";
    if($this->sConector=="pg"){
        $this->vQuery =pg_query("SELECT ic.column_name,ic.udt_name,ic.is_nullable, (select constraint_name from information_schema.key_column_usage where table_name = '".$tabla."' AND column_name=ic.column_name) AS ip FROM information_schema.columns ic WHERE ic.table_name = '".$tabla."'  order by ic.ordinal_position");
        if($this->vQuery==null){
            if(depuracion())
                $traza.="<p style='color:#FFA500;font-weight: bold;text-indent:10px''>ERROR POSTGRES: la Consulta ha Fallasdo</p>";
            return -1;
        }
    }else{
        $this->vQuery = mysql_query("Describe ".$tabla);
        if($this->vQuery==null){
            if(depuracion())
                $traza.="<p style='color:#FFA500;font-weight: bold;text-indent:10px''>ERROR MYSQL: la Consulta ha Fallasdo".mysql_error()."</p>";
            return -1;
        }
    }
    return $this->vQuery;
}

function fExec($sql){
        $this->vQuery = mysql_query($sql);
        if (mysql_error()){
            echo "Ooops Ha ocurrido el siguiente error:\n";
            echo  mysql_error();
            return;
        }
        return $this->vQuery;
}

function fIdTabla($tabla){
    global $traza;
    if(depuracion())
            $traza.="<p style='color:#000000;font-weight: bold'>ModBD: Consultando el Numero de ID de la Taba $tabla</p>";
    if($this->sConector=="pg"){
        $this->vQuery =pg_query("select * from ".$tabla);
        if($this->vQuery==null){
            if(depuracion())
                $traza.="<p style='color:#FFA500;font-weight: bold;text-indent:10px''>ERROR POSTGRES: la Consulta ha Fallasdo</p>";
            return -1;
        }
    }else{
        $this->vQuery = mysql_query("select * from ".$tabla);
        if($this->vQuery==null){
            if(depuracion())
                $traza.="<p style='color:#FFA500;font-weight: bold;text-indent:10px''>ERROR MYSQL: la Consulta ha Fallasdo".mysql_error()."</p>";
            return -1;
        }
    }
    return $this->fCantidadCampos()-1;
}
}
global $objBd;
$objBd=new clsBd();
?>