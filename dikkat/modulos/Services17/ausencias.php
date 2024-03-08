<?php
function FechaUltimaProgramacion($PROVEEDOR_ID,$SUCURSAL_ID,$BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
    $result="";
    if ($conn){                             
        $select  ="SELECT PV.FECHA_PROGRAMACION ";
        $select .=" FROM PROVEEDORES AS PRO ";
        $select .=" LEFT JOIN PROGRAMACION_VISITAS_DET as PVD ON PRO.PROVEEDOR_ID=PVD.PROVEEDOR_ID ";
        $select .=" LEFT JOIN PROGRAMACION_VISITAS AS PV ON PVD.PROGRAMACION_VISITAS_ID=PV.PROGRAMACION_VISITAS_ID ";
        $select .=" WHERE (PRO.PROVEEDOR_ID=$PROVEEDOR_ID AND PVD.SUCURSAL_ID=$SUCURSAL_ID AND PVD.ASISTIO ='S') ";
        $select .=" ORDER BY PV.FECHA_PROGRAMACION DESC LIMIT 1;";    
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                $result = $row["FECHA_PROGRAMACION"];
            }     
            mysqli_close($conn);
        }
        else{
            
            mysqli_close($conn);
            return $result;
        }
    }
    else {
        // FALLO LA CONEXION
        return $result;
    }
    return $result;
}

