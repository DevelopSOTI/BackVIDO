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
        $select = "SELECT ";
        $select .= "    S.*, ";
        $select .= "    E.EXISTENCIA AS EXISTENCIA_TEORICA, ";
        $select .= "    E.FECHA_ULT_RECIBO, ";
        $select .= "    E.CAPACIDAD_EMPAQUE ";
        $select .= "FROM ( ";
        $select .= "    SELECT ";
        $select .= "        F.FALTANTES_ID, ";
        $select .= "        FD.FALTANTES_DETALLE_ID, ";
        $select .= "        A.ARTICULO_ID, ";
        $select .= "        A.SKU, ";
        $select .= "        A.NOMBRE, ";
        $select .= "        A.DESCRIPCION, ";
        $select .= "        FD.STOCK_FISICO, ";
        $select .= "        FD.PRECIO_ARTICULO, ";
        $select .= "        A.IMAGEN, ";
        $select .= "        IFNULL(SP.NOMBRE,'') AS SOLUCION, ";
        $select .= "        F.FECHA, ";
        $select .= "        ( ";
        $select .= "            SELECT E.EXISTENCIA_ID ";
        $select .= "            FROM EXISTENCIAS E ";
        $select .= "            WHERE E.ARTICULO_ID = FD.ARTICULO_ID ";
        $select .= "            AND E.SUCURSAL_ID = F.SUCURSAL_ID ";
        $select .= "            AND E.FECHA >= F.FECHA ";
        $select .= "            ORDER BY E.FECHA ";
        $select .= "            LIMIT 1 ";
        $select .= "        ) AS EXISTENCIA_ID ";
        $select .= "    FROM ";
        $select .= "        FALTANTES AS F ";
        $select .= "        INNER JOIN FALTANTES_DETALLE AS FD ON F.FALTANTES_ID = FD.FALTANTES_ID ";
        $select .= "        INNER JOIN ARTICULOS AS A ON FD.ARTICULO_ID = A.ARTICULO_ID ";
        $select .= "        LEFT JOIN SOLUCION SOL ON SOL.FALTANTES_ID = F.FALTANTES_ID ";
        $select .= "        LEFT JOIN SOLUCION_DETALLE SD ON SD.SOLUCION_ID = SOL.SOLUCION_ID AND SD.ARTICULO_ID = FD.ARTICULO_ID ";
        $select .= "        LEFT JOIN SOLUCION_OPCIONES SP ON SP.SOLUCION_OPCIONES_ID = SD.SOLUCION_OPCIONES_ID ";
        $select .= "    WHERE ";
        $select .= "        F.FALTANTES_ID = $FALTANTES_ID ";
        $select .= "        AND F.SUCURSAL_ID = $SUCURSAL_ID ";
        $select .= "        AND (F.ESTATUS = 'P' OR F.ESTATUS = 'F') ";
        $select .= ") AS S ";
        $select .= "JOIN EXISTENCIAS E ON (S.EXISTENCIA_ID = E.EXISTENCIA_ID) ";
        $select .= "WHERE ";
        $select .= "    (E.EXISTENCIA - S.STOCK_FISICO) > 0 ";
        $select .= "ORDER BY ";
        $select .= "    S.FALTANTES_DETALLE_ID DESC;";

        //echo $select;
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
                $faltante["SOLUCION"] = $row["SOLUCION"];
                $faltante["FECHA"] = $row["FECHA"];
                $faltante["EXISTENCIA_TEORICA"] = $row["EXISTENCIA_TEORICA"];
                $faltante["FECHA_ULT_RECIBO"] = $row["FECHA_ULT_RECIBO"];
                $faltante["CAPACIDAD_EMPAQUE"] = $row["CAPACIDAD_EMPAQUE"];
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
        'SOLUCION' => array('name' => 'SOLUCION', 'type' => 'xsd:string'),
        'FECHA' => array('name' => 'FECHA', 'type' => 'xsd:string'),
        'EXISTENCIA_TEORICA' => array('name' => 'EXISTENCIA_TEORICA', 'type' => 'xsd:string'),
        'FECHA_ULT_RECIBO' => array('name' => 'FECHA_ULT_RECIBO', 'type' => 'xsd:string'),
        'CAPACIDAD_EMPAQUE' => array('name' => 'CAPACIDAD_EMPAQUE', 'type' => 'xsd:string')

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





