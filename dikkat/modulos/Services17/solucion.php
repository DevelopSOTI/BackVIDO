<?php

function getSolucionPendiente($SUCURSAL_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $faltante_id = 0;
    if ($conn){
        
        $select  =" SELECT FALTANTES_ID FROM FALTANTES ";
        $select .=" where SUCURSAL_ID = $SUCURSAL_ID ";
        $select .=" and ESTATUS ='P' ";
        $stmt = mysqli_query($conn, $select);              
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $faltantes =$row["FALTANTES_ID"];
                $result=$faltantes;  
            }
        }else {
            $faltante_id = 0;
        }
        mysqli_close($conn);
    }
    else {
        $faltante_id = 0;
    }
    return $result;
}
$server->register(
    'getSolucionPendiente',
    array(
        'SUCURSAL_ID'=>'xsd:int',
        'BD'=>'xsd:string'
        ),
    array('return'=>'xsd:int'),
    $namespace,
    false,
    'rpc',
    false,
    'Retorna el id de faltantes que no tiene solucion');


    function setSolucionFaltante($FALTANTES_ID, $BD){
        $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
        $result = 0;
        if ($conn){
            
            $select  =" UPDATE FALTANTES ";
            $select .=" SET ESTATUS ='F' ";
            $select .=" WHERE FALTANTES_ID =$FALTANTES_ID ";            
            if (mysqli_query($conn, $select)){
                $result = 1;
            }
            else{
                $result = 0;
            }
            mysqli_close($conn);
        }
        return $result;
    }
    $server->register(
        'setSolucionFaltante',
        array(
            'FALTANTES_ID'=>'xsd:int',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:int'),
        $namespace,
        false,
        'rpc',
        false,
        'Finaliza la solucion del faltante indicado');