function UltimaFechaCompromiso($PROVEEDOR_ID,$SUCURSAL_ID,$BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
    $result="";
    if ($conn){ 
        $select  ="SELECT A.FECHA_COMPROMISO FROM  AUSENCIAS AS A ";
        $select .=" INNER JOIN PROMOTORES AS P ON A.PROMOTOR_ID=P.PROMOTOR_ID";
        $select .=" WHERE (P.PROVEEDOR_ID=$PROVEEDOR_ID AND A.SUCURSAL_ID=$SUCURSAL_ID ) ";
        $select .=" ORDER BY A.FECHA_COMPROMISO DESC LIMIT 1;"; 
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                $result = $row["FECHA_COMPROMISO"];
            }     
            mysqli_close($conn);
        }
        else{
            
            mysqli_close($conn);
            return $result;
        }
    }
    else {
        // FALLO LA CONEXION
        return $result;
    }
    return $result;
} 
function FechaUltimaVisita($PROVEEDOR_ID,$SUCURSAL_ID,$BD){
    $ultimaFecha="";
    $fechaVisita=new DateTime();
    $fechaCompromiso=new DateTime();
    $fechaVisita= FechaUltimaProgramacion($PROVEEDOR_ID,$SUCURSAL_ID);
    $fechaCompromiso= UltimaFechaCompromiso($PROVEEDOR_ID,$SUCURSAL_ID);
    if($fechaVisita>=$fechaCompromiso) {
        $ultimaFecha=$fechaVisita;
    }else {
        $ultimaFecha=$fechaCompromiso;        
    }
    //$result="";
    //$Fechas["ULTIMA_VISITA"]=$ultimaFecha;
    //$Fechas["FECHA_PROGRAMACION"]=$fechaVisita;
    //$Fechas["FECHA_COMPROMISO"]=$fechaCompromiso;
    //$result[]=$Fechas;
    return $ultimaFecha;
    //return $result;
}
function FechaActualServidorPHP($UTC,$BD)
{
    if(strlen($UTC)>0){
        if($UTC==="UTC-8"){
            date_default_timezone_set('America/Mexico_City');
            setlocale(LC_TIME, 'es_MX.UTF-8');
            $fecha_actual=strftime("%Y-%m-%d");
            $hora_actual=strftime("%H:%M:%S");
        }
        elseif($UTC==="UTC-6"){
            date_default_timezone_set('Mexico/BajaSur');
            setlocale(LC_TIME, 'es_MX.UTF-6');
            $fecha_actual=strftime("%Y-%m-%d");
            $hora_actual=strftime("%H:%M:%S");
        }
        elseif($UTC==="UTC-7"){
            date_default_timezone_set('Mexico/BajaNorte');
            setlocale(LC_TIME, 'es_MX.UTF-7');
            $fecha_actual=strftime("%Y-%m-%d");
            $hora_actual=strftime("%H:%M:%S");
        }
    }
    return $fecha_actual." ".$hora_actual;
}
// <editor-fold defaultstate="collapsed" desc="Inserta la ausencia del promotor">
function ObtenerUsuarioIDClave($USUARIO_CLAVE,$BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
    $result=0;
    if ($conn){  
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LA MARCA EN EL SISTEMA">                 
                  $select  = "SELECT USUARIO_ID FROM USUARIOS WHERE USUARIO ='$USUARIO_CLAVE';";              
                // </editor-fold>
                //echo $select;
                    $stmt = mysqli_query($conn, $select);            

                    if ($stmt){
                        while ($row = mysqli_fetch_assoc($stmt)){
                            $roles=$row["USUARIO_ID"];
                            $result=$roles;                            
                        } 
                        mysqli_close($conn);
                        return $result;
                    }
                    else{
                        mysqli_close($conn);
                        return 0; 
                    }
                    mysqli_close($conn);
                }
                else {
                    // FALLO LA CONEXION
                    return 0; 
                }
                
                mysqli_close($conn);
}
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Inserta la ausencia del promotor">
function InsertarAusencia($FECHA_ULT_VISITA,$FECHA_COMPROMISO,$USUARIO_CLAVE,$TELEFONO,$RAZON_AUSENCIA,$PROMOTOR_ID,$SUCURSAL_ID,$CALLCENTER,$PUESTO,$PROMOTOR_JEFE
,$ESTATUS,$PROVEEDOR_ID,$FECHA_AUSENCIA,$USUARIO_CREADOR,$FECHA_HORA_CREACION,$BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
    $result = false;
    $USUARIO_ID=0;
    $USUARIO_ID=ObtenerUsuarioIDClave($USUARIO_CLAVE);
    if($USUARIO_ID>0){
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="INSERTA LA AUSENCIA DEL PROMOTOR">                 
                 /* $query   ="INSERT INTO AUSENCIAS (FECHA_ULT_VISITA,FECHA_COMPROMISO,USUARIO_ID,TELEFONO,RAZON_AUSENCIA,PROMOTOR_ID,SUCURSAL_ID,CALLCENTER,PUESTO,JEFE_ID) ";
                  $query  .=" VALUES ('$FECHA_ULT_VISITA','$FECHA_COMPROMISO',$USUARIO_ID,'$TELEFONO','$RAZON_AUSENCIA',$PROMOTOR_ID,$SUCURSAL_ID,'$CALLCENTER','$PUESTO',$JEFE_ID);";*/
                  $query   ="INSERT INTO AUSENCIAS (FECHA_ULT_VISITA,FECHA_COMPROMISO,USUARIO_ID,TELEFONO,RAZON_AUSENCIA,PROMOTOR_ID,SUCURSAL_ID,CALLCENTER,PUESTO ";
                  $query  .=" ,ESTATUS,PROMOTOR_JEFE,PROVEEDOR_ID,FECHA_AUSENCIA,USUARIO_CREADOR,FECHA_HORA_CREACION) ";
                  $query  .=" VALUES ('$FECHA_ULT_VISITA','$FECHA_COMPROMISO',$USUARIO_ID,'$TELEFONO','$RAZON_AUSENCIA',$PROMOTOR_ID,$SUCURSAL_ID,'$CALLCENTER','$PUESTO' ";
                  $query  .=" ,'$ESTATUS','$PROMOTOR_JEFE',$PROVEEDOR_ID,'$FECHA_AUSENCIA','$USUARIO_CREADOR','$FECHA_HORA_CREACION');";
                // </editor-fold>
                //echo $query;
                if (mysqli_query($conn, $query)){
                    $result = true;
                    mysqli_commit($conn);
                }
                else{
                    mysqli_rollback($conn);
                    $result = false;
                }
            mysqli_close($conn);
        }
        else {
            // FALLO LA CONEXION
            $result = false;
        }
    }