function InsertarSolucion($FECHA, $SUCURSAL_ID, $FALTANTES_ID, $USUARIO_CREACION, $FECHA_HORA_CREACION, $ARTICULO_ID, $SOLUCION_OPCIONES_ID, $SOLUCIONADO,  $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = 0;
    $SOLUCION_ID = 0;
    $SOLUCION_ID = BuscarSolucionID($FECHA, $SUCURSAL_ID, $FALTANTES_ID, $conn);
    //echo "Faltantes id". $FALTANTES_ID." ";
    if ($conn) {
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        if (
            $SOLUCION_ID === 0 || (
                    /*($USUARIO_CREACION===0||$USUARIO_CREACION===null||strlen($USUARIO_CREACION)===0||is_null($USUARIO_CREACION) )&&
                    ($FECHA_HORA_CREACION===0||$FECHA_HORA_CREACION===null||strlen($FECHA_HORA_CREACION)===0||is_null($FECHA_HORA_CREACION) )&&*/
                ($ARTICULO_ID === 0 || $ARTICULO_ID === null || strlen($ARTICULO_ID) === 0 || is_null($ARTICULO_ID)) &&
                ($SOLUCION_OPCIONES_ID === 0 || $SOLUCION_OPCIONES_ID === null || strlen($SOLUCION_OPCIONES_ID) === 0 || is_null($SOLUCION_OPCIONES_ID)) &&
                ($FALTANTES_ID === 0 || $FALTANTES_ID === null || strlen($FALTANTES_ID) === 0 || is_null($FALTANTES_ID))
            )
        ) {
            //insertamos el encabezado 
            $query = "INSERT INTO SOLUCION (SUCURSAL_ID,ESTATUS,FECHA,USUARIO_CREACION,FECHA_HORA_CREACION,FALTANTES_ID) ";
            $query .= "VALUES ($SUCURSAL_ID,'A','$FECHA','$USUARIO_CREACION','$FECHA_HORA_CREACION',$FALTANTES_ID);";
            // echo $query;
            if (mysqli_query($conn, $query)) {
                $result = 1;
            } else {
                $result = 0;
            }
            if ($result === 1) {
                $SOLUCION_ID = BuscarSolucionID($FECHA, $SUCURSAL_ID, $FALTANTES_ID, $conn);
                //echo "Faltantes id". $FALTANTES_ID." ";
            } else {
                $result = 0;
            }
        } elseif ($SOLUCION_ID === -1) {
            $result = 0;
        }
        $result = $SOLUCION_ID;
        //ECHO $FALTANTES_ID." - "."$USUARIO_CREACION-"."$FECHA_HORA_CREACION-"."$ARTICULO_ID-"."$STOCK_FISICO-"."$PRECIO_ARTICULO";
        if (
            $SOLUCION_ID > 0 && (
                (strlen($USUARIO_CREACION) > 2) &&
                (strlen($FECHA_HORA_CREACION) > 2) &&
                ($ARTICULO_ID > 0) &&
                ($SOLUCION_OPCIONES_ID >= 0) &&
                ($FALTANTES_ID >= 0)
            )
        ) {
            //buscamos el detalle
            $SOLUCION_DETALLE_ID = 0;
            $SOLUCION_DETALLE_ID = BuscarSolucionDetalleID($SOLUCION_ID, $ARTICULO_ID, $BD);
            //echo " Faltantes detalle_id: ".$FALTANTES_DETALLE_ID." ";
            if ($SOLUCION_DETALLE_ID === 0) {
                //Insertamos el detalle
                $query = "INSERT INTO SOLUCION_DETALLE (ARTICULO_ID,SOLUCION_OPCIONES_ID,SOLUCION_ID, SOLUCIONADO) ";
                $query .= " VALUES($ARTICULO_ID,$SOLUCION_OPCIONES_ID,$SOLUCION_ID,$SOLUCIONADO);";
                //echo $query;
                if (mysqli_query($conn, $query)) {
                    $result = $SOLUCION_ID;
                } else {
                    $result = 0;
                }
            } elseif ($SOLUCION_DETALLE_ID === -1 || $SOLUCION_DETALLE_ID > 0) {
                // Actualizar registro existente
                $query = "UPDATE SOLUCION_DETALLE SET SOLUCION_OPCIONES_ID = $SOLUCION_OPCIONES_ID, SOLUCIONADO = $SOLUCIONADO WHERE SOLUCION_DETALLE_ID = $SOLUCION_DETALLE_ID";
                if (mysqli_query($conn, $query)) {
                    $result = $SOLUCION_DETALLE_ID; // Devuelve el ID del detalle de solución actualizado
                } else {
                    $result = 0;
                }
            }
        }
        if ($result > 0) {
            mysqli_commit($conn);
        } else {
            mysqli_rollback($conn);
        }
        mysqli_close($conn);
    } else {
        // FALLO LA CONEXION
        $result = false;
    }
    return $result;
}
$server->register(
    'InsertarSolucion',
    array(
        'FECHA' => 'xsd:string',
        'SUCURSAL_ID' => 'xsd:int',
        'FALTANTES_ID' => 'xsd:int',
        'USUARIO_CREACION' => 'xsd:string',
        'FECHA_HORA_CREACION' => 'xsd:string',
        'ARTICULO_ID' => 'xsd:string',
        'SOLUCION_OPCIONES_ID' => 'xsd:int',
        'SOLUCIONADO' => 'xsd:int',
        'BD' => 'xsd:string'

    ),
    array('return' => 'xsd:int'),
    $namespace,
    false,
    'rpc',
    false,
    'Inserta un articulo de una solucion en el sistema'
);


