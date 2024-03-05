<?php
// <editor-fold defaultstate="collapsed" desc="Inserta los dias de visita de un promotor">
function InsertarDiasVisitaPromotor($SUCURSAL_ID,$PROVEEDOR_ID,$LUN,$MAR,$MIE,$JUE,$VIE,$SAB,$DOM,$ESTATUS,$HORARIO_HORA_ENTRADA1, $HORARIO_HORA_SALIDA1, $HORARIO_HORA_SALIDA2, $HORARIO_HORA_ENTRADA2){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
        if(is_null( $HORARIO_HORA_ENTRADA1)|| strlen($HORARIO_HORA_ENTRADA1)===0){
            $HORARIO_HORA_ENTRADA1="NULL";
        }
        if(is_null($HORARIO_HORA_SALIDA1 )|| strlen($HORARIO_HORA_SALIDA1)===0){
            $HORARIO_HORA_SALIDA1="NULL";
        }
        if(is_null($HORARIO_HORA_ENTRADA2)|| strlen($HORARIO_HORA_ENTRADA2)===0){
            $HORARIO_HORA_ENTRADA2="NULL";
        }
        if(is_null($HORARIO_HORA_SALIDA2 )|| strlen($HORARIO_HORA_SALIDA2)===0){
            $HORARIO_HORA_SALIDA2="NULL";
        }

                // <editor-fold defaultstate="collapsed" desc="INSERSION DE LOS DIAS DE VISITA DE UN PROMOTOR">                 
                  $query   ="INSERT INTO SUCURSALES_PROVEEDORES_DIAS (SUCURSAL_ID,PROVEEDOR_ID,LUN,MAR,MIE,JUE,VIE,SAB,DOM,ESTATUS,HORARIO_HORA_ENTRADA1, HORARIO_HORA_SALIDA1, HORARIO_HORA_SALIDA2, HORARIO_HORA_ENTRADA2) ";
                  $query  .=" VALUES ($SUCURSAL_ID,$PROVEEDOR_ID,'$LUN','$MAR','$MIE','$JUE','$VIE','$SAB','$DOM','$ESTATUS','$HORARIO_HORA_ENTRADA1', '$HORARIO_HORA_SALIDA1', '$HORARIO_HORA_SALIDA2', '$HORARIO_HORA_ENTRADA2')";                 
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
// <editor-fold defaultstate="collapsed" desc="Actualiza los dias de visita o estatus de un promotor">
function ActualizaDiasVisitaPromotor($SUCURSAL_ID,$PROVEEDOR_ID,$LUN,$MAR,$MIE,$JUE,$VIE,$SAB,$DOM,$ESTATUS,$HORARIO_HORA_ENTRADA1, $HORARIO_HORA_SALIDA1, $HORARIO_HORA_SALIDA2, $HORARIO_HORA_ENTRADA2){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE LOS DIAS DE VISITA O ESTATUS DE UN PROMOTOR">      
                if(is_null( $HORARIO_HORA_ENTRADA1)|| strlen($HORARIO_HORA_ENTRADA1)===0){
                    $HORARIO_HORA_ENTRADA1="NULL";
                }
                if(is_null($HORARIO_HORA_SALIDA1 )|| strlen($HORARIO_HORA_SALIDA1)===0){
                    $HORARIO_HORA_SALIDA1="NULL";
                }
                if(is_null($HORARIO_HORA_ENTRADA2)|| strlen($HORARIO_HORA_ENTRADA2)===0){
                    $HORARIO_HORA_ENTRADA2="NULL";
                }
                if(is_null($HORARIO_HORA_SALIDA2 )|| strlen($HORARIO_HORA_SALIDA2)===0){
                    $HORARIO_HORA_SALIDA2="NULL";
                }           
                  $query  = " UPDATE SUCURSALES_PROVEEDORES_DIAS ";
                  $query .= " SET ";
                  $query .= " LUN = '$LUN', ";
                  $query .= " MAR = '$MAR', ";
                  $query .= " MIE = '$MIE', ";
                  $query .= " JUE = '$JUE', ";
                  $query .= " VIE = '$VIE', ";
                  $query .= " SAB = '$SAB', ";
                  $query .= " DOM = '$DOM', ";
                  $query .= " HORARIO_HORA_ENTRADA1='$HORARIO_HORA_ENTRADA1,'"; 
                  $query .= " HORARIO_HORA_SALIDA1 ='$HORARIO_HORA_SALIDA1', ";
                  $query .= " HORARIO_HORA_SALIDA2 ='$HORARIO_HORA_SALIDA2'"; 
                  $query .= " HORARIO_HORA_ENTRADA2='$HORARIO_HORA_ENTRADA2'";
                  $query .= " ESTATUS = '$ESTATUS' ";
                  $query .= " WHERE  ";
                  $query .= " SUCURSAL_ID = $SUCURSAL_ID AND PROVEEDOR_ID = $PROVEEDOR_ID;";                 
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
// <editor-fold defaultstate="collapsed" desc="Actualiza los dias de visita o estatus de un promotor">
function ActualizaVisitaProveedorDesdePromotor($SUCURSAL_ID,$PROMOTOR_ID,$FECHA_PROGRAMACION,$HORA_ENTRADA){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE LOS DIAS DE VISITA O ESTATUS DE UN PROMOTOR">                 
                  $query  = " UPDATE PROGRAMACION_VISITAS_DET AS PVD ";
                  $query .= " SET ";
                  $query .= " HORA_ENTRADA1 = '$HORA_ENTRADA' ";
                  $query .= " WHERE  ";
                  $query .= " PROGRAMACION_VISITAS_DET_ID= ".MostrarProgramacionVistaDetalleID($SUCURSAL_ID,$PROMOTOR_ID,$FECHA_PROGRAMACION).";";
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
function MostrarProgramacionVistaDetalleID($SUCURSAL_ID,$PROMOTOR_ID,$FECHA_PROGRAMACION){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = "";
    if ($conn){  
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DIAS DE VISITA Y ESTATUS DE UN PROMOTOR">                 
                  $select = " SELECT PVD.PROGRAMACION_VISITAS_DET_ID FROM PROGRAMACION_VISITAS AS PV ";
                  $select .= " INNER JOIN PROGRAMACION_VISITAS_DET AS PVD ON PV.PROGRAMACION_VISITAS_ID=PVD.PROGRAMACION_VISITAS_ID ";
                  $select .= " INNER JOIN PROMOTORES AS P ON PVD.PROVEEDOR_ID=P.PROVEEDOR_ID  ";
                  $select .= " WHERE P.PROMOTOR_ID=$PROMOTOR_ID ";
                  $select .= " AND PVD.SUCURSAL_ID=$SUCURSAL_ID ";
                  $select .= " AND PV.FECHA_PROGRAMACION ='$FECHA_PROGRAMACION' LIMIT 1";
                         
                // </editor-fold>
                
                    $stmt = mysqli_query($conn, $select);            

                    if ($stmt){
                        while ($row = mysqli_fetch_assoc($stmt)){
                            $SPD=$row["PROGRAMACION_VISITAS_DET_ID"];
                            $result=$SPD;
                            
                        } 
                        mysqli_close($conn);
                        return $result;
                    }
                    else{
                        mysqli_close($conn);
                        return ""; 
                    }
                    mysqli_close($conn);
                }
                else {
                    // FALLO LA CONEXION
                    return ""; 
                }
}
// <editor-fold defaultstate="collapsed" desc="Muestra los dias de visita y estatus de un promotor">
function MostrarDiasVisitaProveedor($SUCURSAL_ID,$PROVEEDOR_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    if ($conn){  
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DIAS DE VISITA Y ESTATUS DE UN PROMOTOR">                 
                $select  = " SELECT SUCURSALES_PROVEEDORES_DIAS_ID,SUCURSAL_ID,PROVEEDOR_ID,LUN,MAR,MIE,JUE,VIE,SAB,DOM,ESTATUS ";
                $select .= ",HORARIO_HORA_ENTRADA1, HORARIO_HORA_SALIDA1, HORARIO_HORA_SALIDA2, HORARIO_HORA_ENTRADA2 FROM SUCURSALES_PROVEEDORES_DIAS";
                $select .= " WHERE ESTATUS='A' AND SUCURSAL_ID = $SUCURSAL_ID AND PROVEEDOR_ID = $PROVEEDOR_ID;";
                         
                // </editor-fold>
                
                    $stmt = mysqli_query($conn, $select);            

                    if ($stmt){
                        while ($row = mysqli_fetch_assoc($stmt)){
                            $SPD["SUCURSALES_PROVEEDORES_DIAS_ID"]=$row["SUCURSALES_PROVEEDORES_DIAS_ID"];
                            $SPD["SUCURSAL_ID"]                   =$row["SUCURSAL_ID"];
                            $SPD["PROVEEDOR_ID"]                  =$row["PROVEEDOR_ID"];
                            $SPD["LUN"]                           =$row["LUN"];
                            $SPD["MAR"]                           =$row["MAR"];
                            $SPD["MIE"]                           =$row["MIE"];
                            $SPD["JUE"]                           =$row["JUE"];
                            $SPD["VIE"]                           =$row["VIE"];
                            $SPD["SAB"]                           =$row["SAB"];
                            $SPD["DOM"]                           =$row["DOM"];
                            $SPD["ESTATUS"]                       =$row["ESTATUS"];
                            $SPD["HORARIO_HORA_ENTRADA1"]         =$row["HORARIO_HORA_ENTRADA1"];
                            $SPD["HORARIO_HORA_SALIDA1"]          =$row["HORARIO_HORA_SALIDA1"];
                            $SPD["HORARIO_HORA_ENTRADA2"]         =$row["HORARIO_HORA_ENTRADA2"];
                            $SPD["HORARIO_HORA_SALIDA2"]          =$row["HORARIO_HORA_SALIDA2"];
                            $result[]=$SPD;
                            
                        } 
                        mysqli_close($conn);
                        return $result;
                    }
                    else{
                        mysqli_close($conn);
                        return null; 
                    }
                    mysqli_close($conn);
                }
                else {
                    // FALLO LA CONEXION
                    return null; 
                }
}
function MostrarHorarioProveedorSucursal($SUCURSAL_ID,$PROVEEDOR_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    if ($conn){  
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DIAS DE VISITA Y ESTATUS DE UN PROMOTOR">                 
                $select  = " SELECT LUN,MAR,MIE,JUE,VIE,SAB,DOM,HORARIO_HORA_ENTRADA1, HORARIO_HORA_SALIDA1 ";
                $select .= ", HORARIO_HORA_SALIDA2, HORARIO_HORA_ENTRADA2 FROM SUCURSALES_PROVEEDORES_DIAS";
                $select .= " WHERE SUCURSAL_ID = $SUCURSAL_ID AND PROVEEDOR_ID = $PROVEEDOR_ID;";
                         
                // </editor-fold>
                
                    $stmt = mysqli_query($conn, $select);            

                    if ($stmt){
                        while ($row = mysqli_fetch_assoc($stmt)){
                            $SPD["LUN"]                           =$row["LUN"];
                            $SPD["MAR"]                           =$row["MAR"];
                            $SPD["MIE"]                           =$row["MIE"];
                            $SPD["JUE"]                           =$row["JUE"];
                            $SPD["VIE"]                           =$row["VIE"];
                            $SPD["SAB"]                           =$row["SAB"];
                            $SPD["DOM"]                           =$row["DOM"];
                            $SPD["HORARIO_HORA_ENTRADA1"]         =$row["HORARIO_HORA_ENTRADA1"];
                            $SPD["HORARIO_HORA_SALIDA1"]          =$row["HORARIO_HORA_SALIDA1"];
                            $SPD["HORARIO_HORA_ENTRADA2"]         =$row["HORARIO_HORA_ENTRADA2"];
                            $SPD["HORARIO_HORA_SALIDA2"]          =$row["HORARIO_HORA_SALIDA2"];
                            $result[]=$SPD;
                            
                        } 
                        mysqli_close($conn);
                        return $result;
                    }
                    else{
                        mysqli_close($conn);
                        return null; 
                    }
                    mysqli_close($conn);
                }
                else {
                    // FALLO LA CONEXION
                    return null; 
                }
}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Actualiza los dias de visita o estatus de un promotor">
/*function ActualizaAsistenciaProveedor($PROGRAMACION_VISITAS_DET_ID,$ASISTIO,$HORA_ENTRADA){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE LOS DIAS DE VISITA O ESTATUS DE UN PROMOTOR">    
                $horaEntrada="";
                if(strlen($HORA_ENTRADA)>0)
                {
                    $horaEntrada=" ,HORA_ENTRADA1='$HORA_ENTRADA' ";
                }                
                  $query  = " UPDATE PROGRAMACION_VISITAS_DET ";
                  $query .= " SET ";
                  $query .= " ASISTIO = '$ASISTIO' ";
                  $query .= $horaEntrada;
                  $query .= " WHERE PROGRAMACION_VISITAS_DET_ID=$PROGRAMACION_VISITAS_DET_ID;";                 
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
}*/
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Actualiza las horas de entrada o salida de un proveedor">
function ActualizaHoraEntradaSalidaProveedor($PROGRAMACION_VISITAS_DET_ID,$ENTRADA_O_SALIDA,$UNO_O_DOS,$HORA){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE LAS HORAS DE ENTRADA O SALIDA DE UN PROVEEDOR">
                $query="";
                $horaModif="";
                if($ENTRADA_O_SALIDA==="E"){
                    $horaModif=" SET HORA_ENTRADA".$UNO_O_DOS;
                }elseif($ENTRADA_O_SALIDA==="S"){
                    $horaModif=" SET HORA_SALIDA".$UNO_O_DOS;
                }
                $query  = " UPDATE PROGRAMACION_VISITAS_DET ";
                $query .= $horaModif." ='$HORA'";
                $query .= " WHERE PROGRAMACION_VISITAS_DET_ID=$PROGRAMACION_VISITAS_DET_ID;"; 
                if (mysqli_query($conn, $query)){
                    $result = true;
                    mysqli_commit($conn);
                }
                else{
                    mysqli_rollback($conn);
                    $result = false;
                }
        mysqli_close($conn);
        // </editor-fold>
    }
    else {
        // FALLO LA CONEXION
        $result = false;
    }
return $result;
}
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Actualiza las horas de entrada o salida de un proveedor">
function AsistioProveedor($PROGRAMACION_VISITAS_DET_ID,$ASISTIO){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE LAS HORAS DE ENTRADA O SALIDA DE UN PROVEEDOR">
                $query="";
                $horaModif="";
               
                $query  = " UPDATE PROGRAMACION_VISITAS_DET ";
                $query .= " SET ASISTIO='$ASISTIO'";
                $query .= " WHERE PROGRAMACION_VISITAS_DET_ID=$PROGRAMACION_VISITAS_DET_ID;"; 
                if (mysqli_query($conn, $query)){
                    $result = true;
                    mysqli_commit($conn);
                }
                else{
                    mysqli_rollback($conn);
                    $result = false;
                }
        mysqli_close($conn);
        // </editor-fold>
    }
    else {
        // FALLO LA CONEXION
        $result = false;
    }
return $result;
}
// </editor-fold>

$server->register(
    'InsertarDiasVisitaProveedor',
    array(//, , , 
        'SUCURSAL_ID'=>'xsd:int',
        'PROVEEDOR_ID'=>'xsd:int',
        'LUN'=>'xsd:string',
        'MAR'=>'xsd:string',
        'MIE'=>'xsd:string',
        'JUE'=>'xsd:string',
        'VIE'=>'xsd:string',
        'SAB'=>'xsd:string',
        'DOM'=>'xsd:string',
        'ESTATUS'=>'xsd:string',
        'HORARIO_HORA_ENTRADA1'=>'xsd:string',
        'HORARIO_HORA_SALIDA1'=>'xsd:string',
        'HORARIO_HORA_ENTRADA2'=>'xsd:string',
        'HORARIO_HORA_SALIDA2'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Inserta los dias de visita de un promotor');

$server->register(
    'ActualizaVisitaProveedorDesdePromotor',
    array(
        'SUCURSAL_ID'=>'xsd:int',
        'PROVEEDOR_ID'=>'xsd:int',
        'FECHA_PROGRAMACION'=>'xsd:string',
        'HORA_ENTRADA'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza los dias de visita o estatus de un promotor');

    $server->register(
        'ActualizaDiasVisitaProveedor',
        array(
            'SUCURSAL_ID'=>'xsd:int',
            'PROVEEDOR_ID'=>'xsd:int',
            'LUN'=>'xsd:string',
            'MAR'=>'xsd:string',
            'MIE'=>'xsd:string',
            'JUE'=>'xsd:string',
            'VIE'=>'xsd:string',
            'SAB'=>'xsd:string',
            'DOM'=>'xsd:string',
            'ESTATUS'=>'xsd:string',
            'HORARIO_HORA_ENTRADA1'=>'xsd:string',
            'HORARIO_HORA_SALIDA1'=>'xsd:string',
            'HORARIO_HORA_ENTRADA2'=>'xsd:string',
            'HORARIO_HORA_SALIDA2'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza los dias de visita o estatus de un promotor');

    $server->wsdl->addComplexType(
        'MostrarDiasVisitaProveedor',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'SUCURSALES_PROVEEDORES_DIAS_ID' => array('name'=>'SUCURSALES_PROMOTORES_DIAS_ID','type'=>'xsd:int'),
            'SUCURSAL_ID'                   => array('name'=>'SUCURSAL_ID','type'=>'xsd:int'),
            'PROVEEDOR_ID'                   => array('name'=>'PROMOTOR_ID','type'=>'xsd:int'),
            'LUN'                           => array('name'=>'LUN','type'=>'xsd:string'),
            'MAR'                           => array('name'=>'MAR','type'=>'xsd:string'),
            'MIE'                           => array('name'=>'MIE','type'=>'xsd:string'),
            'JUE'                           => array('name'=>'JUE','type'=>'xsd:string'),
            'VIE'                           => array('name'=>'VIE','type'=>'xsd:string'),
            'SAB'                           => array('name'=>'SAB','type'=>'xsd:string'),
            'DOM'                           => array('name'=>'DOM','type'=>'xsd:string'),
            'ESTATUS'                       => array('name'=>'ESTATUS','type'=>'xsd:string'),
            'HORARIO_HORA_ENTRADA1'         => array('name'=>'HORARIO_HORA_ENTRADA1','type'=>'xsd:string'),
            'HORARIO_HORA_SALIDA1'          => array('name'=>'HORARIO_HORA_SALIDA1','type'=>'xsd:string'),
            'HORARIO_HORA_ENTRADA2'         => array('name'=>'HORARIO_HORA_ENTRADA2','type'=>'xsd:string'),
            'HORARIO_HORA_SALIDA2'          => array('name'=>'HORARIO_HORA_SALIDA2','type'=>'xsd:string')
        )
    );    
    
$server->wsdl->addComplexType(
    'MostrarDiasVisitaProveedorArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarDiasVisitaProveedor[]')),
    'tns:MostrarDiasVisitaProveedor'
    );

$server->register(
    'MostrarDiasVisitaProveedor',
    array(
        'SUCURSAL_ID'=>'xsd:int',
        'PROVEEDOR_ID'=>'xsd:int'
    ),
    array('return'=> 'tns:MostrarDiasVisitaProveedorArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con las sucursales de un cliente especificado');
    /*$server->register(
        'ActualizaAsistenciaProveedor',
        array( 
            'PROGRAMACION_VISITAS_DET_ID'=>'xsd:int',
            'ASISTIO'=>'xsd:string',
            'HORA_ENTRADA'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza el estatus de la visita programada');*/
        $server->wsdl->addComplexType(
            'MostrarHorarioProveedorSucursal',
            'complexType',
            'struct',
            'all',
            '',
            array(
                'LUN'                           => array('name'=>'LUN','type'=>'xsd:string'),
                'MAR'                           => array('name'=>'MAR','type'=>'xsd:string'),
                'MIE'                           => array('name'=>'MIE','type'=>'xsd:string'),
                'JUE'                           => array('name'=>'JUE','type'=>'xsd:string'),
                'VIE'                           => array('name'=>'VIE','type'=>'xsd:string'),
                'SAB'                           => array('name'=>'SAB','type'=>'xsd:string'),
                'DOM'                           => array('name'=>'DOM','type'=>'xsd:string'),
                'HORARIO_HORA_ENTRADA1'         => array('name'=>'HORARIO_HORA_ENTRADA1','type'=>'xsd:string'),
                'HORARIO_HORA_SALIDA1'          => array('name'=>'HORARIO_HORA_SALIDA1','type'=>'xsd:string'),
                'HORARIO_HORA_ENTRADA2'         => array('name'=>'HORARIO_HORA_ENTRADA2','type'=>'xsd:string'),
                'HORARIO_HORA_SALIDA2'          => array('name'=>'HORARIO_HORA_SALIDA2','type'=>'xsd:string')
            )
        );    
        
    $server->wsdl->addComplexType(
        'MostrarHorarioProveedorSucursalArray',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarHorarioProveedorSucursal[]')),
        'tns:MostrarHorarioProveedorSucursal'
        );
    
    $server->register(
        'MostrarHorarioProveedorSucursal',
        array(
            'SUCURSAL_ID'=>'xsd:int',
            'PROVEEDOR_ID'=>'xsd:int'
        ),
        array('return'=> 'tns:MostrarHorarioProveedorSucursalArray'),
        $namespace,
        false,
        'rpc',
        false,
        'Devuelve un arreglo con el horario y días de visita de un proveedor en una sucursal');

        $server->register(
            'ActualizaHoraEntradaSalidaProveedor',
            array( 
                'PROGRAMACION_VISITAS_DET_ID'=>'xsd:int',
                'ENTRADA_O_SALIDA'=>'xsd:string',
                'UNO_O_DOS'=>'xsd:int',
                'HORA'=>'xsd:string'
                ),
            array('return'=>'xsd:boolean'),
            $namespace,
            false,
            'rpc',
            false,
            'Actualiza la hora de entrada o salida de un proveedor');
            $server->register(
                'AsistioProveedor',
                array( 
                    'PROGRAMACION_VISITAS_DET_ID'=>'xsd:int',
                    'ASISTIO'=>'xsd:string'
                    ),
                array('return'=>'xsd:boolean'),
                $namespace,
                false,
                'rpc',
                false,
                'Actualiza la hora de entrada o salida de un proveedor');
            $server->register(
                'MostrarProgramacionVistaDetalleID',
                array( 
                    'SUCURSAL_ID'=>'xsd:int',
                    'PROMOTOR_ID'=>'xsd:int',
                    'FECHA_PROGRAMACION'=>'xsd:string'
                    ),
                array('return'=>'xsd:string'),
                $namespace,
                false,
                'rpc',
                false,
                'Muestra el id de la programacion de visita de un proveedor en en una sucursal de una fecha determinada');

function ActualizaVisitaProveedorDesdePromotor2($SUCURSAL_ID,$PROMOTOR_ID,$FECHA_PROGRAMACION,$HORA,$ENTRADA_O_SALIDA,$UNO_O_DOS){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE LOS DIAS DE VISITA O ESTATUS DE UN PROMOTOR">        
                $horaModif="";
                if($ENTRADA_O_SALIDA==="E"){
                    $horaModif=" HORA_ENTRADA".$UNO_O_DOS;
                }elseif($ENTRADA_O_SALIDA==="S"){
                    $horaModif=" HORA_SALIDA".$UNO_O_DOS;
                }         
                  $query  = " UPDATE PROGRAMACION_VISITAS_DET AS PVD ";
                  $query .= " SET ";
                  $query .= $horaModif." ='$HORA'";
                  $query .= " WHERE  ";
                  $query .= " PROGRAMACION_VISITAS_DET_ID= ".MostrarProgramacionVistaDetalleID($SUCURSAL_ID,$PROMOTOR_ID,$FECHA_PROGRAMACION).";";
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
return $result;
}

$server->register(
    'ActualizaVisitaProveedorDesdePromotor2',
    array(
        'SUCURSAL_ID'=>'xsd:int',
        'PROMOTOR_ID'=>'xsd:int',
        'FECHA_PROGRAMACION'=>'xsd:string',
        'HORA'=>'xsd:string',
        'ENTRADA_O_SALIDA'=>'xsd:string',
        'UNO_O_DOS'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza los dias de visita o estatus de un promotor');
    function MostrarVisitaProveedorSucursalDia($SUCURSAL_ID,$DIASEMANA){
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = null;
        if ($conn){

                    // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DIAS DE VISITA Y ESTATUS DE UN PROMOTOR">    
            $diaSem="";
            $DIASEMANA=strtoupper($DIASEMANA);
                    if($DIASEMANA==="LUNES"){
                $diaSem=" AND LUN=1";
            }elseif($DIASEMANA==="MARTES"){
                $diaSem=" AND MAR=1";
            }elseif($DIASEMANA==="MIERCOLES"){
                $diaSem=" AND MIE=1";
            }elseif($DIASEMANA==="JUEVES"){
                $diaSem=" AND JUE=1";
            }elseif($DIASEMANA==="VIERNES"){
                $diaSem=" AND VIE=1";
            }elseif($DIASEMANA==="SABADO"){
                $diaSem=" AND SAB=1";
            }elseif($DIASEMANA==="DOMINGO"){
                $diaSem=" AND DOM=1";
            }else {
                $diaSem="";
            }
            if(strlen($diaSem)>0){
                $select  = " SELECT P.PROVEEDOR_ID,P.NOMBRE,PC.CLAVE,SPD.HORARIO_HORA_ENTRADA1 ";
                $select .= " ,SPD.HORARIO_HORA_SALIDA1,SPD.HORARIO_HORA_ENTRADA2,SPD.HORARIO_HORA_SALIDA2 ";
                $select .= " FROM SUCURSALES_PROVEEDORES_DIAS AS SPD ";
                $select .= " INNER JOIN PROVEEDORES AS P ON SPD.PROVEEDOR_ID =P.PROVEEDOR_ID ";
                $select .= " INNER JOIN PROVEEDORES_CLIENTES AS PC ON P.PROVEEDOR_ID=PC.PROVEEDOR_ID ";
                $select .= " WHERE SPD.ESTATUS='A'  AND SPD.SUCURSAL_ID=$SUCURSAL_ID ".$diaSem;
                $select .= " ORDER BY HORARIO_HORA_ENTRADA1";         
                // </editor-fold>
                //ECHO $select;
                $stmt = mysqli_query($conn, $select);            

                if ($stmt){
                    while ($row = mysqli_fetch_assoc($stmt)){
                        $SPD["PROVEEDOR_ID"]          =$row["PROVEEDOR_ID"];
                        $SPD["NOMBRE"]                =$row["NOMBRE"];
                        $SPD["CLAVE"]                 =$row["CLAVE"];
                        $SPD["HORARIO_HORA_ENTRADA1"] =$row["HORARIO_HORA_ENTRADA1"];
                        $SPD["HORARIO_HORA_SALIDA1"]  =$row["HORARIO_HORA_SALIDA1"];
                        $SPD["HORARIO_HORA_ENTRADA2"] =$row["HORARIO_HORA_ENTRADA2"];
                        $SPD["HORARIO_HORA_SALIDA2"]  =$row["HORARIO_HORA_SALIDA2"];
                        $result[]=$SPD;                                
                    } 
                    mysqli_close($conn);
                    return $result;
                }
                else{
                    mysqli_close($conn);
                    return null; 
                }
                mysqli_close($conn);
            }
        }
        else {
            // FALLO LA CONEXION
            return null; 
        }
    }
    $server->wsdl->addComplexType(
        'MostrarVisitaProveedorSucursalDia',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'PROVEEDOR_ID'          => array('name'=>'PROVEEDOR_ID','type'=>'xsd:int'),
            'NOMBRE'                => array('name'=>'NOMBRE','type'=>'xsd:string'),
            'CLAVE'                 => array('name'=>'CLAVE','type'=>'xsd:string'),
            'HORARIO_HORA_ENTRADA1' => array('name'=>'HORARIO_HORA_ENTRADA1','type'=>'xsd:string'),
            'HORARIO_HORA_SALIDA1'  => array('name'=>'HORARIO_HORA_SALIDA1','type'=>'xsd:string'),
            'HORARIO_HORA_ENTRADA2' => array('name'=>'HORARIO_HORA_ENTRADA2','type'=>'xsd:string'),
            'HORARIO_HORA_SALIDA2'  => array('name'=>'HORARIO_HORA_SALIDA2','type'=>'xsd:string')
        )
    );    
    
$server->wsdl->addComplexType(
    'MostrarVisitaProveedorSucursalDiaArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarVisitaProveedorSucursalDia[]')),
    'tns:MostrarVisitaProveedorSucursalDia'
    );

$server->register(
    'MostrarVisitaProveedorSucursalDia',
    array(
        'SUCURSAL_ID'=>'xsd:int',
        'DIASEMANA'=>'xsd:string'
    ),
    array('return'=> 'tns:MostrarVisitaProveedorSucursalDiaArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los proveedores del día de visita en una sucursal');