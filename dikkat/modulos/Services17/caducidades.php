<?php
function BuscarCaducidadID($FECHA,$SUCURSAL_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = 0;
    if ($conn){
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">
        $select  = "SELECT CADUCIDAD_ID FROM CADUCIDADES WHERE FECHA='$FECHA' AND SUCURSAL_ID=$SUCURSAL_ID AND ESTATUS='A';";
        // </editor-fold>    
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $caducidad=$row["CADUCIDAD_ID"];
                $result=$caducidad;
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
        return -1; 
    }
}
function BuscarCaducidadDetalleID($CADUCIDAD_ID,$ARTICULO_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = 0;
    if ($conn){
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">
        $select  = "SELECT CADUCIDADES_DETALLE_ID FROM CADUCIDADES_DETALLE WHERE ARTICULO_ID=$ARTICULO_ID AND CADUCIDAD_ID=$CADUCIDAD_ID;";
        // </editor-fold>    
        
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $caducidadDetalle=$row["CADUCIDADES_DETALLE_ID"];
                $result=$caducidadDetalle;
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
        return -1; 
    }
}
function BuscarCaducidadDetalleFechaID($CADUCIDAD_ID,$ARTICULO_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = 0;
    if ($conn){
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">
        $select  = "SELECT CADUCIDAD_DETALLE_FECHAS_ID FROM CADUCIDADES_DETALLE_FECHAS WHERE ARTICULO_ID='$ARTICULO_ID' AND CADUCIDAD_ID=$CADUCIDAD_ID;";
        // </editor-fold>    
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $caducidadDetalle=$row["CADUCIDAD_DETALLE_FECHAS_ID"];
                $result=$caducidadDetalle;
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
        return -1; 
    }
}
function MostrarCantidadCaducidades($CADUCIDADES_DETALLE_ID,$ARTICULO_ID,$conn){
    //$conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = 0;
    if ($conn){
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">
        $select  = " SELECT SUM(CANTIDAD) AS CANTIDAD FROM CADUCIDADES_DETALLE AS CD ";
        $select .= " INNER JOIN CADUCIDADES_DETALLE_FECHAS AS CDF ON CD.CADUCIDADES_DETALLE_ID=CDF.CADUCIDADES_DETALLE_ID ";
        $select .= " WHERE ARTICULO_ID=$ARTICULO_ID AND CD.CADUCIDADES_DETALLE_ID=$CADUCIDADES_DETALLE_ID;";
        // </editor-fold>    
        
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $caducidadDetalle=$row["CANTIDAD"];
                $result=$caducidadDetalle;
            }
        //mysqli_close($conn);
            return $result;
        }
        else{
            //mysqli_close($conn);
            return 0; 
        }
        //mysqli_close($conn);
    }
    else {
        // FALLO LA CONEXION
        return -1; 
    }
}
function ActualizaEstatusCaducidad($CADUCIDAD_ID,$ESTATUS,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE LA CATEGORÍA">                 
                  $query  = "UPDATE CADUCIDADES ";
                  $query .= " SET ";
                  $query .= " ESTATUS = '$ESTATUS', ";
                  $query .= " USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
                  $query .= " FECHA_HORA_MODIFICACION = '$FECHA_HORA_MODIFICACION' ";
                  $query .= " WHERE CADUCIDAD_ID = $CADUCIDAD_ID;";                 
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
function ActualizarCaducidad( $CADUCIDADES_DETALLE_ID,$PRECIO,$CANTIDAD_PIEZAS/*,$UBICACION*/,$EXHIBIDO,$ESPACIO_ASIGNADO,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE LA CATEGORÍA">                 
                    if($ESPACIO_ASIGNADO==="N"&&$EXHIBIDO==="N"){
                    $EXISTENCIAS=" EXISTENCIAS = 'N', ";
                }              
                  $query  = "UPDATE CADUCIDADES_DETALLE ";
                  $query .= " SET ";
                  $query .= " PRECIO = '$PRECIO', ";
                  $query .= " $EXISTENCIAS ";
                  $query .= " CANTIDAD_PIEZAS = $CANTIDAD_PIEZAS, ";              
                  $query .= " EXHIBIDO = '$EXHIBIDO', ";
                  $query .= " ESPACIO_ASIGNADO = '$ESPACIO_ASIGNADO', ";
                  $query .= " USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
                  $query .= " FECHA_HORA_MODIFICACION = '$FECHA_HORA_MODIFICACION' ";
                  $query .= " WHERE CADUCIDADES_DETALLE_ID = $CADUCIDADES_DETALLE_ID;";                 
                // </editor-fold>
                // Echo $query;
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
    'ActualizarCaducidad',
    array(//$,$,$,$,$,$
        'CADUCIDADES_DETALLE_ID'=>'xsd:int',
        'PRECIO'=>'xsd:string',
        'CANTIDAD_PIEZAS'=>'xsd:int',
        /*'UBICACION'=>'xsd:string',*/
        'EXHIBIDO'=>'xsd:string',
        'ESPACIO_ASIGNADO'=>'xsd:string',
        'USUARIO_MODIFICACION'=>'xsd:string',
        'FECHA_HORA_MODIFICACION'=>'xsd:string',
        'BD'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza la hora de inicio y la hora de fin de caducidades');
function ActualizarCaducidadDetalleCantidad( $CADUCIDADES_DETALLE_ID,$CANTIDAD_PIEZAS,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn){ 
        if($CANTIDAD_PIEZAS>0)
        {
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE); 
            $query  = "UPDATE CADUCIDADES_DETALLE ";
            $query .= " SET ";
            $query .= " EXISTENCIAS = 'S', ";
            $query .= " CANTIDAD_PIEZAS = $CANTIDAD_PIEZAS, ";
            $query .= " USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
            $query .= " FECHA_HORA_MODIFICACION = '$FECHA_HORA_MODIFICACION' ";
            $query .= " WHERE CADUCIDADES_DETALLE_ID = $CADUCIDADES_DETALLE_ID;";  
            
            if (mysqli_query($conn, $query)){
                $result = true;
                
                mysqli_commit($conn);
            }
            else{
                $result = false;
                
                mysqli_rollback($conn);
            }
                mysqli_close($conn);
        }
    }
    else {
        // FALLO LA CONEXION
        $result = false;
    }
return $result;
}
function InsertarFechaCaducidadArticulo($CADUCIDADES_DETALLE_ID,$ARTICULO_ID,$ARTICULO_LOTE,$FECHA_CADUCIDAD,$CANTIDAD,$UBICACION,$PRECIO,$OBSERVACIONES,$POSICION,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION, $BD)
{
    $result = false;
    /*$CADUCIDADES_DETALLE_ID=0;
    $CADUCIDADES_DETALLE_ID=BuscarCaducidadDetalleID($CADUCIDAD_ID,$ARTICULO_ID);
    */
    if($CADUCIDADES_DETALLE_ID>0)
    {
        $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
        //Buscar el detalle de la caducidad
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE); 
            $query  = "INSERT INTO CADUCIDADES_DETALLE_FECHAS(CADUCIDADES_DETALLE_ID,ARTICULO_LOTE,FECHA_CADUCIDAD,CANTIDAD,UBICACION,OBSERVACIONES,POSICION) ";
            $query .= " VALUES($CADUCIDADES_DETALLE_ID,'$ARTICULO_LOTE','$FECHA_CADUCIDAD',$CANTIDAD,'$UBICACION','$OBSERVACIONES',$POSICION);";   
            if (mysqli_query($conn, $query)){
                $Cant=MostrarCantidadCaducidades($CADUCIDADES_DETALLE_ID,$ARTICULO_ID,$conn);
                if($Cant>=0)
                {
                    $query  = "UPDATE CADUCIDADES_DETALLE ";
                    $query .= " SET ";
                    $query .= " EXISTENCIAS = 'S', ";
                    $query .= " PRECIO = '$PRECIO', ";
                    $query .= " CANTIDAD_PIEZAS = $Cant,";
                    $query .= " EXHIBIDO = 'S', ";
                    $query .= " ESPACIO_ASIGNADO = 'S' , ";
                    $query .= " USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
                    $query .= " FECHA_HORA_MODIFICACION = '$FECHA_HORA_MODIFICACION' ";
                    $query .= " WHERE CADUCIDADES_DETALLE_ID = $CADUCIDADES_DETALLE_ID;";
                    if(mysqli_query($conn, $query)){
                        
                        $result = true;
                        
                        mysqli_commit($conn);
                    }
                    else{
                        
                        mysqli_rollback($conn);
                        $result = false;
                    }
                }
                else{
                    mysqli_rollback($conn);
                    $result = false;
                    
                }
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
function MostrarCaducidadesSucursal($SUCURSAL_ID,$FECHA_REVISION, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    $hostname=$_SERVER['SERVER_NAME'];
    if ($conn){
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">                 
        $select  = "SELECT C.CADUCIDAD_ID,CD.CADUCIDADES_DETALLE_ID,A.ARTICULO_ID,A.SKU,A.DESCRIPCION AS NOMBRE,CD.CANTIDAD_PIEZAS,CDF.FECHA_CADUCIDAD,CDF.POSICION";
        $select .= " ,CDF.CANTIDAD,A.IMAGEN,CDF.ARTICULO_LOTE,CDF.UBICACION,CD.EXISTENCIAS,CD.PRECIO,CDF.OBSERVACIONES, CD.EXHIBIDO,CD.ESPACIO_ASIGNADO ";
        $select .= " FROM CADUCIDADES AS C ";
        $select .= " INNER JOIN CADUCIDADES_DETALLE  AS CD ON C.CADUCIDAD_ID=CD.CADUCIDAD_ID ";
        $select .= " INNER JOIN ARTICULOS AS A ON CD.ARTICULO_ID=A.ARTICULO_ID ";
        $select .= " LEFT JOIN CADUCIDADES_DETALLE_FECHAS  AS CDF ON CD.CADUCIDADES_DETALLE_ID =CDF.CADUCIDADES_DETALLE_ID ";
        $select .= " WHERE C.ESTATUS='A' AND CD.SUCURSAL_ID=$SUCURSAL_ID AND C.FECHA_REVISION='$FECHA_REVISION';";
        // </editor-fold>
    //echo $select;
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){

                $caducidades["CADUCIDAD_ID"]            =$row["CADUCIDAD_ID"];
                $caducidades["CADUCIDADES_DETALLE_ID"]  =$row["CADUCIDADES_DETALLE_ID"];
                $caducidades["ARTICULO_ID"]             =$row["ARTICULO_ID"];
                $caducidades["SKU"]                     =$row["SKU"];
                $caducidades["NOMBRE"]                  =$row["NOMBRE"];
                $caducidades["PRECIO"]                  =$row["PRECIO"];
                $caducidades["CANTIDAD_PIEZAS"]         =$row["CANTIDAD_PIEZAS"];
                $caducidades["FECHA_CADUCIDAD"]         =$row["FECHA_CADUCIDAD"];
                $caducidades["CANTIDAD"]                =$row["CANTIDAD"];
                $caducidades["IMAGEN"]                  =$hostname."/articulos/". $row["IMAGEN"];
                $caducidades["ARTICULO_LOTE"]           =$row["ARTICULO_LOTE"];
                $caducidades["UBICACION"]               =$row["UBICACION"];
                $caducidades["EXISTENCIAS"]             =$row["EXISTENCIAS"];
                $caducidades["OBSERVACIONES"]           =$row["OBSERVACIONES"];
                $caducidades["POSICION"]                =$row["POSICION"];
                $caducidades["EXHIBIDO"]                =$row["EXHIBIDO"];
                $caducidades["ESPACIO_ASIGNADO"]        =$row["ESPACIO_ASIGNADO"];
                $result[]=$caducidades;                            
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

$server->wsdl->addComplexType(
    'MostrarCaducidadesSucursal',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'CADUCIDAD_ID' => array('name'=>'CADUCIDAD_ID','type'=>'xsd:int'),
        'CADUCIDADES_DETALLE_ID' => array('name'=>'CADUCIDADES_DETALLE_ID','type'=>'xsd:int'),
        'ARTICULO_ID' => array('name'=>'ARTICULO_ID','type'=>'xsd:int'),
        'SKU' => array('name'=>'SKU','type'=>'xsd:string'),
        'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
        'PRECIO' => array('name'=>'PRECIO','type'=>'xsd:string'),
        'CANTIDAD_PIEZAS' => array('name'=>'CANTIDAD_PIEZAS','type'=>'xsd:string'),
        'FECHA_CADUCIDAD' => array('name'=>'FECHA_CADUCIDAD','type'=>'xsd:string'),
        'CANTIDAD' => array('name'=>'CANTIDAD','type'=>'xsd:string'),
        'IMAGEN' => array('name'=>'IMAGEN','type'=>'xsd:string'),
        'ARTICULO_LOTE' => array('name'=>'ARTICULO_LOTE','type'=>'xsd:string'),
        'UBICACION' => array('name'=>'UBICACION','type'=>'xsd:string'),
        'EXISTENCIAS' => array('name'=>'EXISTENCIAS','type'=>'xsd:string'),
        'OBSERVACIONES' => array('name'=>'OBSERVACIONES','type'=>'xsd:string'),
        'POSICION' => array('name'=>'POSICION','type'=>'xsd:int'),
        'EXHIBIDO' => array('name'=>'EXHIBIDO','type'=>'xsd:string'),
        'ESPACIO_ASIGNADO' => array('name'=>'ESPACIO_ASIGNADO','type'=>'xsd:string')
    )
);    
$server->wsdl->addComplexType(
    'MostrarCaducidadesSucursalArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarCaducidadesSucursal[]')),
    'tns:MostrarCaducidadesSucursal'
    );
$server->register(
    'MostrarCaducidadesSucursal',
    array(
        'SUCURSAL_ID'=>'xsd:int',
        'FECHA_REVISION'=>'xsd:string',
        'BD'=>'xsd:string'
    ),
    array('return'=> 'tns:MostrarCaducidadesSucursalArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con las articulos a revisión de caducidad en sucursal determinada');

    $server->register(
        'InsertarFechaCaducidadArticulo',
        array(
            'CADUCIDADES_DETALLE_ID'=>'xsd:int',
            'ARTICULO_ID'=>'xsd:int',
            'ARTICULO_LOTE'=>'xsd:string',
            'FECHA_CADUCIDAD'=>'xsd:string',
            'CANTIDAD'=>'xsd:int',
            'UBICACION'=>'xsd:string',
            'PRECIO'=>'xsd:string',
            'OBSERVACIONES'=>'xsd:string',
            'POSICION'=>'xsd:int',
            'USUARIO_MODIFICACION'=>'xsd:string',
            'FECHA_HORA_MODIFICACION'=>'xsd:string',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta una fecha de caducidad de un articulo en el sistema');
    function ActualizarHoraInicioFinCaducidades($TIPO,$HORA,$CADUCIDADES_ID,$SUCURSAL_ID, $BD){
        $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
        $result = false;
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE); 
            
            $query  = "";            
            
            if ($TIPO==="I") {
                //$TIPO_ACTUALIZACION="HORA_INICIO ='$HORA',";
                //Insertar
                $CADUCIDADES_DETALLE_SUCURSALES_ID=0;
                $select  ="SELECT CADUCIDADES_DETALLE_SUCURSALES_ID FROM CADUCIDADES_DETALLE_SUCURSALES ";
                $select .=" WHERE CADUCIDADES_ID=$CADUCIDADES_ID AND SUCURSAL_ID=$SUCURSAL_ID;";
                $stmt = mysqli_query($conn, $select);  
        //ECHO $select;
                if ($stmt){
                    while ($row = mysqli_fetch_assoc($stmt)){
                        $CADUCIDADES_DETALLE_SUCURSALES_ID=$row["CADUCIDADES_DETALLE_SUCURSALES_ID"];
                    }
                }
                if($CADUCIDADES_DETALLE_SUCURSALES_ID===0){
                    $query  ="INSERT INTO CADUCIDADES_DETALLE_SUCURSALES (CADUCIDADES_ID,SUCURSAL_ID,HORA_INICIO) ";
                    $query .=" VALUES($CADUCIDADES_ID,$SUCURSAL_ID,'$HORA');";
                    //echo $query;
                }
            }elseif($TIPO==="F") {
                //$TIPO_ACTUALIZACION="HORA_FIN = '$HORA',";
                // Buscar el id de la asignación detalle sucursal
                $CADUCIDADES_DETALLE_SUCURSALES_ID=0;
                $select  ="SELECT CADUCIDADES_DETALLE_SUCURSALES_ID FROM CADUCIDADES_DETALLE_SUCURSALES ";
                $select .=" WHERE CADUCIDADES_ID=$CADUCIDADES_ID AND SUCURSAL_ID=$SUCURSAL_ID;";
                $stmt = mysqli_query($conn, $select);  
        
                if ($stmt){
                    while ($row = mysqli_fetch_assoc($stmt)){
                        $CADUCIDADES_DETALLE_SUCURSALES_ID=$row["CADUCIDADES_DETALLE_SUCURSALES_ID"];
                    }
                }
                if($CADUCIDADES_DETALLE_SUCURSALES_ID>0){
                    //Actualizar
                    $query  ="UPDATE CADUCIDADES_DETALLE_SUCURSALES ";
                    $query .=" SET ";
                    $query .=" HORA_FIN = '$HORA' ";
                    $query .=" WHERE CADUCIDADES_DETALLE_SUCURSALES_ID = $CADUCIDADES_DETALLE_SUCURSALES_ID AND HORA_FIN IS NULL;";
                }
            }  
            
            if(strlen($query)>0){
                if (mysqli_query($conn, $query)){
                    $result = true;
                    mysqli_commit($conn);
                }
                else{
                    mysqli_rollback($conn);
                    $result = false;
                }
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
        'ActualizarHoraInicioFinCaducidades',
        array(
            'TIPO'=>'xsd:string',
            'HORA'=>'xsd:string',
            'CADUCIDADES_ID'=>'xsd:int',
            'SUCURSAL_ID'=>'xsd:int',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza la hora de inicio y la hora de fin de caducidades');

    function ExisteCaducidad($CADUCIDADES_ID,$SUCURSAL_ID, $BD){
        $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
        $result = false;
        if ($conn){
            $select  ="SELECT CADUCIDADES_DETALLE_SUCURSALES_ID FROM CADUCIDADES_DETALLE_SUCURSALES ";
            $select .=" WHERE CADUCIDADES_ID=$CADUCIDADES_ID AND SUCURSAL_ID=$SUCURSAL_ID;";
            $stmt = mysqli_query($conn, $select);              
            if ($stmt){
                while ($row = mysqli_fetch_assoc($stmt)){
                    $result=true;
                }
            }else {
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
    'ExisteCaducidad',
    array(
        'CADUCIDADES_ID'=>'xsd:int',
        'SUCURSAL_ID'=>'xsd:int',
        'BD'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Muestra si ya existe un registro de caducidades');

    function CaducidadSinExistencia($CADUCIDADES_ID,$SUCURSAL_ID,$ARTICULO_ID,$PRECIO,$ESPACIO_ASIGNADO,$EXHIBIDO,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION, $BD){
        $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
        $result = false;
        if ($conn){
            if(strlen(($PRECIO)==0) || ($PRECIO==="")|| $PRECIO===" "){
                $PRECIO='0.00';
            }
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE); 
            $query  ="UPDATE CADUCIDADES_DETALLE ";
            $query .=" SET ";
            $query .=" EXISTENCIAS = 'N', ";
            $query .=" PRECIO = '$PRECIO', ";
            $query .=" CANTIDAD_PIEZAS = 0, ";
            $query .=" ESPACIO_ASIGNADO = '$ESPACIO_ASIGNADO', ";
            $query .=" EXHIBIDO = '$EXHIBIDO', ";
            $query .=" USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
            $query .=" FECHA_HORA_MODIFICACION = '$FECHA_HORA_MODIFICACION' ";
            $query .=" WHERE CADUCIDAD_ID=$CADUCIDADES_ID AND SUCURSAL_ID=$SUCURSAL_ID AND ARTICULO_ID=$ARTICULO_ID;";
            
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
        'CaducidadSinExistencia',
        array(
            'CADUCIDADES_ID'=>'xsd:int',
            'SUCURSAL_ID'=>'xsd:int',
            'ARTICULO_ID'=>'xsd:int',
            'PRECIO'=>'xsd:string',
            'ESPACIO_ASIGNADO'=>'xsd:string',
            'EXHIBIDO'=>'xsd:string',
            'USUARIO_MODIFICACION'=>'xsd:string',
            'FECHA_HORA_MODIFICACION'=>'xsd:string',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Muestra si ya existe un registro de caducidades');

    function MuestraHoraInicioFinCaducidad($CADUCIDADES_ID,$SUCURSAL_ID, $BD){
        $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
        $result = null;
        if ($conn){
            $select  ="SELECT HORA_INICIO,HORA_FIN FROM CADUCIDADES_DETALLE_SUCURSALES ";
            $select .=" WHERE CADUCIDADES_ID=$CADUCIDADES_ID AND SUCURSAL_ID=$SUCURSAL_ID;";
            $stmt = mysqli_query($conn, $select);              
            if ($stmt){
                while ($row = mysqli_fetch_assoc($stmt)){
                    $caducidades["HORA_INICIO"] =$row["HORA_INICIO"];
                    $caducidades["HORA_FIN"]    =$row["HORA_FIN"];
                    $result[]=$caducidades;  
                }
            }else {
                $result = null;
            }
            mysqli_close($conn);
        }
        else {
            // FALLO LA CONEXION
            $result = null;
        }
    return $result;
    }
    $server->wsdl->addComplexType(
        'MuestraHoraInicioFinCaducidad',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'HORA_INICIO' => array('name'=>'HORA_INICIO','type'=>'xsd:string'),
            'HORA_FIN' => array('name'=>'HORA_FIN','type'=>'xsd:string')
        )
    );    
    $server->wsdl->addComplexType(
        'MuestraHoraInicioFinCaducidadArray',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MuestraHoraInicioFinCaducidad[]')),
        'tns:MuestraHoraInicioFinCaducidad'
        );
    $server->register(
        'MuestraHoraInicioFinCaducidad',
        array(
            'CADUCIDADES_ID'=>'xsd:int',
            'SUCURSAL_ID'=>'xsd:int',
            'BD'=>'xsd:string'
        ),
        array('return'=> 'tns:MuestraHoraInicioFinCaducidadArray'),
        $namespace,
        false,
        'rpc',
        false,
        'Muestra la hora de inicio y fin de la tarea de caducidades');
         function MostrarFechasCaducidadesArticulo($SUCURSAL_ID,$FECHA_REVISION,$ARTICULO_ID, $BD)
        {
            $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
            $result = null;
            $hostname=$_SERVER['SERVER_NAME'];
            if ($conn){
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">                 
                $select  = "SELECT A.ARTICULO_ID,A.SKU,A.NOMBRE,CDF.FECHA_CADUCIDAD";
                $select .= " ,CDF.CANTIDAD,A.IMAGEN,CDF.ARTICULO_LOTE,CDF.UBICACION,CDF.OBSERVACIONES,CDF.POSICION ";
                $select .= " FROM CADUCIDADES AS C ";
                $select .= " INNER JOIN CADUCIDADES_DETALLE  AS CD ON C.CADUCIDAD_ID=CD.CADUCIDAD_ID ";
                $select .= " INNER JOIN ARTICULOS AS A ON CD.ARTICULO_ID=A.ARTICULO_ID ";
                $select .= " LEFT JOIN CADUCIDADES_DETALLE_FECHAS  AS CDF ON CD.CADUCIDADES_DETALLE_ID =CDF.CADUCIDADES_DETALLE_ID ";
                $select .= " WHERE CD.SUCURSAL_ID=$SUCURSAL_ID AND C.FECHA_REVISION='$FECHA_REVISION' AND C.ARTICULO_ID=$ARTICULO_ID;";
                // </editor-fold>
            
                $stmt = mysqli_query($conn, $select);            
        
                if ($stmt){
                    while ($row = mysqli_fetch_assoc($stmt)){
        
                        $caducidades["ARTICULO_ID"]             =$row["ARTICULO_ID"];
                        $caducidades["SKU"]                     =$row["SKU"];
                        $caducidades["NOMBRE"]                  =$row["NOMBRE"];
                        $caducidades["FECHA_CADUCIDAD"]         =$row["FECHA_CADUCIDAD"];
                        $caducidades["CANTIDAD"]                =$row["CANTIDAD"];
                        $caducidades["IMAGEN"]                  =$hostname."/articulos/". $row["IMAGEN"];
                        $caducidades["ARTICULO_LOTE"]           =$row["ARTICULO_LOTE"];
                        $caducidades["UBICACION"]               =$row["UBICACION"];
                        $caducidades["OBSERVACIONES"]           =$row["OBSERVACIONES"];
                        $caducidades["POSICION"]                =$row["POSICION"];
                        $result[]=$caducidades;                            
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
        
        $server->wsdl->addComplexType(
            'MostrarFechasCaducidadesArticulo',
            'complexType',
            'struct',
            'all',
            '',
            array(
                'ARTICULO_ID' => array('name'=>'ARTICULO_ID','type'=>'xsd:int'),
                'SKU' => array('name'=>'SKU','type'=>'xsd:string'),
                'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
                'FECHA_CADUCIDAD' => array('name'=>'FECHA_CADUCIDAD','type'=>'xsd:string'),
                'CANTIDAD' => array('name'=>'CANTIDAD','type'=>'xsd:string'),
                'IMAGEN' => array('name'=>'IMAGEN','type'=>'xsd:string'),
                'ARTICULO_LOTE' => array('name'=>'ARTICULO_LOTE','type'=>'xsd:string'),
                'UBICACION' => array('name'=>'UBICACION','type'=>'xsd:string'),
                'EXISTENCIAS' => array('name'=>'EXISTENCIAS','type'=>'xsd:string'),
                'OBSERVACIONES' => array('name'=>'OBSERVACIONES','type'=>'xsd:string'),
                'POSICION'=>array('name'=>'POSICION','type'=>'xsd:int')
            )
        );    
        $server->wsdl->addComplexType(
            'MostrarFechasCaducidadesArticuloArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarFechasCaducidadesArticulo[]')),
            'tns:MostrarFechasCaducidadesArticulo'
            );
        $server->register(
            'MostrarFechasCaducidadesArticulo',
            array(
                'SUCURSAL_ID'=>'xsd:int',
                'FECHA_REVISION'=>'xsd:string',
                'ARTICULO_ID'=>'xsd:int',
                'BD'=>'xsd:string'
            ),
            array('return'=> 'tns:MostrarFechasCaducidadesArticuloArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con las articulos a revisión de caducidad en sucursal determinada');
            
function ActualizarPEPS($CADUCIDADES_DETALLE_ID,$ARTICULO_ID,$PEPS,$BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    $EXISTENCIAS="";
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE); 
        $query  = "UPDATE CADUCIDADES_DETALLE ";
        $query .= " SET ";
        $query .= " PEPS = $PEPS ";
        $query .= " WHERE CADUCIDADES_DETALLE_ID = $CADUCIDADES_DETALLE_ID AND ARTICULO_ID=$ARTICULO_ID;";
    //Echo $query;
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
        $result = false;
    }
return $result;
}
$server->register(
    'ActualizarPEPS',
    array(
        'CADUCIDADES_DETALLE_ID'=>'xsd:int',
        'ARTICULO_ID'=>'xsd:int',
        'PEPS'=>'xsd:int',
        'BD'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza primeras entradas primeras salidas de caducidades detalle');
    

function InsertarCaducidadDetalle($usuario_id, $sucursal_id, $caducidad_id, $sku, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($caducidad_id != 0) {

    if ($conn) {
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

        // Verificar si el SKU ya existe en CADUCIDADES_DETALLE para la combinación de sucursal_id y caducidad_id
        $stmt_verificar = $conn->prepare("SELECT COUNT(*) AS total FROM CADUCIDADES_DETALLE WHERE SUCURSAL_ID = ? AND CADUCIDAD_ID = ? AND ARTICULO_ID = (SELECT ARTICULO_ID FROM ARTICULOS WHERE SKU = ?)");
        $stmt_verificar->bind_param("iis", $sucursal_id, $caducidad_id, $sku);
        $stmt_verificar->execute();
        $result_verificar = $stmt_verificar->get_result();
        $row_verificar = $result_verificar->fetch_assoc();

        // Si ya existe el SKU en esa combinación, mostrar mensaje y retornar false
        if ($row_verificar['total'] > 0) {
            
            mysqli_rollback($conn);
            mysqli_close($conn);
            return 'YA EXISTE';
        }

        // INSERTAR EN LA TABLA CADUCIDADES_DETALLE
        $result_detalle = $conn->prepare("INSERT INTO CADUCIDADES_DETALLE(SUCURSAL_ID, CADUCIDAD_ID, ARTICULO_ID, PRECIO, CANTIDAD_PIEZAS, USUARIO_ID) VALUES (?, ?, (SELECT ARTICULO_ID FROM ARTICULOS WHERE SKU = ?), (SELECT PRECIO FROM ARTICULOS WHERE SKU = ?), 0, ?)");

        // Reemplazar los '?' por las variables correspondientes
        $result_detalle->bind_param("iissi", $sucursal_id, $caducidad_id, $sku, $sku, $usuario_id);

        // Ejecutar la consulta
        if ($result_detalle->execute()) {
            mysqli_commit($conn);
            return '1';
        } else {
            mysqli_rollback($conn);
            return '0';
        }

        mysqli_close($conn);
    } else {
        return '0';
    }
    }
    else
    {
        return '0';
    }
}


    
 $server->register(
        'InsertarCaducidadDetalle',
        array(
            'usuario_id'=>'xsd:int',
            'sucursal_id'=>'xsd:int',
            'caducidad_id'=>'xsd:int',
            'sku'=>'xsd:string',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:string'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta un detalle a una asignacion de caducidad');    
    