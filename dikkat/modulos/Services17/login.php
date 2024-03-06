<?php
// <editor-fold defaultstate="collapsed" desc="Inserta los usuarios del sistema">

//use JetBrains\PhpStorm\Internal\ReturnTypeContract;

    #region consultar el usuario en la tabla maestra

    function getUserMaestro($USUARIO,$PASSWORD) {
        $con = ABRIR_CONEXION_MYSQL(FALSE,DB_MASTER);  //Conexion a base de datos

    }

    function getTipoUsuario($USUARIO,$PASSWORD) {
        $con = ABRIR_CONEXION_MYSQL(FALSE,DB_MASTER);  //Conexion a base de datos
        $tipoUsuario = [];
        if($con){
            $select = "select u.USUARIO_ID ";
            $select .= ", u.USUARIO ";
            $select .= " , r.CLAVE , r.DESCRIPCION ";
            $select .= "from USUARIOS u ";
            $select .= "join ROLES_USUARIOS ru on ru.USUARIO_ID = u.USUARIO_ID ";
            $select .= "join ROLES r on r.ROL_ID = ru.ROL_ID ";
            $select .= "where u.usuario = '" . $USUARIO . "' ";
            $select .= "and u.PASS = '" . $PASSWORD . "' ";
            $select .= "";

            $stmt = mysqli_query($con, $select);

            if ($stmt) {

                while ($row = mysqli_fetch_assoc($stmt)) {
                    $prov["USUARIO_ID"] =   $row["USUARIO_ID"];
                    $prov["USUARIO"] =      $row["USUARIO"];
                    $prov["CLAVE"] =        $row["CLAVE"];
                    $prov["DESCRIPCION"] =  $row["DESCRIPCION"];
                    $tipoUsuario[] = $prov;
                }
    
            } else {
    
                mysqli_close($con);
                return null;
            }

        }
        else {
            // FALLO LA CONEXION
            return null;
        }
        mysqli_close($con);
        return $tipoUsuario;
    }


    $server->wsdl->addComplexType(
        'getTipoUsuario',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'USUARIO_ID' => array('name' => 'USUARIO_ID', 'type' => 'xsd:int'),
            'USUARIO' => array('name' => 'USUARIO', 'type' => 'xsd:string'),
            'CLAVE' => array('name' => 'CLAVE', 'type' => 'xsd:string'),
            'DESCRIPCION' => array('name' => 'DESCRIPCION', 'type' => 'xsd:string')
        )
    );
    #endregion

function LoginClaveRol($USUARIO, $PASS)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $provUsuario = null;
    if ($conn) {

        $select = "SELECT C.CLAVE FROM USUARIOS AS U ";
        $select .= " INNER JOIN ROLES_USUARIOS AS RU ON U.USUARIO_ID=RU.USUARIO_ID ";
        $select .= " INNER JOIN ROLES AS C ON RU.ROL_ID=C.ROL_ID";
        $select .= " WHERE U.USUARIO='$USUARIO' AND U.PASS='$PASS'";

        $stmt = mysqli_query($conn, $select);

        if ($stmt) {

            while ($row = mysqli_fetch_assoc($stmt)) {
                $prov["CLAVE"] = $row["CLAVE"];
                $provUsuario[] = $prov;
            }

        } else {

            mysqli_close($conn);
            return null;
        }

    } else {
        // FALLO LA CONEXION
        return null;
    }
    mysqli_close($conn);
    return $provUsuario;
}