function BuscarSolucionID($FECHA, $SUCURSAL_ID, $FALTANTES_ID, $conn)
{
    //$conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = 0;
    if ($conn) {
        $select = "SELECT SOLUCION_ID FROM SOLUCION WHERE FECHA='$FECHA' AND SUCURSAL_ID=$SUCURSAL_ID AND FALTANTES_ID = $FALTANTES_ID;";
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">

        //echo $select;
        // </editor-fold>  
        //echo " Consulta ".$select." ";
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $solucion = $row["SOLUCION_ID"];
                $result = $solucion;
            }
            //mysqli_close($conn);
            return $result;
        } else {
            //mysqli_close($conn);
            return 0;
        }
        //mysqli_close($conn);
    } else {
        // FALLO LA CONEXION
        return -1;
    }
}

function BuscarSolucionIDs($FECHA, $SUCURSAL_ID, $FALTANTES_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = 0;
    if ($conn) {
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">
        $select = "SELECT SOLUCION_ID FROM SOLUCION ";
        $select .= "WHERE SUCURSAL_ID=$SUCURSAL_ID AND FALTANTES_ID = $FALTANTES_ID ";
        if ($FECHA !== "0") {
            $select .= " AND FECHA='$FECHA' ";
        }
        $select .= "ORDER BY SOLUCION_ID DESC LIMIT 1";

        //echo $select;
        // </editor-fold>  
        //echo " Consulta ".$select." ";
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $solucion = $row["SOLUCION_ID"];
                $result = $solucion;
            }
            //mysqli_close($conn);
            return $result;
        } else {
            //mysqli_close($conn);
            return 0;
        }
        //mysqli_close($conn);
    } else {
        // FALLO LA CONEXION
        return -1;
    }
}

$server->register(
    'BuscarSolucionIDs',
    array(
        'FECHA' => 'xsd:string',
        'SUCURSAL_ID' => 'xsd:int',
        'FALTANTES_ID' => 'xsd:int',
        'BD' => 'xsd:string'

    ),
    array('return' => 'xsd:int'),
    $namespace,
    false,
    'rpc',
    false,
    'busca la solucion id'
);


function BuscarSolucionDetalleID($SOLUCION_ID, $ARTICULO_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = 0;
    if ($conn) {
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">
        $select = "SELECT SD.SOLUCION_DETALLE_ID FROM SOLUCION_DETALLE AS SD WHERE SD.SOLUCION_ID=$SOLUCION_ID AND SD.ARTICULO_ID=$ARTICULO_ID;";
        // </editor-fold>    
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $solucuon = $row["SOLUCION_DETALLE_ID"];
                $result = $solucuon;
            }
            mysqli_close($conn);
            return $result;
        } else {
            mysqli_close($conn);
            return 0;
        }
        mysqli_close($conn);
    } else {
        // FALLO LA CONEXION
        return -1;
    }
}



