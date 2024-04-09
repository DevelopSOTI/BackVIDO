<?php
function BuscarFaltanteID2($FECHA, $SUCURSAL_ID, $conn)
{
    //$conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = 0;
    if ($conn) {
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">
        $select = "SELECT FALTANTES_ID FROM FALTANTES WHERE FECHA='$FECHA' AND SUCURSAL_ID=$SUCURSAL_ID ;";
        // </editor-fold>  
        //echo " Consulta ".$select." ";
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $faltante = $row["FALTANTES_ID"];
                $result = $faltante;
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
function BuscarFaltanteDetalleID2($FALTANTES_ID, $ARTICULO_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = 0;
    if ($conn) {
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">
        $select = "SELECT FD.FALTANTES_DETALLE_ID FROM FALTANTES_DETALLE AS FD WHERE FD.FALTANTES_ID=$FALTANTES_ID AND FD.ARTICULO_ID=$ARTICULO_ID;";
        // </editor-fold>    
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $faltante = $row["FALTANTES_DETALLE_ID"];
                $result = $faltante;
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
function InsertarFaltante2($FECHA, $SUCURSAL_ID, $USUARIO_CREACION, $FECHA_HORA_CREACION, $ARTICULO_ID, $STOCK_FISICO, $PRECIO_ARTICULO, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = 0;
    $FALTANTES_ID = 0;
    $FALTANTES_ID = BuscarFaltanteID($FECHA, $SUCURSAL_ID, $conn);
    //echo "Faltantes id". $FALTANTES_ID." ";
    if ($conn) {
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        if (
            $FALTANTES_ID === 0 || (
                ($USUARIO_CREACION === 0 || $USUARIO_CREACION === null || strlen($USUARIO_CREACION) === 0 || is_null($USUARIO_CREACION)) &&
                ($FECHA_HORA_CREACION === 0 || $FECHA_HORA_CREACION === null || strlen($FECHA_HORA_CREACION) === 0 || is_null($FECHA_HORA_CREACION)) &&
                ($ARTICULO_ID === 0 || $ARTICULO_ID === null || strlen($ARTICULO_ID) === 0 || is_null($ARTICULO_ID)) &&
                ($STOCK_FISICO === 0 || $STOCK_FISICO === null || strlen($STOCK_FISICO) === 0 || is_null($STOCK_FISICO)) &&
                ($PRECIO_ARTICULO === 0 || $PRECIO_ARTICULO === null || strlen($PRECIO_ARTICULO) === 0 || is_null($PRECIO_ARTICULO))
            )
        ) {
            //insertamos el encabezado 
            $query = "INSERT INTO FALTANTES (SUCURSAL_ID,ESTATUS,FECHA,USUARIO_CREACION,FECHA_HORA_CREACION) ";
            $query .= "VALUES ($SUCURSAL_ID,'A','$FECHA','$USUARIO_CREACION','$FECHA_HORA_CREACION');";
            if (mysqli_query($conn, $query)) {
                $result = 1;
            } else {
                $result = 0;
            }
            if ($result === 1) {
                $FALTANTES_ID = BuscarFaltanteID($FECHA, $SUCURSAL_ID, $conn);
                //echo "Faltantes id". $FALTANTES_ID." ";
            } else {
                $result = 0;
            }
        } elseif ($FALTANTES_ID === -1) {
            $result = 0;
        }
        $result = $FALTANTES_ID;
        //ECHO $FALTANTES_ID." - "."$USUARIO_CREACION-"."$FECHA_HORA_CREACION-"."$ARTICULO_ID-"."$STOCK_FISICO-"."$PRECIO_ARTICULO";
        if (
            $FALTANTES_ID > 0 && (
                (strlen($USUARIO_CREACION) > 2) &&
                (strlen($FECHA_HORA_CREACION) > 2) &&
                ($ARTICULO_ID > 0) &&
                ($STOCK_FISICO > 0) &&
                ($PRECIO_ARTICULO > 0)
            )
        ) {
            //buscamos el detalle
            $FALTANTES_DETALLE_ID = 0;
            $FALTANTES_DETALLE_ID = BuscarFaltanteDetalleID($FALTANTES_ID, $ARTICULO_ID, $BD);
            //echo " Faltantes detalle_id: ".$FALTANTES_DETALLE_ID." ";
            if ($FALTANTES_DETALLE_ID === 0) {
                //Insertamos el detalle
                $query = "INSERT INTO FALTANTES_DETALLE (FALTANTES_ID,ARTICULO_ID,STOCK_FISICO,PRECIO_ARTICULO) ";
                $query .= " VALUES($FALTANTES_ID,$ARTICULO_ID,$STOCK_FISICO,'$PRECIO_ARTICULO');";
                //echo $query;
                if (mysqli_query($conn, $query)) {
                    $result = $FALTANTES_ID;
                } else {
                    $result = 0;
                }
            } elseif ($FALTANTES_DETALLE_ID === -1 || $FALTANTES_DETALLE_ID > 0) {
                $result = 0;
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
    'InsertarFaltante2',
    array(
        'FECHA' => 'xsd:string',
        'SUCURSAL_ID' => 'xsd:int',
        'USUARIO_CREACION' => 'xsd:string',
        'FECHA_HORA_CREACION' => 'xsd:string',
        'ARTICULO_ID' => 'xsd:string',
        'STOCK_FISICO' => 'xsd:string',
        'PRECIO_ARTICULO' => 'xsd:string',
        'BD' => 'xsd:string'
    ),
    array('return' => 'xsd:int'),
    $namespace,
    false,
    'rpc',
    false,
    'Inserta un articulo de faltante en el sistema'
);


function MostrarFaltantes2($FECHA, $SUCURSAL_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    $hostname = $_SERVER['SERVER_NAME'];
    if ($conn) {
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">
        $select = "SELECT F.FALTANTES_ID,FD.FALTANTES_DETALLE_ID,A.ARTICULO_ID,A.SKU,A.NOMBRE,A.DESCRIPCION,FD.STOCK_FISICO,FD.PRECIO_ARTICULO,A.IMAGEN ";
        $select .= " FROM FALTANTES AS F ";
        $select .= " INNER JOIN FALTANTES_DETALLE AS FD ON F.FALTANTES_ID =FD.FALTANTES_ID ";
        $select .= " INNER JOIN ARTICULOS AS A ON FD.ARTICULO_ID=A.ARTICULO_ID ";
        $select .= " WHERE F.FECHA='$FECHA' AND F.SUCURSAL_ID=$SUCURSAL_ID AND F.ESTATUS ='A' ORDER BY FD.FALTANTES_DETALLE_ID DESC;";
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
    'MostrarFaltantes2',
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
        'IMAGEN' => array('name' => 'IMAGEN', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'MostrarFaltantesArray2',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:MostrarFaltantes2[]')),
    'tns:MostrarFaltantes2'
);
$server->register(
    'MostrarFaltantes2',
    array(
        'FECHA' => 'xsd:string',
        'SUCURSAL_ID' => 'xsd:int',
        'BD' => 'xsd:string'
    ),
    array('return' => 'tns:MostrarFaltantesArray2'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los articulos faltanes en el sistema en una fecha y sucursal determinada'
);

function MostrarArticulos2($ARTICULO_ID, $MARCA_ID, $CATEGORIA_ID, $SKU, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    $where = "";
    $hostname = $_SERVER['SERVER_NAME'];
    if ($conn) {
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LOS ARTÍCULOS EN EL SISTEMA"> 
        if ($ARTICULO_ID > 0) {
            $where = " WHERE ARTICULO_ID=$ARTICULO_ID";
        } elseif ($SKU === 0 || strlen($SKU) > 0) {
            $SKU = trim($SKU, " \t\n\r");
            $where = " WHERE SKU='$SKU';";
        } elseif ($MARCA_ID > 0 && $CATEGORIA_ID > 0) {
            $where = " WHERE MARCA_ID=$MARCA_ID AND CATEGORIA_ID=$CATEGORIA_ID;";
        } elseif ($MARCA_ID > 0 && $CATEGORIA_ID === 0) {
            $where = " WHERE MARCA_ID=$MARCA_ID;";
        } elseif ($MARCA_ID === 0 && $CATEGORIA_ID > 0) {
            $where = " WHERE CATEGORIA_ID=$CATEGORIA_ID;";
        } else {
            $where = ";";
        }
        $select = "SELECT ARTICULO_ID,MARCA_ID,NOMBRE,PRECIO,SKU,DESCRIPCION,IMAGEN,USUARIO_CREADOR,FECHA_HORA_CREACION ";
        $select .= " ,USUARIO_MODIFICACION,FECHA_HORA_MODIFICACION,CATEGORIA_ID ";
        $select .= " FROM ARTICULOS " . $where;
        // </editor-fold>
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $roles["ARTICULO_ID"] = $row["ARTICULO_ID"];
                $roles["MARCA_ID"] = $row["MARCA_ID"];
                $roles["NOMBRE"] = $row["NOMBRE"];
                $roles["PRECIO"] = $row["PRECIO"];
                $roles["SKU"] = $row["SKU"];
                $roles["DESCRIPCION"] = $row["DESCRIPCION"];
                $roles["IMAGEN"] = $hostname . "/articulos/" . $row["IMAGEN"];
                $roles["USUARIO_CREADOR"] = $row["USUARIO_CREADOR"];
                $roles["FECHA_HORA_CREACION"] = $row["FECHA_HORA_CREACION"];
                $roles["USUARIO_MODIFICACION"] = $row["USUARIO_MODIFICACION"];
                $roles["FECHA_HORA_MODIFICACION"] = $row["FECHA_HORA_MODIFICACION"];
                $roles["CATEGORIA_ID"] = $row["CATEGORIA_ID"];
                $result[] = $roles;
            }
            mysqli_close($conn);
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
    'MostrarArticulos2',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'ARTICULO_ID' => array('name' => 'ARTICULO_ID', 'type' => 'xsd:int'),
        'MARCA_ID' => array('name' => 'MARCA_ID', 'type' => 'xsd:int'),
        'NOMBRE' => array('name' => 'NOMBRE', 'type' => 'xsd:string'),
        'PRECIO' => array('name' => 'PRECIO', 'type' => 'xsd:string'),
        'SKU' => array('name' => 'SKU', 'type' => 'xsd:string'),
        'DESCRIPCION' => array('name' => 'DESCRIPCION', 'type' => 'xsd:string'),
        'IMAGEN' => array('name' => 'IMAGEN', 'type' => 'xsd:string'),
        'USUARIO_CREADOR' => array('name' => 'USUARIO_CREADOR', 'type' => 'xsd:string'),
        'FECHA_HORA_CREACION' => array('name' => 'FECHA_HORA_CREACION', 'type' => 'xsd:string'),
        'USUARIO_MODIFICACION' => array('name' => 'USUARIO_MODIFICACION', 'type' => 'xsd:string'),
        'FECHA_HORA_MODIFICACION' => array('name' => 'FECHA_HORA_MODIFICACION', 'type' => 'xsd:string'),
        'CATEGORIA_ID' => array('name' => 'CATEGORIA_ID', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'MostrarArticulosArray2',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:MostrarArticulos2[]')),
    'tns:MostrarArticulos2'
);
$server->register(
    'MostrarArticulos2',
    array(
        'ARTICULO_ID' => 'xsd:int',
        'MARCA_ID' => 'xsd:int',
        'CATEGORIA_ID' => 'xsd:int',
        'SKU' => 'xsd:string',
        'BD' => 'xsd:string'
    ),
    array('return' => 'tns:MostrarArticulosArray2'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los articulos del sistema (usando "0" en articulo id, marca id y categoría id muestra todos los articulos del sistema)'
);

function ActualizarHoraInicioFinFaltantes2($TIPO, $HORA, $FALTANTES_ID, $USARIO_MODIFICACION, $FECHA_HORA_MODIFICACION, $BD, $FALTA_SIN_SOL = "S")
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
                $TIPO_ACTUALIZACION = "HORA_FIN = '$HORA',";
                if ($FALTA_SIN_SOL == "N")
                    $TIPO_ACTUALIZACION .= " ESTATUS = 'P', ";
                $WHERE_COMPLEMENTO = " AND HORA_FIN IS NULL";
            }
            $query = "UPDATE FALTANTES ";
            $query .= " SET ";
            $query .= " $TIPO_ACTUALIZACION ";
            $query .= " USARIO_MODIFICACION = '$USARIO_MODIFICACION', ";
            $query .= " FECHA_HORA_MODIFICACION ='$FECHA_HORA_MODIFICACION' ";
            $query .= " WHERE FALTANTES_ID = $FALTANTES_ID $WHERE_COMPLEMENTO;";
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
    'ActualizarHoraInicioFinFaltantes2',
    array(
        'TIPO' => 'xsd:string',
        'HORA' => 'xsd:string',
        'FALTANTES_ID' => 'xsd:int',
        'USARIO_MODIFICACION' => 'xsd:string',
        'FECHA_HORA_MODIFICACION' => 'xsd:string',
        'BD' => 'xsd:string'
    ),
    array('return' => 'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza la hora de inicio y la hora de fin de la faltantes'
);

function ExisteFaltantes2($FALTANTES_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    $HORA_INICIO = "";
    if ($conn) {
        $select = "SELECT HORA_INICIO FROM FALTANTES ";
        $select .= " WHERE FALTANTES_ID = $FALTANTES_ID;";
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $HORA_INICIO = $row["HORA_INICIO"];
            }
            if (STRLEN($HORA_INICIO) > 0) {
                $result = true;
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        mysqli_close($conn);
    } else {
        // FALLO LA CONEXION
        $result = false;
    }
    return $result;
}
$server->register(
    'ExisteFaltantes2',
    array(
        'FALTANTES_ID' => 'xsd:int',
        'BD' => 'xsd:string'
    ),
    array('return' => 'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Verifica si existe una tarea de faltantes'
);

function MuestraHoraInicioFinFaltantes2($FALTANTES_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    if ($conn) {
        $select = "SELECT HORA_INICIO,HORA_FIN FROM FALTANTES ";
        $select .= " WHERE FALTANTES_ID = $FALTANTES_ID;";
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
    'MuestraHoraInicioFinFaltantes2',
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
    'MuestraHoraInicioFinFaltantesArray2',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:MuestraHoraInicioFinFaltantes2[]')),
    'tns:MuestraHoraInicioFinFaltantes2'
);
$server->register(
    'MuestraHoraInicioFinFaltantes2',
    array(
        'FALTANTES_ID' => 'xsd:int',
        'BD' => 'xsd:string'
    ),
    array('return' => 'tns:MuestraHoraInicioFinFaltantesArray2'),
    $namespace,
    false,
    'rpc',
    false,
    'Muestra la hora de inicio y fin de la tarea de faltantes'
);

function MuestraBitacora2($SUCURSAL_ID, $FECHA, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = "";
    if ($conn) {
        $hostname = $_SERVER['SERVER_NAME'];
        $select = "SELECT B.IMAGEN FROM BITACORA AS B ";
        $select .= " INNER JOIN SUCURSALES AS S ON B.CLIENTE_ID=S.CLIENTE_ID ";
        $select .= " WHERE ";
        $select .= " CONVERT(B.FECHA_REVISION, DATE)='$FECHA' AND S.SUCURSAL_ID=$SUCURSAL_ID ";
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $caducidades = $row["IMAGEN"];
                $result = $hostname . "/" . $caducidades;
            }
        } else {
            $result = "";
        }
        mysqli_close($conn);
    } else {
        // FALLO LA CONEXION
        $result = "";
    }
    return $result;
}
$server->register(
    'MuestraBitacora2',
    array(
        'SUCURSAL_ID' => 'xsd:int',
        'FECHA' => 'xsd:string',
        'BD' => 'xsd:string'
    ),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    false,
    'Muestra la hora de inicio y fin de la tarea de faltantes'
);