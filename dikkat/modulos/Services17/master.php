<?php

function getClaveMaestraCliente($BD)
{
    $CLAVE_CLIENTE_MASTER = "";
    $conn = ABRIR_CONEXION_MYSQL(FALSE, DB_MASTER);
    if ($conn) {
        $select_Clave = "select CLAVE FROM CLIENTES WHERE NOMBRE_DB = '$BD'";
        $stmt = mysqli_query($conn, $select_Clave);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $CLAVE_CLIENTE_MASTER = $row["CLAVE"];
            }
        }
    } else {
        $CLAVE_CLIENTE_MASTER = null;
    }
    mysqli_close($conn);
    return $CLAVE_CLIENTE_MASTER;
}

$server->register(
    'getClaveMaestraCliente',
    array('DB' => 'xsd:string'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    false,
    'Funcion que devuelve la clave de una base de datos'
);



function getModulosCliente($BD)
{
    $result = null;
    $conn = ABRIR_CONEXION_MYSQL(FALSE, DB_MASTER);
    if ($conn) {
        $select_Clave = "select m.NOM_MODULO, m.CLAVE  ";
        $select_Clave .= " from CLIENTES_MODULOS cm ";
        $select_Clave .= " join MODULOS m on m.MODULO_ID = cm.MODULO_ID ";
        $select_Clave .= " join CLIENTES c on c.CLIENTE_ID = cm.CLIENTE_ID ";
        $select_Clave .= " where c.NOMBRE_DB = '$BD' order by m.MODULO_ID ASC";
        //echo $select;
        $stmt = mysqli_query($conn, $select_Clave);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $Modulos["NOM_MODULO"] = $row["NOM_MODULO"];
                $Modulos["CLAVE"] = $row["CLAVE"];
                $result[] = $Modulos;
            }
        } else {
            $result = null;
        }
        mysqli_close($conn);
    } else {
        $result = null;
    }
    return $result;
}

$server->register(
    'getModulosCliente',
    array(

        'BD' => 'xsd:string'
    ),
    array('return' => 'tns:ModulosArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Funcion que devuelve los modulos de un cliente'
);


$server->wsdl->addComplexType(
    'Modulos',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'NOM_MODULO' => array('name' => 'NOM_MODULO', 'type' => 'xsd:string'),
        'CLAVE' => array('name' => 'CLAVE', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'ModulosArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:Modulos[]')),
    'tns:Modulos'
);
