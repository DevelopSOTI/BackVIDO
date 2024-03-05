<?php
// <editor-fold defaultstate="collapsed" desc="Inserta el detalle de la programación de visitas de proveedores">
    function InsertarProgramacionVisitaDet($PROGRAMACION_VISITAS_ID,$SUCURSAL_ID,$PROVEEDOR_ID){
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = false;
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                    // <editor-fold defaultstate="collapsed" desc="INSERSION DE PROGRAMACION_VISITAS_DETALLE">                 
                      $query   ="INSERT INTO PROGRAMACION_VISITAS_DET(PROGRAMACION_VISITAS_ID,SUCURSAL_ID,PROVEEDOR_ID)";
                      $query  .=" VALUES($PROGRAMACION_VISITAS_ID,$SUCURSAL_ID,$PROVEEDOR_ID);";                 
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

// <editor-fold defaultstate="collapsed" desc="Actualiza la programación de visitas de proveedores">
function ActualizaProgramacionVisitaDet($PROGRAMACION_VISITAS_DET_ID,$PROGRAMACION_VISITAS_ID,$SUCURSAL_ID,$PROVEEDOR_ID,$ASISTIO,$HORA_ENTRADA1,$HORA_SALIDA1,$HORA_ENTRADA2,$HORA_SALIDA2){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE PROGRAMACION_VISITAS_DETALLE">                 
                  $query  = "UPDATE PROGRAMACION_VISITAS_DET ";
                  $query  .=" SET ";
                  $query  .=" PROGRAMACION_VISITAS_ID = $PROGRAMACION_VISITAS_ID, ";
                  $query  .=" SUCURSAL_ID = $SUCURSAL_ID, ";
                  $query  .=" PROVEEDOR_ID = $PROVEEDOR_ID, ";
                  $query  .=" ASISTIO = '$ASISTIO', ";
                  $query  .=" HORA_ENTRADA1 = '$HORA_ENTRADA1', ";
                  $query  .=" HORA_SALIDA1 = '$HORA_SALIDA1', ";
                  $query  .=" HORA_ENTRADA2 = '$HORA_ENTRADA2', ";
                  $query  .=" HORA_SALIDA2 = '$HORA_SALIDA2' ";
                  $query  .=" WHERE PROGRAMACION_VISITAS_DET_ID =$PROGRAMACION_VISITAS_DET_ID;";                 
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
 // <editor-fold defaultstate="collapsed" desc="Muestra los datos de la programación de visitas de proveedores">
 function MostrarProgramacionVisitaSucursalFecha($SUCURSAL_ID,$FECHA_PROGRAMACION){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    if ($conn){  
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE PROGRAMACION_VISITAS_DETALLE">                 
                $select  = " SELECT PVD.PROGRAMACION_VISITAS_DET_ID, PVD.PROGRAMACION_VISITAS_ID, PVD.SUCURSAL_ID, PVD.ASISTIO, PVD.PROVEEDOR_ID, ";
                $select .= " PVD.HORA_ENTRADA1, PVD.HORA_SALIDA1, PVD.HORA_ENTRADA2, PVD.HORA_SALIDA2,P.NOMBRE,PC.CLAVE";
                $select .= " /*,SPD.HORARIO_HORA_ENTRADA1,SPD.HORARIO_HORA_SALIDA1,SPD.HORARIO_HORA_ENTRADA2,SPD.HORARIO_HORA_SALIDA2*/";
                $select .= " FROM PROGRAMACION_VISITAS_DET AS PVD ";
                $select .= " INNER JOIN PROGRAMACION_VISITAS AS PV ON PVD.PROGRAMACION_VISITAS_ID=PV.PROGRAMACION_VISITAS_ID ";
                $select .= " INNER JOIN PROVEEDORES AS P ON PVD.PROVEEDOR_ID=P.PROVEEDOR_ID ";
                $select .= " INNER JOIN PROVEEDORES_CLIENTES AS PC ON P.PROVEEDOR_ID=PC.PROVEEDOR_ID ";
                $select .= " INNER JOIN SUCURSALES AS S ON PVD.SUCURSAL_ID=S.SUCURSAL_ID AND S.CLIENTE_ID=PC.CLIENTE_ID ";
                $select .= " /*INNER JOIN SUCURSALES_PROVEEDORES_DIAS AS SPD ON P.PROVEEDOR_ID=SPD.PROVEEDOR_ID AND S.SUCURSAL_ID=SPD.SUCURSAL_ID*/";
                $select .= " WHERE PVD.SUCURSAL_ID=$SUCURSAL_ID AND PV.ESTATUS='A' AND PV.FECHA_PROGRAMACION ='$FECHA_PROGRAMACION';";
                // echo $select        ;
                // </editor-fold>
                
                    $stmt = mysqli_query($conn, $select);            

                    if ($stmt){
                        while ($row = mysqli_fetch_assoc($stmt)){
                            $PVD["PROGRAMACION_VISITAS_DET_ID"] =$row["PROGRAMACION_VISITAS_DET_ID"];
                            $PVD["PROGRAMACION_VISITAS_ID"]     =$row["PROGRAMACION_VISITAS_ID"];
                            $PVD["SUCURSAL_ID"]                 =$row["SUCURSAL_ID"];
                            $PVD["ASISTIO"]                     =$row["ASISTIO"];
                            $PVD["PROVEEDOR_ID"]                =$row["PROVEEDOR_ID"];
                            $PVD["HORA_ENTRADA1"]               =$row["HORA_ENTRADA1"];
                            $PVD["HORA_SALIDA1"]                =$row["HORA_SALIDA1"];
                            $PVD["HORA_ENTRADA2"]               =$row["HORA_ENTRADA2"];
                            $PVD["HORA_SALIDA2"]                =$row["HORA_SALIDA2"];
                            $PVD["NOMBRE"]                      =$row["NOMBRE"];
                            $PVD["CLAVE"]                       =$row["CLAVE"];
                            $result[]=$PVD;
                            
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
    $server->register(
        'InsertarProgramacionVisitaDet',
        array(
            'PROGRAMACION_VISITAS_ID'=>'xsd:int',
            'SUCURSAL_ID'=>'xsd:int',
            'PROVEEDOR_ID'=>'xsd:int'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta renglon detalle de la programación de visitas de proveedores');

    $server->register(
        'ActualizaProgramacionVisitaDet',
        array(
            'PROGRAMACION_VISITAS_ID'=>'xsd:int',
            'SUCURSAL_ID'=>'xsd:int',
            'PROVEEDOR_ID'=>'xsd:int',
            'ASISTIO'=>'xsd:string',
            'HORA_ENTRADA1'=>'xsd:string',
            'HORA_SALIDA1'=>'xsd:string',
            'HORA_ENTRADA2'=>'xsd:string',
            'HORA_SALIDA2'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza la programación de visitas de proveedores');

        $server->wsdl->addComplexType(
            'MostrarProgramacionVisitaSucursalFecha',
            'complexType',
            'struct',
            'all',
            '',
            array(
                'PROGRAMACION_VISITAS_DET_ID' => array('name'=>'PROGRAMACION_VISITAS_DET_ID','type'=>'xsd:int'), 
                'PROGRAMACION_VISITAS_ID' => array('name'=>'PROGRAMACION_VISITAS_ID','type'=>'xsd:int'), 
                'SUCURSAL_ID' => array('name'=>'SUCURSAL_ID','type'=>'xsd:int'), 
                'ASISTIO' => array('name'=>'ASISTIO','type'=>'xsd:string'), 
                'PROVEEDOR_ID' => array('name'=>'PROVEEDOR_ID','type'=>'xsd:string'), 
                'HORA_ENTRADA1' => array('name'=>'HORA_ENTRADA1','type'=>'xsd:string'), 
                'HORA_SALIDA1'  => array('name'=>'HORA_SALIDA1','type'=>'xsd:string'), 
                'HORA_ENTRADA2' => array('name'=>'HORA_ENTRADA2','type'=>'xsd:string'), 
                'HORA_SALIDA2'  => array('name'=>'HORA_SALIDA2','type'=>'xsd:string') ,
                'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string') ,
                'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string') 
            )
        );    
        $server->wsdl->addComplexType(
            'MostrarProgramacionVisitaSucursalFechaArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarProgramacionVisitaSucursalFecha[]')),
            'tns:MostrarProgramacionVisitaSucursalFecha'
            );
        $server->register(
            'MostrarProgramacionVisitaSucursalFecha',
            array(
                'SUCURSAL_ID'=>'xsd:int',
                'FECHA_PROGRAMACION'=>'xsd:string'),
            array('return'=> 'tns:MostrarProgramacionVisitaSucursalFechaArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con la programación de visitas de proveedores');
    function BuscarProgramacionID($FECHA_PROGRAMACION){
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = 0;
        if ($conn){
            // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE PROGRAMACION_VISITAS_DETALLE">                 
            $select  = " SELECT PROGRAMACION_VISITAS_ID ";
            $select .= " FROM PROGRAMACION_VISITAS WHERE FECHA_PROGRAMACION ='$FECHA_PROGRAMACION';";                          
            // </editor-fold>            
                $stmt = mysqli_query($conn, $select); 
                if ($stmt){
                    while ($row = mysqli_fetch_assoc($stmt)){
                        $PVID =$row["PROGRAMACION_VISITAS_ID"];
                        $result=$PVID;                        
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
    }
    function ExisteProgramacionDetalle($conn,$FECHA_PROGRAMACION,$SUCURSAL_ID,$PROVEEDOR_ID){
        //$conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = 0;
        if ($conn){
            // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE PROGRAMACION_VISITAS_DETALLE">                 
            $select  = " SELECT PV.PROGRAMACION_VISITAS_ID,PVD.SUCURSAL_ID, PVD.PROVEEDOR_ID,PV.FECHA_PROGRAMACION ";
            $select .= " FROM soticomm_INMEX.PROGRAMACION_VISITAS_DET AS PVD ";
            $select .= " INNER JOIN soticomm_INMEX.PROGRAMACION_VISITAS AS PV ON PVD.PROGRAMACION_VISITAS_ID=PV.PROGRAMACION_VISITAS_ID";
            $select .= " WHERE ";
            $select .= " PVD.SUCURSAL_ID=$SUCURSAL_ID AND PVD.PROVEEDOR_ID=$PROVEEDOR_ID AND FECHA_PROGRAMACION='$FECHA_PROGRAMACION';";                          
            // </editor-fold>            
                $stmt = mysqli_query($conn, $select); 
                if ($stmt){
                    while ($row = mysqli_fetch_assoc($stmt)){
                        $PVID =$row["PROGRAMACION_VISITAS_ID"];
                        $result=1;                        
                    } 
                    //mysqli_close($conn);
                    return $result;
                }
                else{
                   // mysqli_close($conn);
                    return 0; 
                }
                //mysqli_close($conn);
            }
            else {
                // FALLO LA CONEXION
                return -1; 
            }
    }
    function InsertarProgramacionVisitasProveedores($FECHA_PROGRAMACION,$USUARIO_CREADOR,$FECHA_HORA_CREACION){
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = false;
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                    // <editor-fold defaultstate="collapsed" desc="INSERSION DE PROGRAMACION_VISITAS_DETALLE">                 
                      $query   ="INSERT INTO PROGRAMACION_VISITAS (FECHA_PROGRAMACION,ESTATUS,USUARIO_CREADOR,FECHA_HORA_CREACION) ";
                      $query .= " VALUES ('$FECHA_PROGRAMACION','A','$USUARIO_CREADOR','$FECHA_HORA_CREACION');";
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
    function InsertarProgramacionPorHorario($SUCURSAL_ID,$FECHA_PROGRAMACION,$USUARIO_CREADOR,$FECHA_HORA_CREACION,$DIASEMANA){//Prueba
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = false;
        $ProgNueva=false;
        $ProgVisId=-1;
        $insertar="";
        if ($conn){
            $ProgVisId=BuscarProgramacionID($FECHA_PROGRAMACION); //Busca la PROGRAMACION_ID
            //echo $ProgVisId;
            if($ProgVisId===0 || is_null($ProgVisId)){ //Si no encuentra la programación 
                $ProgNueva=true; //Bandera de programacion nueva
                if (InsertarProgramacionVisitasProveedores($FECHA_PROGRAMACION,$USUARIO_CREADOR,$FECHA_HORA_CREACION)){//intenta insertar el encabezado de la nueva programación
                    $ProgVisId=BuscarProgramacionID($FECHA_PROGRAMACION); //Busca la PROGRAMACION_ID
                }
                else{
                    $result = false;    //Si no puede insertar la nueva programación el resultado es falso
                }
            }
            if($ProgVisId===0 || is_null($ProgVisId)){//Si la PROGRAMACION_ID es nula o cero
                $result=false;  //El resultado es falso
            }
            else {
                if($ProgNueva==true){
                    //Si es una nueva programacion inserta todos los proveedores correspondientes al dia de la semana
                    $PROVEEDORES=MostrarVisitaProveedorSucursalDia($SUCURSAL_ID,$DIASEMANA); //Buscamos todos los proveedores en el dia de la semana especificado 
                    if(is_array($PROVEEDORES)){ //Validamos que sea un arreglo
                        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE); //Inicia la transacción para hacer el insert
                        for( $i=0;$i<count($PROVEEDORES);$i++) //Recorremos el arreglo
                        {
                            $ren = $PROVEEDORES[$i]; //obtenemos el renglon a insertar
                            $insertar  = " INSERT INTO PROGRAMACION_VISITAS_DET (PROGRAMACION_VISITAS_ID,SUCURSAL_ID,PROVEEDOR_ID) ";
                            $insertar .= " VALUES ($ProgVisId,$SUCURSAL_ID,".$ren["PROVEEDOR_ID"].");"; //Consulta insert tomando solo el PROVEEDOR_ID del renglon a insertar
                            if (mysqli_query($conn, $insertar)){ //Ejecutamo el insert
                                $result=true;// si hubo éxito el resultado es verdadero
                            }else 
                            {
                                $result=false;// si hubo algún error resultado es  falso
                                break;         // Y rompe el ciclo
                            }
                        }
                        if($result===true)
                        {
                            mysqli_commit($conn);// si el resultado es verdadero aplica las inserciones
                        }
                        else {
                            mysqli_rollback($conn);// si resultado es falso deshecha las inserciones
                        }
                    }
                    else {
                        $result=false; //Si no es un arreglo el resultado es falso
                    }
                }
                else {
                    //si no es una nueva programacion solo insertará los proveedores que no existan en la base de datos
                    $PROVEEDORES=MostrarVisitaProveedorSucursalDia($SUCURSAL_ID,$DIASEMANA); //Buscamos todos los proveedores en el dia de la semana especificado 
                    //echo $PROVEEDORES;
                    if(is_array($PROVEEDORES)){ //Validamos que sea un arreglo
                        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE); //Inicia la transacción para hacer el insert
                        for( $i=0;$i<count($PROVEEDORES);$i++) //Recorremos el arreglo
                        {
                            $ren = $PROVEEDORES[$i]; //obtenemos el renglon a insertar
                            $provId=$ren["PROVEEDOR_ID"];
                            //Buscamos si el proveedor se asigno a la sucursal del dia indicado
                            $existe=0;
                            $existe=ExisteProgramacionDetalle($conn,$FECHA_PROGRAMACION,$SUCURSAL_ID,$provId);
                            if($existe===0){
                                $insertar  = " INSERT INTO PROGRAMACION_VISITAS_DET (PROGRAMACION_VISITAS_ID,SUCURSAL_ID,PROVEEDOR_ID) ";
                                $insertar .= " VALUES ($ProgVisId,$SUCURSAL_ID,".$ren["PROVEEDOR_ID"].");"; //Consulta insert tomando solo el PROVEEDOR_ID del renglon a insertar
                                if (mysqli_query($conn, $insertar)){ //Ejecutamo el insert
                                    $result=true;// si hubo éxito el resultado es verdadero
                                }else 
                                {
                                    $result=false;// si hubo algún error resultado es  falso
                                    break;         // Y rompe el ciclo
                                }
                            }elseif($existe===-1) {
                                $result=false;
                                break;
                            }
                        }
                        if($result===true)
                        {
                            mysqli_commit($conn);// si el resultado es verdadero aplica las inserciones
                        }
                        else {
                            mysqli_rollback($conn);// si resultado es  falso deshecha las inserciones
                        }
                    }
                    else {
                        $result=false; //Si no es un arreglo el resultado es falso
                    }
                }
            }
            mysqli_close($conn); //Cierra la conexión 
            return $result; //Retorna el resultado
        }
        else{
            return $result; //retorna el resultado en caso de que no se haya podido establecer la conexión
        }
    }
    function InsertarProgramacionPorHorario2($SUCURSAL_ID,$FECHA_PROGRAMACION,$USUARIO_CREADOR,$FECHA_HORA_CREACION,$PROVEEDORES){//Prueba
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = false;
        $ProgNueva=false;
        $ProgVisId=0;
        $insertar="";
        if ($conn){  
                    // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE PROGRAMACION_VISITAS_DETALLE">                 
                    $select  = " SELECT PROGRAMACION_VISITAS_ID ";
                    $select .= " FROM PROGRAMACION_VISITAS WHERE FECHA_PROGRAMACION ='$FECHA_PROGRAMACION';";                                
                    // </editor-fold>                    
                        $stmt = mysqli_query($conn, $select);            
                        if ($stmt){
                            while ($row = mysqli_fetch_assoc($stmt)){                                
                                $ProgVisId=$row["PROGRAMACION_VISITAS_ID"];                                
                            } 
                            if($ProgVisId===0 || is_null($ProgVisId)){
                                //mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);  
                                $ProgNueva=true;
                                $insertar  = "INSERT INTO PROGRAMACION_VISITAS (FECHA_PROGRAMACION,ESTATUS,USUARIO_CREADOR,FECHA_HORA_CREACION) ";
                                $insertar .= " VALUES ('$FECHA_PROGRAMACION','A','$USUARIO_CREADOR','$FECHA_HORA_CREACION');";
                                if (mysqli_query($conn, $insertar)){
                                    //$result = true;
                                    //mysqli_commit($conn);
                                    $select  = "SELECT PROGRAMACION_VISITAS_ID ";
                                    $select .= " FROM PROGRAMACION_VISITAS WHERE FECHA_PROGRAMACION ='$FECHA_PROGRAMACION';";
                                    $stmt = mysqli_query($conn, $select);
                                    if ($stmt){
                                        while ($row = mysqli_fetch_assoc($stmt)){                                
                                            $ProgVisId=$row["PROGRAMACION_VISITAS_ID"];                                
                                        } 
                                    }
                                }
                                else{
                                    mysqli_rollback($conn);
                                    $result = false;
                                }
                            }
                            if($ProgVisId===0 || is_null($ProgVisId)){
                                $result=false;
                            }else {
                                if($ProgNueva==true){
                                    if(is_array($PROVEEDORES)){
                                        foreach ($PROVEEDORES as &$PROVEEDOR_ID) {
                                            $insertar  = " INSERT INTO PROGRAMACION_VISITAS_DET (PROGRAMACION_VISITAS_ID,SUCURSAL_ID,PROVEEDOR_ID) ";
                                            $insertar .= " VALUES ($ProgVisId,$SUCURSAL_ID,$PROVEEDOR_ID);";
                                            //mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE); 
                                            if (mysqli_query($conn, $insertar)){
                                                $result=true;
                                            }else {
                                                $result=false;
                                                break;
                                            }
                                        }
                                        if($result===true)
                                        {
                                            mysqli_commit($conn);
                                        }else {
                                            mysqli_rollback($conn);
                                        }
                                    }else {
                                        $result=false;
                                    }
                                }else {
                                    $result=false;
                                }
                            }
                            mysqli_close($conn);
                            return $result;
                        }
                        else{
                            mysqli_close($conn);
                            return $result; 
                        }
                        mysqli_close($conn);
                    }
                    else {
                        return $result; 
                    }
    }
    $server->wsdl->addComplexType(
        'PROVEEDORES',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'PROVEEDOR_ID' => array('name'=>'PROVEEDOR_ID','type'=>'xsd:int')
        )
    );    
    $server->wsdl->addComplexType(
        'PROVEEDORES',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:PROVEEDORES[]')),
        'tns:PROVEEDORES'
        );
    $server->register(
        'InsertarProgramacionPorHorario',
        array(
            'SUCURSAL_ID'=>'xsd:int',
            'FECHA_PROGRAMACION'=>'xsd:string',
            'USUARIO_CREADOR'=>'xsd:string',
            'FECHA_HORA_CREACION'=>'xsd:string',
            'DIASEMANA'=>'xsd:string'
        ),
        array('return'=> 'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta la programación de visitas de proveedores a una sucursal en una fecha determinada');