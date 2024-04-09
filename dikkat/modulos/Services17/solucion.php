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

            $i = mysqli_num_rows($stmt);
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


function MostrarFaltantesPendiente($FALTANTES_ID, $SUCURSAL_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    $hostname = $_SERVER['SERVER_NAME'];
    if ($conn) {
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">
        $select = "SELECT F.FALTANTES_ID,FD.FALTANTES_DETALLE_ID,A.ARTICULO_ID,A.SKU,A.NOMBRE,A.DESCRIPCION,FD.STOCK_FISICO,FD.PRECIO_ARTICULO,A.IMAGEN,D.NOMBRE AS CATEGORIA ";
        $select .= " FROM FALTANTES AS F ";
        $select .= " INNER JOIN FALTANTES_DETALLE AS FD ON F.FALTANTES_ID =FD.FALTANTES_ID ";
        $select .= " INNER JOIN ARTICULOS AS A ON FD.ARTICULO_ID=A.ARTICULO_ID ";
        $select .= " INNER JOIN CATEGORIAS AS C ON A.CATEGORIA_ID=C.CATEGORIA_ID";
        $select .= " INNER JOIN DEPARTAMENTOS AS D ON C.DEPARTAMENTO_ID=D.DEPARTAMENTO_ID ";
        $select .= " WHERE F.FALTANTES_ID='$FALTANTES_ID' AND F.SUCURSAL_ID=$SUCURSAL_ID AND F.ESTATUS ='P' ORDER BY FD.FALTANTES_DETALLE_ID DESC;";
        // </editor-fold>    
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $faltante["FALTANTES_ID"] = $row["FALTANTES_ID"];
                $faltante["FALTANTES_DETALLE_ID"] = $row["FALTANTES_DETALLE_ID"];
                $faltante["ARTICULO_ID"] = $row["ARTICULO_ID"];
                $faltante["SKU"] = $row["SKU"];
                $faltante["NOMBRE"] = $row["NOMBRE"];
                $faltante["DESCRIPCION"] = $row["DESCRIPCION"];
                $faltante["STOCK_FISICO"] = $row["STOCK_FISICO"];
                $faltante["PRECIO_ARTICULO"] = $row["PRECIO_ARTICULO"];
                $faltante["IMAGEN"] = $hostname . "/articulos/" . $row["IMAGEN"];
                $faltante["CATEGORIA"] = $row["CATEGORIA"];
                $faltante["SOLUCION"] = "";
                $result[] = $faltante;
            }
            mysqli_close($conn);
            return $result;
        } else {
            mysqli_close($conn);
            return $result;
        }
        mysqli_close($conn);
    } else {
        // FALLO LA CONEXION
        return $result;
    }
}


$server->wsdl->addComplexType(
    'MostrarSolucion',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'FALTANTES_ID' => array('name' => 'FALTANTES_ID', 'type' => 'xsd:int'),
        'FALTANTES_DETALLE_ID' => array('name' => 'FALTANTES_DETALLE_ID', 'type' => 'xsd:int'),
        'ARTICULO_ID' => array('name' => 'ARTICULO_ID', 'type' => 'xsd:int'),
        'SKU' => array('name' => 'SKU', 'type' => 'xsd:string'),
        'NOMBRE' => array('name' => 'NOMBRE', 'type' => 'xsd:string'),
        'DESCRIPCION' => array('name' => 'DESCRIPCION', 'type' => 'xsd:string'),
        'STOCK_FISICO' => array('name' => 'STOCK_FISICO', 'type' => 'xsd:string'),
        'PRECIO_ARTICULO' => array('name' => 'PRECIO_ARTICULO', 'type' => 'xsd:string'),
        'IMAGEN' => array('name' => 'IMAGEN', 'type' => 'xsd:string'),
        'CATEGORIA' => array('name' => 'CATEGORIA', 'type' => 'xsd:string'),
        'SOLUCION' => array('name' => 'SOLUCION', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'MostrarSolucionArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:MostrarSolucion[]')),
    'tns:MostrarSolucion'
);
$server->register(
    'MostrarFaltantesPendiente',
    array(
        'FALTANTES_ID' => 'xsd:string',
        'SUCURSAL_ID' => 'xsd:int',
        'BD' => 'xsd:string'
    ),
    array('return' => 'tns:MostrarSolucionArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los articulos faltanes en el sistema en una fecha y sucursal determinada'
);