function ActualizarHoraInicioFinSolucion($TIPO, $HORA, $FALTANTES_ID, $SOLUCION_ID, $USUARIO_MODIFICACION, $FECHA_HORA_MODIFICACION, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    $TIPO_ACTUALIZACION = "";
    $WHERE_COMPLEMENTO = "";
    if (is_null($HORA) || strlen($HORA) === 0) {

    } else {
        if ($conn) {
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
            if ($TIPO === "I") {
                $TIPO_ACTUALIZACION = "HORA_INICIO ='$HORA',";
                $WHERE_COMPLEMENTO = " AND HORA_INICIO IS NULL";
            } elseif ($TIPO === "F") {
                $TIPO_ACTUALIZACION = "HORA_FIN = '$HORA', ";
                $WHERE_COMPLEMENTO = " AND HORA_FIN IS NULL";
            }
            $query = "UPDATE SOLUCION ";
            $query .= " SET ";
            $query .= " $TIPO_ACTUALIZACION ";
            $query .= " USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
            $query .= " FECHA_HORA_MODIFICACION ='$FECHA_HORA_MODIFICACION' ";
            $query .= " WHERE SOLUCION_ID = $SOLUCION_ID AND FALTANTES_ID = $FALTANTES_ID $WHERE_COMPLEMENTO;";
            // echo $query;
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
    }
    return $result;
}
$server->register(
    'ActualizarHoraInicioFinSolucion',
    array(
        'TIPO' => 'xsd:string',
        'HORA' => 'xsd:string',
        'FALTANTES_ID' => 'xsd:int',
        'SOLUCION_ID' => 'xsd:int',
        'USUARIO_MODIFICACION' => 'xsd:string',
        'FECHA_HORA_MODIFICACION' => 'xsd:string',
        'BD' => 'xsd:string'
    ),
    array('return' => 'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza la hora de inicio y la hora de fin de solucion'
);

function MuestraHoraInicioFinSolucion($SOLUCION_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    if ($conn) {
        $select = "SELECT HORA_INICIO,HORA_FIN FROM SOLUCION ";
        $select .= " WHERE SOLUCION_ID = $SOLUCION_ID;";
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $caducidades["HORA_INICIO"] = $row["HORA_INICIO"];
                $caducidades["HORA_FIN"] = $row["HORA_FIN"];
                $result[] = $caducidades;
            }
        } else {
            $result = null;
        }
        mysqli_close($conn);
    } else {
        // FALLO LA CONEXION
        $result = null;
    }
    return $result;
}
$server->wsdl->addComplexType(
    'MuestraHoraInicioFinSolucion',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'HORA_INICIO' => array('name' => 'HORA_INICIO', 'type' => 'xsd:string'),
        'HORA_FIN' => array('name' => 'HORA_FIN', 'type' => 'xsd:string')
    )
);

$server->wsdl->addComplexType(
    'MuestraHoraInicioFinSolucionArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:MuestraHoraInicioFinSolucion[]')),
    'tns:MuestraHoraInicioFinSolucion'
);
$server->register(
    'MuestraHoraInicioFinSolucion',
    array(
        'SOLUCION_ID' => 'xsd:int',
        'BD' => 'xsd:string'
    ),
    array('return' => 'tns:MuestraHoraInicioFinSolucionArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Muestra la hora de inicio y fin de la tarea de faltantes'
);


function getSolucionPendientes($SUCURSAL_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    if ($conn) {

        $select = " SELECT FALTANTES_ID FROM FALTANTES ";
        $select .= " where SUCURSAL_ID = $SUCURSAL_ID ";
        $select .= " and ESTATUS ='P' ";
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $faltantes = $row["FALTANTES_ID"];
                $result[] = $faltantes;
            }
        } else {
            $result = 0;
        }
        mysqli_close($conn);
    } else {
        $result = 0;
    }
    return $result;
}
$server->wsdl->addComplexType(
    'MostrarPendientes',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'FALTANTES_ID' => array('name' => 'FALTANTES_ID', 'type' => 'xsd:int'),        
        'FECHA' => array('name' => 'FECHA', 'type' => 'xsd:string'),
        'HORA_INICIO' => array('name' => 'EXISTENCIA_TEORICA', 'type' => 'xsd:string'),
        'HORA_FIN' => array('name' => 'FECHA_ULT_RECIBO', 'type' => 'xsd:string')

    )
);
$server->wsdl->addComplexType(
    'MostrarPendientesArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:MostrarPendientes[]')),
    'tns:MostrarPendientes'
);
$server->register(
    'getSolucionPendientes',
    array(
        
        'SUCURSAL_ID' => 'xsd:int',
        'BD' => 'xsd:string'
    ),
    array('return' => 'tns:MostrarPendientesArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los articulos faltanes en el sistema en una fecha y sucursal determinada'
);
