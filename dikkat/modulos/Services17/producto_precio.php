<?php
function MostrarDetalleProductoPrecio($SUCURSAL_ID, $FECHA, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
    $result = null;
    if ($conn) {
        $select  = "SELECT AD.ASIGNACION_DETALLE_ID,AR.SKU,AR.NOMBRE,AR.DESCRIPCION,AD.ESTATUS,AD.SENALIZADO,A.SISTEMA_ORIGEN,CC.USUARIO_ASIGNADO ";
        $select .= "FROM ASIGNACIONES AS A ";
        $select .= "INNER JOIN ASIGNACIONES_DETALLE AS AD ON A.ASIGNACION_ID=AD.ASIGNACION_ID ";
        $select .= "INNER JOIN ARTICULOS AS AR ON AD.ARTICULO_ID=AR.ARTICULO_ID ";
        $select .= "LEFT JOIN CHEQUEO_COMPETENCIA_DETALLE AS CCD ON CCD.ASIGNACION_ID =A.ASIGNACION_ID ";
        $select .= "LEFT JOIN CHEQUEO_COMPETENCIA AS CC ON CCD.CHEQUEO_COMPETENCIA_ID= CC.CHEQUEO_COMPETENCIA_ID ";
        $select .= "WHERE AD.SUCURSAL_ID=$SUCURSAL_ID AND A.FECHA='$FECHA' AND A.ESTATUS='A';";
        $stmt = mysqli_query($conn, $select);

        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $articulos["ASIGNACION_DETALLE_ID"] = $row["ASIGNACION_DETALLE_ID"];
                $articulos["SKU"]                   = $row["SKU"];
                $articulos["NOMBRE"]                = $row["NOMBRE"];
                $articulos["DESCRIPCION"]           = $row["DESCRIPCION"];
                $articulos["ESTATUS"]               = $row["ESTATUS"];
                $articulos["SENALIZADO"]            = $row["SENALIZADO"];
                $articulos["SISTEMA_ORIGEN"]        = $row["SISTEMA_ORIGEN"];
                $articulos["USUARIO_ASIGNADO"]      = $row["USUARIO_ASIGNADO"];
                $result[] = $articulos;
            }

            mysqli_close($conn);
            return $result;
        } else {

            mysqli_close($conn);
            return null;
        }
        mysqli_close($conn);
    } else {
        return null;
    }
}
function MostrarDetalleProductoPrecioChequeo($SUCURSAL_ID, $FECHA, $USUARIO,$BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
    $result = null;
    if ($conn) {
        $select  = "SELECT AD.ASIGNACION_DETALLE_ID,AR.SKU,AR.NOMBRE,AR.DESCRIPCION,AD.ESTATUS,AD.SENALIZADO,A.SISTEMA_ORIGEN,CC.USUARIO_ASIGNADO ";
        $select .= "FROM ASIGNACIONES AS A ";
        $select .= "INNER JOIN ASIGNACIONES_DETALLE AS AD ON A.ASIGNACION_ID=AD.ASIGNACION_ID ";
        $select .= "INNER JOIN ARTICULOS AS AR ON AD.ARTICULO_ID=AR.ARTICULO_ID ";
        $select .= "INNER JOIN CHEQUEO_COMPETENCIA_DETALLE AS CCD ON CCD.ASIGNACION_ID =A.ASIGNACION_ID ";
        $select .= "INNER JOIN CHEQUEO_COMPETENCIA AS CC ON CCD.CHEQUEO_COMPETENCIA_ID= CC.CHEQUEO_COMPETENCIA_ID ";
        $select .= "WHERE AD.SUCURSAL_ID=$SUCURSAL_ID AND A.FECHA='$FECHA' AND CC.USUARIO_ASIGNADO ='$USUARIO' AND A.ESTATUS='A' ";
        $select .= "group by AD.ASIGNACION_DETALLE_ID,AR.SKU,AR.NOMBRE,AR.DESCRIPCION,AD.ESTATUS,AD.SENALIZADO,A.SISTEMA_ORIGEN,CC.USUARIO_ASIGNADO;";
        $stmt = mysqli_query($conn, $select);
//echo $select;
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $articulos["ASIGNACION_DETALLE_ID"] = $row["ASIGNACION_DETALLE_ID"];
                $articulos["SKU"]                   = $row["SKU"];
                $articulos["NOMBRE"]                = $row["NOMBRE"];
                $articulos["DESCRIPCION"]           = $row["DESCRIPCION"];
                $articulos["ESTATUS"]               = $row["ESTATUS"];
                $articulos["SENALIZADO"]            = $row["SENALIZADO"];
                $articulos["SISTEMA_ORIGEN"]        = $row["SISTEMA_ORIGEN"];
                $articulos["USUARIO_ASIGNADO"]      = $row["USUARIO_ASIGNADO"];
                $result[] = $articulos;
            }

            mysqli_close($conn);
            return $result;
        } else {

            mysqli_close($conn);
            return null;
        }
        mysqli_close($conn);
    } else {
        return null;
    }
}
function MostrarArticuloProductoPrecio($SKU, $SUCURSAL_ID, $FECHA,$SISTEMA_ORIGEN, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    $hostname = $_SERVER['SERVER_NAME'];
    if ($conn) {
        $select  = "SELECT AD.ASIGNACION_DETALLE_ID,AR.SKU,AR.NOMBRE,AR.DESCRIPCION,PRECIO_ETIQUETA,PRECIO_VERIFICADOR";
        $select .= " ,AD.ESTATUS,AD.SENALIZADO/*,AD.COMPETITIVO*/,AD.ESPACIO_ASIGNADO, AD.EXHIBIDO, AD.PRODUCTO_ETIQUETA,AD.LIMPIEZA, AD.OBSERVACIONES,AR.IMAGEN ";
        $select .= " ,AD.UBICACION_ID, AD.UBICACION_NOMBRE, AD.COMENTARIOS ";
        $select .= " FROM ASIGNACIONES AS A ";
        $select .= " INNER JOIN ASIGNACIONES_DETALLE AS AD ON A.ASIGNACION_ID=AD.ASIGNACION_ID ";
        $select .= " INNER JOIN ARTICULOS AS AR ON AD.ARTICULO_ID=AR.ARTICULO_ID ";
        $select .= " WHERE A.ESTATUS='A' AND AD.SUCURSAL_ID=$SUCURSAL_ID AND A.FECHA='$FECHA' AND AR.SKU='$SKU' AND A.SISTEMA_ORIGEN='$SISTEMA_ORIGEN' ;";
        $stmt = mysqli_query($conn, $select);
//echo $select;
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $articulo["ASIGNACION_DETALLE_ID"]  = $row["ASIGNACION_DETALLE_ID"];
                $articulo["SKU"]                    = $row["SKU"];
                $articulo["NOMBRE"]                 = $row["NOMBRE"];
                $articulo["DESCRIPCION"]            = $row["DESCRIPCION"];
                $articulo["PRECIO_ETIQUETA"]        = $row["PRECIO_ETIQUETA"];
                $articulo["PRECIO_VERIFICADOR"]     = $row["PRECIO_VERIFICADOR"];
                $articulo["ESTATUS"]                = $row["ESTATUS"];
                $articulo["SENALIZADO"]             = $row["SENALIZADO"];
                //$articulo["COMPETITIVO"]            =$row["COMPETITIVO"];
                $articulo["ESPACIO_ASIGNADO"]       = $row["ESPACIO_ASIGNADO"];
                $articulo["EXHIBIDO"]               = $row["EXHIBIDO"];
                $articulo["PRODUCTO_ETIQUETA"]      = $row["PRODUCTO_ETIQUETA"];
                $articulo["LIMPIEZA"]               = $row["LIMPIEZA"];
                $articulo["OBSERVACIONES"]          = $row["OBSERVACIONES"];
                $articulo["IMAGEN"]                 = $hostname . "/articulos/" . $row["IMAGEN"];
                $articulo["UBICACION_ID"]           = $row["UBICACION_ID"];
                $articulo["UBICACION_NOMBRE"]       = $row["UBICACION_NOMBRE"];
                $articulo["COMENTARIOS"]            = $row["COMENTARIOS"];
                $result[] = $articulo;
            }

            mysqli_close($conn);
            return $result;
        } else {

            mysqli_close($conn);
            return null;
        }
        mysqli_close($conn);
    } else {
        return null;
    }
}
function ActualizaArticuloProductoPrecio(
    $ASIGNACION_DETALLE_ID,
    $ESTATUS,
    $PRECIO_ETIQUETA,
    $PRECIO_VERIFICADOR,
    $SENALIZADO/*,$COMPETITIVO*/,
    $ESPACIO_ASIGNADO,
    $EXHIBIDO,
    $PRODUCTO_ETIQUETA,
    $LIMPIEZA,
    $OBSERVACIONES,
    $USUARIO_MODIFICACION,
    $FECHA_HORA_MODIFICACION,
    $UBICACION_ID,
    $UBICACION_NOMBRE,
    $COMENTARIOS,
    $BD
) {
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn) {
        if (strlen($PRECIO_ETIQUETA) === 0) {
            $PRECIO_ETIQUETA = "NULL";
        } else {
            $PRECIO_ETIQUETA = "'$PRECIO_ETIQUETA'";
        }
        if (strlen($PRECIO_VERIFICADOR) === 0) {
            $PRECIO_VERIFICADOR = "NULL";
        } else {
            $PRECIO_VERIFICADOR = "'$PRECIO_VERIFICADOR'";
        }
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        $query  = "UPDATE ASIGNACIONES_DETALLE ";
        $query .= "SET ";
        $query .= "ESTATUS = '$ESTATUS', ";
        $query .= "PRECIO_ETIQUETA =$PRECIO_ETIQUETA, ";
        $query .= "PRECIO_VERIFICADOR = $PRECIO_VERIFICADOR, ";
        $query .= "SENALIZADO = '$SENALIZADO', ";
        // $query .= "COMPETITIVO = '$COMPETITIVO', ";
        $query .= "ESPACIO_ASIGNADO = '$ESPACIO_ASIGNADO', ";
        $query .= "EXHIBIDO = '$EXHIBIDO', ";
        $query .= "PRODUCTO_ETIQUETA = '$PRODUCTO_ETIQUETA', ";
        $query .= "LIMPIEZA = '$LIMPIEZA', ";
        $query .= "OBSERVACIONES = '$OBSERVACIONES', ";
        $query .= "USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
        $query .= "FECHA_HORA_MODIFICACION = '$FECHA_HORA_MODIFICACION', ";
        $query .= "UBICACION_ID = '$UBICACION_ID', ";
        $query .= "UBICACION_NOMBRE = '$UBICACION_NOMBRE', ";
        $query .= "COMENTARIOS = '$COMENTARIOS' ";
        $query .= "WHERE ASIGNACION_DETALLE_ID = $ASIGNACION_DETALLE_ID;";
        
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
function SubirImgProductoPrecio($MODULO, $FECHA, $IMAGEN, $NOMBRE_IMAGEN, $ASIGNACION_DETALLE_ID, $BD)
{
    $result = -1;
    $hostname = "../../../";
    $CLAVE_CLIENTE = "";
    $CLAVE_SUCURSAL = "";
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
    if ($conn) {
        $select  = " SELECT C.CLAVE AS CLAVE_CLIENTE ,S.CLAVE AS CLAVE_SUCURSAL ";
        $select .= " FROM ASIGNACIONES_DETALLE AS AD ";
        $select .= " INNER JOIN SUCURSALES AS S ON AD.SUCURSAL_ID=S.SUCURSAL_ID ";
        $select .= " INNER JOIN CLIENTES AS C ON S.CLIENTE_ID=C.CLIENTE_ID ";
        $select .= " WHERE ASIGNACION_DETALLE_ID =$ASIGNACION_DETALLE_ID;";
        $stmt = mysqli_query($conn, $select);
        //Echo $select;
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $CLAVE_CLIENTE = $row["CLAVE_CLIENTE"];
                $CLAVE_SUCURSAL = $row["CLAVE_SUCURSAL"];
            }
            if (strlen($CLAVE_CLIENTE) > 0 && strlen($CLAVE_SUCURSAL) > 0) {
                $path = "$MODULO/$CLAVE_CLIENTE/$CLAVE_SUCURSAL/$FECHA";
                //Crear la ruta de la carpeta
                $actualPath = $hostname . "Evidencia/$path";

                if (!file_exists($actualPath)) {
                    mkdir($actualPath, 0777, true);
                }
                if (file_exists($actualPath)) {
                    $actualPath .= "/$NOMBRE_IMAGEN";
                    if (file_put_contents($actualPath, base64_decode($IMAGEN))) {
                        //$conn = ABRIR_CONEXION_MYSQL(FALSE);
                        if (file_exists($actualPath)) {
                            //insertar imagen en la Tabla de Producto y prrecio
                            $actualPath =/*$_SERVER['SERVER_NAME'].*/ "/Evidencia/$path/$NOMBRE_IMAGEN";

                            $insert  = "INSERT INTO REPOSITORIO_PRODUCTO_PRECIO (ASIGNACION_DETALLE_ID,RUTA_IMAGEN) ";
                            $insert .= " VALUES ($ASIGNACION_DETALLE_ID,'$actualPath');";
                            $stmt = mysqli_query($conn, $insert);
                            if ($stmt) {
                                $result = 1;
                                mysqli_commit($conn);
                            } else {
                                $result = 0;
                                mysqli_rollback($conn);
                            }
                        }
                    }
                }
            }
        }
        mysqli_close($conn);
    }
    return $result;
}