return $result;
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Actualiza la ausencia del promotor">
function ActualizaAusencia($FECHA_ULT_VISITA,$FECHA_COMPROMISO,$USUARIO_ID,$TELEFONO,$RAZON_AUSENCIA,$PROMOTOR_ID,$SUCURSAL_ID,$CALLCENTER,$PUESTO,$PROMOTOR_JEFE
,$ESTATUS,$PROVEEDOR_ID,$FECHA_AUSENCIA,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION,$BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="ACTUALIZA LA AUSENCIA DEL PROMOTOR">                 
                  $query  = "UPDATE AUSENCIAS ";
                  $query .= " SET ";
                  $query .= " FECHA_ULT_VISITA = '$FECHA_ULT_VISITA', ";
                  $query .= " FECHA_COMPROMISO = '$FECHA_COMPROMISO', ";
                  $query .= " USUARIO_ID = $USUARIO_ID, ";
                  $query .= " TELEFONO = '$TELEFONO', ";
                  $query .= " RAZON_AUSENCIA = '$RAZON_AUSENCIA', ";
                  $query .= " PROMOTOR_ID = $PROMOTOR_ID, ";
                  $query .= " CALLCENTER = '$CALLCENTER', ";
                  $query .= " PUESTO='$PUESTO', ";
                  $query .= " JEFE_ID=$PROMOTOR_JEFE";
                  $query .= " ESTATUS=$ESTATUS";
                  $query .= " USUARIO_MODIFICACION = $USUARIO_MODIFICACION,";
                  $query .= " FECHA_HORA_MODIFICACION = $FECHA_HORA_MODIFICACION";
                  $query .= " WHERE FECHA_AUSENCIA =$FECHA_AUSENCIA ";
                  $query .= " AND SUCURSAL_ID = $SUCURSAL_ID ";
                  $query .= " AND PROVEEDOR_ID = $PROVEEDOR_ID;";                 
                // </editor-fold>
                if (mysqli_query($conn, $query)){
                    $result = true;
                    mysqli_commit($conn);
                }
                else{
                    mysqli_rollback($conn);
                    $result = false;
                }
        mysqli_close($conn);
    }
    else {
        // FALLO LA CONEXION
        $result = false;
    }
return $result;
}
// </editor-fold>
function MostrarAusenciasPorSucursal($FECHA_PROGRAMACION,$SUCURSAL_ID,$BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
    $result=null;
    if ($conn){                             

        $select  ="SELECT P.PROVEEDOR_ID,P.NOMBRE,PC.CLAVE,PV.FECHA_PROGRAMACION FROM PROGRAMACION_VISITAS AS PV ";
        $select .=" INNER JOIN PROGRAMACION_VISITAS_DET AS PVD ON PV.PROGRAMACION_VISITAS_ID=PVD.PROGRAMACION_VISITAS_ID ";
        $select .=" INNER JOIN PROVEEDORES AS P ON PVD.PROVEEDOR_ID=P.PROVEEDOR_ID ";
        $select .=" INNER JOIN PROVEEDORES_CLIENTES AS PC ON P.PROVEEDOR_ID=PC.PROVEEDOR_ID ";
        $select .=" INNER JOIN SUCURSALES AS S ON PC.CLIENTE_ID= S.CLIENTE_ID ";
        $select .=" WHERE ";
        $select .=" S.SUCURSAL_ID=$SUCURSAL_ID AND PV.FECHA_PROGRAMACION='$FECHA_PROGRAMACION' AND PVD.ASISTIO ='N' ";
        $select .=" order by P.NOMBRE ASC;";  
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                $PVD["PROVEEDOR_ID"]=$row["PROVEEDOR_ID"];
                $PVD["NOMBRE"]      =$row["NOMBRE"];
                $PVD["CLAVE"]       =$row["CLAVE"];
                $result[]=$PVD;
            }     
            mysqli_close($conn);
            return $result;
        }
        else{            
            mysqli_close($conn);
            return null;
        }
    }
    else {
        // FALLO LA CONEXION
        return null;
    }
}
function MostrarAusencia($FECHA_AUSENCIA,$SUCURSAL_ID,$PROVEEDOR_ID,$BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
    $result=null;
    if ($conn){                             

        $select  =" SELECT AUSENCIA_ID,FECHA_ULT_VISITA,FECHA_COMPROMISO,USUARIO_ID,TELEFONO,RAZON_AUSENCIA,PROMOTOR_ID,SUCURSAL_ID,CALLCENTER,PUESTO,PROMOTOR_JEFE,ESTATUS,PROVEEDOR_ID,
        FECHA_AUSENCIA,USUARIO_CREADOR,FECHA_HORA_CREACION,USUARIO_MODIFICACION,FECHA_HORA_MODIFICACION
        FROM soticomm_INMEX.AUSENCIAS 
        WHERE PROVEEDOR_ID=$PROVEEDOR_ID AND FECHA_AUSENCIA='$FECHA_AUSENCIA' AND SUCURSAL_ID=$SUCURSAL_ID;";  
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                $AUS["AUSENCIA_ID"]             =$row["AUSENCIA_ID"];
                $AUS["FECHA_ULT_VISITA"]        =$row["FECHA_ULT_VISITA"];
                $AUS["FECHA_COMPROMISO"]        =$row["FECHA_COMPROMISO"];
                $AUS["USUARIO_ID"]              =$row["USUARIO_ID"];
                $AUS["TELEFONO"]                =$row["TELEFONO"];
                $AUS["RAZON_AUSENCIA"]          =$row["RAZON_AUSENCIA"];
                $AUS["PROMOTOR_ID"]             =$row["PROMOTOR_ID"];
                $AUS["SUCURSAL_ID"]             =$row["SUCURSAL_ID"];
                $AUS["CALLCENTER"]              =$row["CALLCENTER"];
                $AUS["PUESTO"]                  =$row["PUESTO"];
                $AUS["PROMOTOR_JEFE"]           =$row["PROMOTOR_JEFE"];
                $AUS["ESTATUS"]                 =$row["ESTATUS"];
                $AUS["PROVEEDOR_ID"]            =$row["PROVEEDOR_ID"];
                $AUS["FECHA_AUSENCIA"]          =$row["FECHA_AUSENCIA"];
                $AUS["USUARIO_CREADOR"]         =$row["USUARIO_CREADOR"];
                $AUS["FECHA_HORA_CREACION"]     =$row["FECHA_HORA_CREACION"];
                $AUS["USUARIO_MODIFICACION"]    =$row["USUARIO_MODIFICACION"];
                $AUS["FECHA_HORA_MODIFICACION"] =$row["FECHA_HORA_MODIFICACION"];
               
                $result[]=$AUS;
            }     
            mysqli_close($conn);
            return $result;
        }
        else{            
            mysqli_close($conn);
            return null;
        }
    }
    else {
        // FALLO LA CONEXION
        return null;
    }
}
//Web Service que devuelve la fecha de entrada ó salida de un proveedor

