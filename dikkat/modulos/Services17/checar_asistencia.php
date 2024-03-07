<?php
function InsertarChecada($USUARIO_ID, $SUCURSAL_ID, $TIPO, $HORA, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn) {
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

        $FechaYHora = "";
        date_default_timezone_set('America/Mexico_City');

        $FechaYHora = date("Y-m-d");
        $FechaYHora = $FechaYHora . " " . $HORA;
        $query   = "INSERT INTO CHECADA (USUARIO_ID,SUCURSAL_ID,FECHA,TIPO) ";
        $query  .= " VALUES ($USUARIO_ID,$SUCURSAL_ID,'$FechaYHora' ,'$TIPO');";
        //echo $query;
        if (mysqli_query($conn, $query)) {
            $result = true;
            mysqli_commit($conn);
        } else {
            mysqli_rollback($conn);
            $result = false;
        }
        mysqli_close($conn);
    } else {
        // FALLO LA CONEXION
        $result = false;
    }
    return $result;
}
function MostrarChecadas($USUARIO_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    if ($conn) {
        $select  = "SELECT CHECADA_ID, USUARIO_ID, SUCURSAL_ID, FECHA, TIPO FROM CHECADA ";
        $select .= " WHERE USUARIO_ID=$USUARIO_ID";
        $select .= " AND DATE(FECHA)=DATE(NOW( ))";

        $stmt = mysqli_query($conn, $select);

        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $checada["CHECADA_ID"]  = $row["CHECADA_ID"];
                $checada["USUARIO_ID"]  = $row["USUARIO_ID"];
                $checada["SUCURSAL_ID"] = $row["SUCURSAL_ID"];
                $checada["FECHA"]       = $row["FECHA"];
                $checada["TIPO"]        = $row["TIPO"];
                $result[]              = $checada;
            }

            mysqli_close($conn);
            //print_r($result);
            return $result;
        } else {

            mysqli_close($conn);
            return null;
        }
        mysqli_close($conn);
    } else {
        // FALLO LA CONEXION
        return null;
    }
}

$server->wsdl->addComplexType(
    'MostrarChecadas',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'CHECADA_ID'    => array('name' => 'CHECADA_ID', 'type' => 'xsd:int'),
        'USUARIO_ID'    => array('name' => 'USUARIO_ID', 'type' => 'xsd:int'),
        'SUCURSAL_ID'   => array('name' => 'SUCURSAL_ID', 'type' => 'xsd:int'),
        'FECHA'         => array('name' => 'FECHA',      'type' => 'xsd:string'),
        'TIPO'          => array('name' => 'TIPO',       'type' => 'xsd:string')
    )
);

$server->wsdl->addComplexType(
    'MostrarChecadasArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:MostrarChecadas[]')),
    'tns:MostrarChecadas'
);
$server->register(
    'MostrarChecadas',
    array(
        'USUARIO_ID' => 'xsd:int',
        'BD'=>'xsd:string'
    ),
    array('return' => 'tns:MostrarChecadasArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los datos de las checadas del usuario el dia de hoy'
);

$server->register(
    'InsertarChecada',
    array(
        'USUARIO_ID' => 'xsd:int',
        'SUCURSAL_ID' => 'xsd:int',
        'TIPO' => 'xsd:string',
        'HORA' => 'xsd:string',
        'BD'=>'xsd:string'
    ),
    array('return' => 'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Inserta Checada de asistencia'
);