function LoginUsuario($USUARIO)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $provUsuario = null;
    if ($conn) {

        $select = "SELECT C.CLAVE FROM USUARIOS AS U ";
        $select .= " INNER JOIN ROLES_USUARIOS AS RU ON U.USUARIO_ID=RU.USUARIO_ID ";
        $select .= " INNER JOIN ROLES AS C ON RU.ROL_ID=C.ROL_ID";
        $select .= " WHERE U.USUARIO='$USUARIO'";
        $stmt = mysqli_query($conn, $select);

        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $prov["CLAVE"] = $row["CLAVE"];
                $provUsuario[] = $prov;
            }
        } else {
            mysqli_close($conn);
            return null;
        }
    } else {
        // FALLO LA CONEXION
        return null;
    }
    mysqli_close($conn);
    return $provUsuario;
}
function LoginNombreUsuario($USUARIO)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $NombreUsuario = "";
    if ($conn) {

        $select = "SELECT CONCAT(U.NOMBRE,' ', U.APELLIDO_P) AS NOMBRE FROM USUARIOS AS U ";
        $select .= " WHERE U.USUARIO='$USUARIO'";

        $stmt = mysqli_query($conn, $select);

        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $NombreUsuario = $row["NOMBRE"];
            }
        } else {
            $NombreUsuario = "";
        }
    } else {
        // FALLO LA CONEXION
        $NombreUsuario = "";
    }
    mysqli_close($conn);
    return $NombreUsuario;
}

$server->wsdl->addComplexType(
    'LoginClaveRol',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'CLAVE' => array('name' => 'CLAVE', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'LoginUsuario',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'CLAVE' => array('name' => 'CLAVE', 'type' => 'xsd:string')
    )
);

$server->wsdl->addComplexType(
    'LoginClaveRolArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:LoginClaveRol[]')),
    'tns:LoginClaveRol'
);
$server->wsdl->addComplexType(
    'LoginUsuarioArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:LoginClaveRol[]')),
    'tns:LoginClaveRol'
);


$server->wsdl->addComplexType(
    'provUsuario',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'CLAVE' => array('name' => 'CLAVE', 'type' => 'xsd:string')
    )
);

$server->wsdl->addComplexType(
    'provUsuarioArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:provUsuario[]')),
    'tns:provUsuario'
);


