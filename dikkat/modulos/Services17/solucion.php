<?php

function getSolucionPendiente($SUCURSAL_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $faltante_id = 0;
    if ($conn) {

        $select = " SELECT FALTANTES_ID FROM FALTANTES ";
        $select .= " where SUCURSAL_ID = $SUCURSAL_ID ";
        $select .= " and ESTATUS ='P' ";
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $faltantes = $row["FALTANTES_ID"];
                $result = $faltantes;
            }
        } else {
            $faltante_id = 0;
        }
        mysqli_close($conn);
    } else {
        $faltante_id = 0;
    }
    return $result;
}
$server->register(
    'getSolucionPendiente',
    array(
        'SUCURSAL_ID' => 'xsd:int',
        'BD' => 'xsd:string'
    ),
    array('return' => 'xsd:int'),
    $namespace,
    false,
    'rpc',
    false,
    'Retorna el id de faltantes que no tiene solucion'
);


function setSolucionFaltante($FALTANTES_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = 0;
    if ($conn) {

        $select = " UPDATE FALTANTES ";
        $select .= " SET ESTATUS ='F' ";
        $select .= " WHERE FALTANTES_ID =$FALTANTES_ID ";
        if (mysqli_query($conn, $select)) {
            $result = 1;
        } else {
            $result = 0;
        }
        mysqli_close($conn);
    }
    return $result;
}
$server->register(
    'setSolucionFaltante',
    array(
        'FALTANTES_ID' => 'xsd:int',
        'BD' => 'xsd:string'
    ),
    array('return' => 'xsd:int'),
    $namespace,
    false,
    'rpc',
    false,
    'Finaliza la solucion del faltante indicado'
);




function getSolucionOpciones($BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $faltante["SOLUCION_OPCIONES_ID"] = "0";
    $SolucionOpciones[] = $faltante;
    if ($conn) {
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">
        $select = "SELECT * FROM SOLUCION_OPCIONES WHERE ESTATUS = 'A'";
        // </editor-fold>    
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {

            $i =  mysqli_num_rows($stmt);
            if ($i > 0)
                unset($SolucionOpciones);

            while ($row = mysqli_fetch_assoc($stmt)) {
                $faltante["SOLUCION_OPCIONES_ID"] = $row["SOLUCION_OPCIONES_ID"];
                $faltante["NOMBRE"] = $row["NOMBRE"];
                $faltante["CLAVE"] = $row["CLAVE"];
                $SolucionOpciones[] = $faltante;
            }
            mysqli_close($conn);
            return $SolucionOpciones;
        } else {
            mysqli_close($conn);
            return $SolucionOpciones;
        }
        
    } else {
        // FALLO LA CONEXION
        return $SolucionOpciones;
    }
}

$server->wsdl->addComplexType(
    'SolucionOpciones',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'SOLUCION_OPCIONES_ID' => array('name' => 'SOLUCION_OPCIONES_ID', 'type' => 'xsd:int'),
        'NOMBRE' => array('name' => 'NOMBRE', 'type' => 'xsd:string'),
        'CLAVE' => array('name' => 'CLAVE', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'SolucionOpcionesArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:SolucionOpciones[]')),
    'tns:SolucionOpciones'
);
$server->register(
    'getSolucionOpciones',
    array(
        
        'BD' => 'xsd:string'
    ),
    array('return' => 'tns:SolucionOpcionesArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los articulos faltanes en el sistema en una fecha y sucursal determinada'
);