//Web service que devuelve


$server->register(
    'FechaUltimaProgramacion',
    array(
        'PROMOTOR_ID'=>'xsd:int',
        'SUCURSAL_ID'=>'xsd:int',
        'BD'=>'xsd:string'
    ),
    array('return'=>'xsd:string'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve la ultima fecha de programacion de visitas detalle del promotor en la sucursal especificada'
);

$server->register(
    'UltimaFechaCompromiso',
    array(
        'PROMOTOR_ID'=>'xsd:int',
        'SUCURSAL_ID'=>'xsd:int',
        'BD'=>'xsd:string'
    ),
    array('return'=>'xsd:string'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve la ultima fecha de compromiso del promotor en la sucursal especificada'
);

$server->register(
    'FechaUltimaVisita',
    array(
        'PROVEEDOR_ID'=>'xsd:int',
        'SUCURSAL_ID'=>'xsd:int',
        'BD'=>'xsd:string'
    ),
    array('return'=>'xsd:string'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve la ultima fecha de ultima visita del promotor en la sucursal especificada'
);

$server->register(
    'FechaActualServidorPHP',
    array('UTC'=>'xsd:string',
          'BD'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve la ultima fecha actual del servidor de PHP'
);

$server->register(
    'ObtenerUsuarioIDClave',
    array(
        'USUARIO_CLAVE'=>'xsd:string',
        'BD'=>'xsd:string'
        ),
    array('return'=>'xsd:int'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve el id del usuario desde la clave especificada'
);
$server->register(
    'InsertarAusencia',
    array(
        'FECHA_ULT_VISITA'=>'xsd:string',
        'FECHA_COMPROMISO'=>'xsd:string',
        'USUARIO_CLAVE'=>'xsd:string',
        'TELEFONO'=>'xsd:string',
        'RAZON_AUSENCIA'=>'xsd:string',
        'PROMOTOR_ID'=>'xsd:int',
        'SUCURSAL_ID'=>'xsd:string',
        'CALLCENTER'=>'xsd:string',
        'PUESTO'=>'xsd:string',
        'PROMOTOR_JEFE'=>'xsd:string',
        'ESTATUS'=>'xsd:string',
        'PROVEEDOR_ID'=>'xsd:int',
        'FECHA_AUSENCIA'=>'xsd:string',
        'USUARIO_CREADOR'=>'xsd:string',
        'FECHA_HORA_CREACION'=>'xsd:string',
        'BD'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Inserta la ausencia del promotor');
    
    $server->register(
        'ActualizaAusencia',
        array(
            'FECHA_ULT_VISITA'=>'xsd:string',
            'FECHA_COMPROMISO'=>'xsd:string',
            'USUARIO_ID'=>'xsd:int',
            'TELEFONO'=>'xsd:string',
            'RAZON_AUSENCIA'=>'xsd:string',
            'PROMOTOR_ID'=>'xsd:int',
            'SUCURSAL_ID'=>'xsd:string',
            'CALLCENTER'=>'xsd:string',
            'PUESTO'=>'xsd:string',
            'PROMOTOR_JEFE'=>'xsd:string',
            'ESTATUS'=>'xsd:string',
            'PROVEEDOR_ID'=>'xsd:int',
            'FECHA_AUSENCIA'=>'xsd:string',
            'USUARIO_MODIFICACION'=>'xsd:string',
            'FECHA_HORA_MODIFICACION'=>'xsd:string',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza la ausencia del promotor');
        $server->wsdl->addComplexType(
            'MostrarAusenciasPorSucursal',
            'complexType',
            'struct',
            'all',
            '',
            array(
                'PROVEEDOR_ID' => array('name'=>'PROVEEDOR_ID','type'=>'xsd:int'),
                'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
                'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string')
            )
        );    
        $server->wsdl->addComplexType(
            'MostrarAusenciasPorSucursalArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarAusenciasPorSucursal[]')),
            'tns:MostrarAusenciasPorSucursal'
            );
        $server->register(
            'MostrarAusenciasPorSucursal',
            array(
                'FECHA_PROGRAMACION'=>'xsd:string',
                'SUCURSAL_ID'=>'xsd:int',
                'BD'=>'xsd:string'),
            array('return'=> 'tns:MostrarAusenciasPorSucursalArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con los proveedores que se ausentaron en una sucursal determinada'); 

 $server->wsdl->addComplexType(
            'MostrarAusencia',
            'complexType',
            'struct',
            'all',
            '',
            array(
                'AUSENCIA_ID' => array('name'=>'AUSENCIA_ID','type'=>'xsd:int'),
                'FECHA_ULT_VISITA' => array('name'=>'FECHA_ULT_VISITA','type'=>'xsd:string'),
                'FECHA_COMPROMISO' => array('name'=>'FECHA_COMPROMISO','type'=>'xsd:string'),
                'USUARIO_ID' => array('name'=>'USUARIO_ID','type'=>'xsd:int'),
                'TELEFONO' => array('name'=>'TELEFONO','type'=>'xsd:string'),
                'RAZON_AUSENCIA' => array('name'=>'RAZON_AUSENCIA','type'=>'xsd:string'),
                'PROMOTOR_ID' => array('name'=>'PROMOTOR_ID','type'=>'xsd:int'),
                'SUCURSAL_ID' => array('name'=>'SUCURSAL_ID','type'=>'xsd:int'),
                'CALLCENTER' => array('name'=>'CALLCENTER','type'=>'xsd:string'),
                'PUESTO' => array('name'=>'PUESTO','type'=>'xsd:string'),
                'PROMOTOR_JEFE' => array('name'=>'PROMOTOR_JEFE','type'=>'xsd:string'),
                'PROVEEDOR_ID' => array('name'=>'PROVEEDOR_ID','type'=>'xsd:int'),
                'FECHA_AUSENCIA' => array('name'=>'FECHA_AUSENCIA','type'=>'xsd:string'),
                'USUARIO_CREADOR' => array('name'=>'USUARIO_CREADOR','type'=>'xsd:string'),
                'FECHA_HORA_CREACION' => array('name'=>'FECHA_HORA_CREACION','type'=>'xsd:string'),
                'USUARIO_MODIFICACION' => array('name'=>'USUARIO_MODIFICACION','type'=>'xsd:string'),
                'FECHA_HORA_MODIFICACION' => array('name'=>'FECHA_HORA_MODIFICACION','type'=>'xsd:string')
            )
        );    
        $server->wsdl->addComplexType(
            'MostrarAusenciaArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarAusencia[]')),
            'tns:MostrarAusencia'
            );
        $server->register(
            'MostrarAusencia',
            array(
                'FECHA_AUSENCIA'=>'xsd:string',
                'SUCURSAL_ID'=>'xsd:int',
                'PROVEEDOR_ID'=>'xsd:int',
                'BD'=>'xsd:string'
            ),
            array('return'=> 'tns:MostrarAusenciaArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con los datos de la ausencia de un proveedor en una sucursal y fecha determinada'); 