$server->register(
    'LoginClaveRol',
    array(
        'USUARIO' => 'xsd:string',
        'PASS' => 'xsd:string'
    ),
    array('return' => 'tns:provUsuarioArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los roles asignados al usuario'
);

$server->register(
    'LoginUsuario',
    array('USUARIO' => 'xsd:string'),
    array('return' => 'tns:LoginUsuarioArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los roles asignados al usuario'
);

$server->register(
    'LoginNombreUsuario',
    array('USUARIO' => 'xsd:string'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve el nombre del usuario indicado'
);

function MostrarHoraInicioFinModulos($USUARIO, $SUCURSAL_ID, $FECHA)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    if ($conn) {
        /*--------------CADUCIDADES------------- */
        $select = "select \"CA\" as MODULO,HORA_INICIO,HORA_FIN from CADUCIDADES as C ";
        $select .= " inner join CADUCIDADES_DETALLE_SUCURSALES as CDS on C.CADUCIDAD_ID=CDS.CADUCIDADES_ID ";
        $select .= " where SUCURSAL_ID=$SUCURSAL_ID and FECHA_REVISION='$FECHA' and ESTATUS='A';";

        $stmt = mysqli_query($conn, $select);

        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $tareas["MODULO"] = $row["MODULO"];
                $tareas["HORA_INICIO"] = $row["HORA_INICIO"];
                $tareas["HORA_FIN"] = $row["HORA_FIN"];
                $result[] = $tareas;
            }
        }
        /*--------------FALTANTES------------- */
        $select = "Select \"FA\" as MODULO,HORA_INICIO,HORA_FIN from FALTANTES as F ";
        $select .= " where SUCURSAL_ID=$SUCURSAL_ID and FECHA='$FECHA' and ESTATUS='A';";

        $stmt = mysqli_query($conn, $select);

        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $tareas["MODULO"] = $row["MODULO"];
                $tareas["HORA_INICIO"] = $row["HORA_INICIO"];
                $tareas["HORA_FIN"] = $row["HORA_FIN"];
                $result[] = $tareas;
            }
        }
        /*--------------PRODUCTO_PRECIO------------ */
        $select = " select \"PP\" as MODULO,HORA_INICIO,HORA_FIN ";
        $select .= " from ASIGNACIONES_DETALLE_SUCURSALES as ADS ";
        $select .= " inner join ASIGNACIONES as A on ADS.ASIGNACION_ID= A.ASIGNACION_ID ";
        $select .= " where SISTEMA_ORIGEN='PROD_PRECI' and SUCURSAL_ID=$SUCURSAL_ID and A.FECHA='$FECHA' and A.ESTATUS='A';";

        $stmt = mysqli_query($conn, $select);

        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $tareas["MODULO"] = $row["MODULO"];
                $tareas["HORA_INICIO"] = $row["HORA_INICIO"];
                $tareas["HORA_FIN"] = $row["HORA_FIN"];
                $result[] = $tareas;
            }
        }
        /*---------------MYSTERY_SHOPPER-------------------*/
        $select = " select \"MS\" as MODULO,HORA_INICIO,HORA_FIN ";
        $select .= " from MYSTERY_SHOPPER as MS ";
        $select .= " inner join USUARIOS as U on MS.USUARIO_ASIGNADO=U.USUARIO_ID ";
        $select .= " where FECHA_REVISION='$FECHA' and SUCURSAL_ID=$SUCURSAL_ID and U.USUARIO='$USUARIO' and MS.ESTATUS='A';";

        $stmt = mysqli_query($conn, $select);

        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $tareas["MODULO"] = $row["MODULO"];
                $tareas["HORA_INICIO"] = $row["HORA_INICIO"];
                $tareas["HORA_FIN"] = $row["HORA_FIN"];
                $result[] = $tareas;
            }
        }
        /*------------CHEQUEO_COMPETENCIAS-----------------*/
        $select = " SELECT \"CC\" as MODULO,HORA_INICIO,HORA_FIN ";
        $select .= " FROM CHEQUEO_COMPETENCIA AS CC  ";
        $select .= " INNER JOIN CHEQUEO_COMPETENCIA_DETALLE AS CCD ON CC.CHEQUEO_COMPETENCIA_ID=CCD.CHEQUEO_COMPETENCIA_ID ";
        $select .= " INNER JOIN CLIENTES AS C ON CC.CLIENTE_SOLICITO_ID=C.CLIENTE_ID ";
        $select .= " INNER JOIN SUCURSALES_CHEQUEO_COMPETENCIA AS SCC ON CCD.SUCURSALES_CHEQUEO_COMPETENCIA_ID=SCC.SUCURSALES_CHEQUEO_COMPETENCIA_ID ";
        $select .= " WHERE CC.FECHA_REVISION='$FECHA' AND CC.USUARIO_ASIGNADO='$USUARIO' AND SCC.SUCURSAL_ID=$SUCURSAL_ID AND CC.ESTATUS='A';";

        $stmt = mysqli_query($conn, $select);

        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $tareas["MODULO"] = $row["MODULO"];
                $tareas["HORA_INICIO"] = $row["HORA_INICIO"];
                $tareas["HORA_FIN"] = $row["HORA_FIN"];
                $result[] = $tareas;
            }
        }
        mysqli_close($conn);
        return $result;
    } else {

        return null;
    }
}
$server->wsdl->addComplexType(
    'MostrarHoraInicioFinModulos',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'MODULO' => array('name' => 'MODULO', 'type' => 'xsd:string'),
        'HORA_INICIO' => array('name' => 'HORA_INICIO', 'type' => 'xsd:string'),
        'HORA_FIN' => array('name' => 'HORA_FIN', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'MostrarHoraInicioFinModulosArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:MostrarHoraInicioFinModulos[]')),
    'tns:MostrarHoraInicioFinModulos'
);
$server->register(
    'MostrarHoraInicioFinModulos',
    array(//
        'USUARIO' => 'xsd:string',
        'SUCURSAL_ID' => 'xsd:int',
        'FECHA' => 'xsd:string'
    ),
    array('return' => 'tns:MostrarHoraInicioFinModulosArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Muestra la hora de inicio y fin de los diferentes módulos'
);
