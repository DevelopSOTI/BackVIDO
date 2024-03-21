<?php

function getParametros($NOMBRE,$MODULO,$BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
    $result = "";
    if ($conn){
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">
        $select  = "SELECT * FROM PARAMETROS WHERE NOMBRE='$NOMBRE' AND MODULO='$MODULO' ;";
        echo $select;
        // </editor-fold>  
        //echo " Consulta ".$select." ";
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $faltante=$row["VALOR"];
                $result=$faltante;
            }
        //mysqli_close($conn);
            return $result;
        }
        else{
            mysqli_close($conn);
            return "0"; 
        }
        mysqli_close($conn);
    }
    else {
        // FALLO LA CONEXION
        return "-1"; 
    }


}


$server->register(
    'getParametros',
    array(
        'NOMBRE'=>'xsd:string',
        'MODULO'=>'xsd:string',
        'BD'=>'xsd:string'
        ),
    array('return'=>'xsd:string'),
    $namespace,
    false,
    'rpc',
    false,
    'Verifica el valor de un parametro');