function ActualizarHoraInicioFinProductoPrecio($TIPO, $HORA, $ASIGNACION_DETALLE_ID, $SUCURSAL_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn) {
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        $ASIGNACION_ID = 0;
        $query  = "";

        if ($TIPO === "I") {
            //Insertar
            $ASIGNACIONES_DETALLE_SUCURSALES_ID = 0;
            $select  = "SELECT ADS.ASIGNACIONES_DETALLE_SUCURSALES_ID,AD.ASIGNACION_ID FROM ASIGNACIONES_DETALLE AS AD ";
            $select .= " LEFT JOIN ASIGNACIONES_DETALLE_SUCURSALES AS ADS ON AD.ASIGNACION_ID=ADS.ASIGNACION_ID AND ADS.SUCURSAL_ID=$SUCURSAL_ID ";
            $select .= " WHERE AD.ASIGNACION_DETALLE_ID=$ASIGNACION_DETALLE_ID;";
            $stmt = mysqli_query($conn, $select);
            //echo $select." - ";
            if ($stmt) {
                while ($row = mysqli_fetch_assoc($stmt)) {
                    $ASIGNACION_ID = $row["ASIGNACION_ID"];
                    $ASIGNACIONES_DETALLE_SUCURSALES_ID = $row["ASIGNACIONES_DETALLE_SUCURSALES_ID"];
                }
            }
            if ($ASIGNACIONES_DETALLE_SUCURSALES_ID === 0 || $ASIGNACIONES_DETALLE_SUCURSALES_ID === null) {
                $query  = "INSERT INTO ASIGNACIONES_DETALLE_SUCURSALES (ASIGNACION_ID,SUCURSAL_ID,HORA_INICIO) ";
                $query .= " VALUES ($ASIGNACION_ID,$SUCURSAL_ID,'$HORA');";
                //echo $query;
            }
        } elseif ($TIPO === "F") {
            // Buscar el id de la asignación detalle sucursal
            $ASIGNACIONES_DETALLE_SUCURSALES_ID = 0;
            $select  = "SELECT ASIGNACIONES_DETALLE_SUCURSALES_ID FROM ASIGNACIONES_DETALLE AD ";
            $select .= " LEFT JOIN ASIGNACIONES_DETALLE_SUCURSALES AS ADS ON AD.ASIGNACION_ID=ADS.ASIGNACION_ID  AND ADS.SUCURSAL_ID=$SUCURSAL_ID";
            $select .= " WHERE AD.ASIGNACION_DETALLE_ID=$ASIGNACION_DETALLE_ID;";
            $stmt = mysqli_query($conn, $select);
            //echo $select." - ";
            if ($stmt) {
                while ($row = mysqli_fetch_assoc($stmt)) {
                    $ASIGNACIONES_DETALLE_SUCURSALES_ID = $row["ASIGNACIONES_DETALLE_SUCURSALES_ID"];
                }
            }
            if ($ASIGNACIONES_DETALLE_SUCURSALES_ID > 0) {
                //Actualizar
                $query  = "UPDATE ASIGNACIONES_DETALLE_SUCURSALES ";
                $query .= " SET ";
                $query .= " HORA_FIN = '$HORA' ";
                $query .= " WHERE ASIGNACIONES_DETALLE_SUCURSALES_ID =$ASIGNACIONES_DETALLE_SUCURSALES_ID AND HORA_FIN IS NULL;";
                //echo $query;
            }
        }

        if (strlen($query) > 0) {
            if (mysqli_query($conn, $query)) {
                $result = true;
                mysqli_commit($conn);
            } else {
                mysqli_rollback($conn);
                $result = false;
            }
        }
        mysqli_close($conn);
    } else {
        $result = false;
    }
    return $result;
}


