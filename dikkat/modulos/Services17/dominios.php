<?php
function MostrarDominios()
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $Dominios = null;
    if ($conn) {

        $select  = "SELECT DOMINIO_ID, NOMBRE, RUTA FROM dominios where estatus = 'A';";

        $stmt = mysqli_query($conn, $select);

        if ($stmt) {

            while ($row = mysqli_fetch_assoc($stmt)) {
                $dom["DOMINIO_ID"] = $row["DOMINIO_ID"];
                $dom["NOMBRE"] = $row["NOMBRE"];
                $dom["RUTA"] = $row["RUTA"];
                $Dominios[] = $dom;
            }
        } else {

            mysqli_close($conn);
            return null;
        }
    } else {
        return null;
    }
    mysqli_close($conn);
    return $Dominios;
}
function InsertarDominio($NOMBRE, $RUTA)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn) {
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        $query  = "INSERT INTO dominios (NOMBRE,RUTA,ESTATUS) ";
        $query .= "VALUES('$NOMBRE','$RUTA','A');";
        if (mysqli_query($conn, $query)) {
            $result = true;
            mysqli_commit($conn);
        } else {
            mysqli_rollback($conn);
            $result = false;
        }
        mysqli_close($conn);
    } else {
        $result = false;
    }
    return $result;
}
function ActualizarDominio($DOMINIO_ID, $NOMBRE, $RUTA, $ESTATUS)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn) {
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        $query  = "UPDATE dominios SET NOMBRE = $NOMBRE,RUTA = $RUTA,ESTATUS = $ESTATUS WHERE DOMINIO_ID = $DOMINIO_ID;";
        if (mysqli_query($conn, $query)) {
            $result = true;
            mysqli_commit($conn);
        } else {
            mysqli_rollback($conn);
            $result = false;
        }
        mysqli_close($conn);
    } else {
        $result = false;
    }
    return $result;
}
$server->wsdl->addComplexType(
    'MostrarDominios',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'DOMINIO_ID' => array('name' => 'USUARIO_ID', 'type' => 'xsd:int'),
        'NOMBRE' => array('name' => 'NOMBRE', 'type' => 'xsd:string'),
        'RUTA' => array('name' => 'RUTA', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'MostrarDominiosArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:MostrarDominios[]')),
    'tns:MostrarDominios'
);
$server->register(
    'MostrarDominios',
    array(),
    array('return' => 'tns:MostrarDominiosArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los dominios registrados'
);
$server->register(
    'InsertarDominio',
    array(
        'NOMBRE' => 'xsd:string',
        'RUTA' => 'xsd:string'
    ),
    array('return' => 'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Inserta un dominio en la base de datos del sistema'
);

$server->register(
    'ActualizarDominio',
    array(
        'DOMINIO_ID' => 'xsd:int',
        'NOMBRE' => 'xsd:string',
        'RUTA' => 'xsd:string',
        'ESTATUS' => 'xsd:string'
    ),
    array('return' => 'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza el dominio indicado en el sistema'
);