function ExisteInicioProductoPrecio($ASIGNACION_ID, $SUCURSAL_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn) {
        $select  = "SELECT ASIGNACIONES_DETALLE_SUCURSALES_ID FROM ASIGNACIONES_DETALLE_SUCURSALES ";
        $select .= " WHERE ASIGNACION_ID=$ASIGNACION_ID AND SUCURSAL_ID=$SUCURSAL_ID;";
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $result = true;
            }
        } else {
            $result = false;
        }
        mysqli_close($conn);
    } else {
        $result = false;
    }
    return $result;
}

function MuestraHoraInicioFinProductoPrecio($ASIGNACION_DETALLE_ID, $SUCURSAL_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    if ($conn) {
        $select  = "SELECT HORA_INICIO,HORA_FIN FROM ASIGNACIONES_DETALLE AS AD ";
        $select .= " LEFT JOIN ASIGNACIONES_DETALLE_SUCURSALES AS ADS ON AD.ASIGNACION_ID=ADS.ASIGNACION_ID";
        $select .= " WHERE AD.ASIGNACION_DETALLE_ID=$ASIGNACION_DETALLE_ID AND ADS.SUCURSAL_ID=$SUCURSAL_ID;";
        //echo $select;
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $caducidades["HORA_INICIO"] = $row["HORA_INICIO"];
                $caducidades["HORA_FIN"]    = $row["HORA_FIN"];
                $result[] = $caducidades;
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

$server->wsdl->addComplexType(
    'MostrarDetalleProductoPrecio',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'ASIGNACION_DETALLE_ID' => array('name' => 'ASIGNACION_DETALLE_ID', 'type' => 'xsd:int'),
        'SKU' => array('name' => 'SKU', 'type' => 'xsd:string'),
        'NOMBRE' => array('name' => 'NOMBRE', 'type' => 'xsd:string'),
        'DESCRIPCION' => array('name' => 'DESCRIPCION', 'type' => 'xsd:string'),
        'ESTATUS' => array('name' => 'ESTATUS', 'type' => 'xsd:string'),
        'SENALIZADO' => array('name' => 'SENALIZADO', 'type' => 'xsd:string'),
        'SISTEMA_ORIGEN' => array('name' => 'SISTEMA_ORIGEN', 'type' => 'xsd:string'),
        'USUARIO_ASIGNADO' => array('name' => 'USUARIO_ASIGNADO', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'MostrarDetalleProductoPrecioArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:MostrarDetalleProductoPrecio[]')),
    'tns:MostrarDetalleProductoPrecio'
);
$server->register(
    'MostrarDetalleProductoPrecio',
    array(
        'SUCURSAL_ID' => 'xsd:int',
        'FECHA' => 'xsd:string',
        'BD'=>'xsd:string'
    ),
    array('return' => 'tns:MostrarDetalleProductoPrecioArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con las articulos a revisar de producto y precio de una fecha y sucursal determinada'
);
$server->register(
    'MostrarDetalleProductoPrecioChequeo',
    array(
        'SUCURSAL_ID' => 'xsd:int',
        'FECHA' => 'xsd:string',
        'USUARIO' => 'xsd:string',
        'BD'=>'xsd:string'
    ),
    array('return' => 'tns:MostrarDetalleProductoPrecioArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con las articulos a revisar de producto y precio de una fecha y sucursal determinada'
);
$server->wsdl->addComplexType(
    'MostrarArticuloProductoPrecio',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'ASIGNACION_DETALLE_ID' => array('name' => 'ASIGNACION_DETALLE_ID', 'type' => 'xsd:int'),
        'SKU' => array('name' => 'SKU', 'type' => 'xsd:string'),
        'NOMBRE' => array('name' => 'NOMBRE', 'type' => 'xsd:string'),
        'DESCRIPCION' => array('name' => 'DESCRIPCION', 'type' => 'xsd:string'),
        'PRECIO_ETIQUETA' => array('name' => 'PRECIO_ETIQUETA', 'type' => 'xsd:string'),
        'PRECIO_VERIFICADOR' => array('name' => 'PRECIO_VERIFICADOR', 'type' => 'xsd:string'),
        'ESTATUS' => array('name' => 'ESTATUS', 'type' => 'xsd:string'),
        'SENALIZADO' => array('name' => 'SENALIZADO', 'type' => 'xsd:string'),
        /*'COMPETITIVO' => array('name'=>'COMPETITIVO','type'=>'xsd:string'),*/
        'ESPACIO_ASIGNADO' => array('name' => 'ESPACIO_ASIGNADO', 'type' => 'xsd:string'),
        'EXHIBIDO' => array('name' => 'EXHIBIDO', 'type' => 'xsd:string'),
        'PRODUCTO_ETIQUETA' => array('name' => 'PRODUCTO_ETIQUETA', 'type' => 'xsd:string'),
        'LIMPIEZA' => array('name' => 'LIMPIEZA', 'type' => 'xsd:string'),
        'OBSERVACIONES' => array('name' => 'OBSERVACIONES', 'type' => 'xsd:string'),
        'IMAGEN' => array('name' => 'IMAGEN', 'type' => 'xsd:string'),
        'UBICACION_ID' => array('name' => 'UBICACION_ID', 'type' => 'xsd:int'),
        'UBICACION_NOMBRE' => array('name' => 'UBICACION_NOMBRE', 'type' => 'xsd:string'),
        'COMENTARIOS' => array('name' => 'COMENTARIOS', 'type' => 'xsd:string'),
        
    )
);
$server->wsdl->addComplexType(
    'MostrarArticuloProductoPrecioArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:MostrarArticuloProductoPrecio[]')),
    'tns:MostrarArticuloProductoPrecio'
);
$server->register(
    'MostrarArticuloProductoPrecio',
    array(
        'SKU' => 'xsd:string',
        'SUCURSAL_ID' => 'xsd:int',
        'FECHA' => 'xsd:string',
        'SISTEMA_ORIGEN' => 'xsd:string',
        'BD'=>'xsd:string'
    ),
    array('return' => 'tns:MostrarArticuloProductoPrecioArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con el detalle del articulo a revisar de producto y precio de una fecha y sucursal determinada'
);
$server->register(
    'ActualizaArticuloProductoPrecio',
    array(
        'ASIGNACION_DETALLE_ID' => 'xsd:int',
        'ESTATUS' => 'xsd:string',
        'PRECIO_ETIQUETA' => 'xsd:string',
        'PRECIO_VERIFICADOR' => 'xsd:string',
        'SENALIZADO' => 'xsd:string',
        //'COMPETITIVO'=>'xsd:string'
        'ESPACIO_ASIGNADO' => 'xsd:string',
        'EXHIBIDO' => 'xsd:string',
        'PRODUCTO_ETIQUETA' => 'xsd:string',
        'LIMPIEZA' => 'xsd:string',
        'OBSERVACIONES' => 'xsd:string',
        'USUARIO_MODIFICACION' => 'xsd:string',
        'FECHA_HORA_MODIFICACION' => 'xsd:string',
        'UBICACION_ID' => 'xsd:string',
        'UBICACION_NOMBRE' => 'xsd:string',
        'COMENTARIOS' => 'xsd:string',
        'BD'=>'xsd:string'
    ),
    array('return' => 'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza la categor赤a en el articulo a revisar de producto y precio '
);
$server->register(
    'SubirImgProductoPRecio',
    array(
        'MODULO' => 'xsd:string',
        // 'CLAVE_CLIENTE'=>'xsd:string',
        // 'CLAVE_SUCURSAL'=>'xsd:string',
        'FECHA' => 'xsd:string',
        'IMAGEN' => 'xsd:string',
        'NOMBRE_IMAGEN' => 'xsd:string',
        'ASIGNACION_DETALLE_ID' => 'xsd:int',
        'BD'=>'xsd:string'
    ),
    array('return' => 'xsd:int'),
    $namespace,
    false,
    'rpc',
    false,
    'Servicio para subir imagen al servidor'
);
$server->register(
    'ActualizarHoraInicioFinProductoPrecio',
    array(
        'TIPO' => 'xsd:string',
        'HORA' => 'xsd:string',
        'ASIGNACION_DETALLE_ID' => 'xsd:int',
        'SUCURSAL_ID' => 'xsd:int',
        'BD'=>'xsd:string'
    ),
    array('return' => 'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza la hora de inicio y la hora de fin de producto y precio'
);
$server->register(
    'ExisteInicioProductoPrecio',
    array(
        'ASIGNACION_ID' => 'xsd:int',
        'SUCURSAL_ID' => 'xsd:int',
        'BD'=>'xsd:string'
    ),
    array('return' => 'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Muestra si ya existe un registro de caducidades'
);

$server->wsdl->addComplexType(
    'MuestraHoraInicioFinProductoPrecio',
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
    'MuestraHoraInicioFinProductoPrecioArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:MuestraHoraInicioFinProductoPrecio[]')),
    'tns:MuestraHoraInicioFinProductoPrecio'
);
$server->register(
    'MuestraHoraInicioFinProductoPrecio',
    array(
        'ASIGNACION_DETALLE_ID' => 'xsd:int',
        'SUCURSAL_ID' => 'xsd:int',
        'BD'=>'xsd:string'
    ),
    array('return' => 'tns:MuestraHoraInicioFinProductoPrecioArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Muestra la hora de inicio y fin de la tarea de caducidades'
);


//WS PARA UBICACIONES--------------------------------------------------------------

function MuestraUbicacionesSucursal($SUCURSAL_ID, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    if ($conn) {
        $select  = "SELECT U.UBICACION_ID, U.NOMBRE ";
        $select .= " FROM UBICACIONES U";
        $select .= " LEFT JOIN UBICACIONES_SUCURSALES US ON US.UBICACION_ID = U.UBICACION_ID";
        $select .= " LEFT JOIN SUCURSALES S ON S.SUCURSAL_ID = US.SUCURSAL_ID";
        $select .= " WHERE (S.SUCURSAL_ID =$SUCURSAL_ID OR US.SUCURSAL_ID = 0) AND U.ESTATUS = 'A'";
        
        
        //echo $select;
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $ubicaciones["UBICACION_ID"] = $row["UBICACION_ID"];
                $ubicaciones["NOMBRE_UBICACION"]    = $row["NOMBRE"];
                $result[] = $ubicaciones;
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

$server->wsdl->addComplexType(
    'Ubicaciones',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'UBICACION_ID' => array('name' => 'UBICACION_ID', 'type' => 'xsd:int'),
        'NOMBRE_UBICACION' => array('name' => 'NOMBRE_UBICACION', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'UbicacionesArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:Ubicaciones[]')),
    'tns:Ubicaciones'
);
$server->register(
    'MuestraUbicacionesSucursal',
    array(
        
        'SUCURSAL_ID' => 'xsd:int',
        'BD'=>'xsd:string'
    ),
    array('return' => 'tns:UbicacionesArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Muestra la hora de inicio y fin de la tarea de caducidades'
);
