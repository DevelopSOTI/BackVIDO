<?php
function MostrarMysteryShopper($SUCURSAL_ID,$FECHA_REVISION,$USUARIO_ASIGNADO){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    if ($conn){
        $select  = " SELECT MS.MYSTERY_SHOPPER_ID,MSD.MYSTERY_SHOPPER_DETALLE_ID,MSD.TAREA_DET_ID,MSD.ESTATUS,T.NOMBRE AS GRUPO_TAREA,TD.DESCRIPCION ";
        $select .= " ,RTD.RESPUESTA_TAREA_DETALLE_ID,RTD.TIPO_DATO,RTD.RESULTADO,RI.IMAGEN,RI.TIPO_TAREA,TD.REQUIERE_IMAGEN,TD.TIPO_DATO as TIPO_DATO_TAREA,TD.NA, TD.LIBRE ";
        $select .= " FROM MYSTERY_SHOPPER AS MS ";
        $select .= " INNER JOIN MYSTERY_SHOPPER_DETALLE AS MSD ON MS.MYSTERY_SHOPPER_ID=MSD.MYSTERY_SHOPPER_ID ";
        $select .= " INNER JOIN TAREA_DETALLE AS TD ON MSD.TAREA_DET_ID=TD.TAREA_DET_ID ";
        $select .= " INNER JOIN TAREAS AS T ON TD.TAREA_ID=T.TAREA_ID ";
        $select .= " INNER JOIN USUARIOS AS U ON MS.USUARIO_ASIGNADO=U.USUARIO_ID ";
        $select .= " LEFT JOIN RESPUESTA_TAREA_DETALLE AS RTD ON MSD.MYSTERY_SHOPPER_DETALLE_ID=RTD.MYSTERY_SHOPPER_DETALLE_ID ";
        $select .= " LEFT JOIN REPOSITORIO_IMAGEN AS RI ON RTD.RESPUESTA_TAREA_DETALLE_ID=RI.RESPUESTA_TAREA_DETALLE_ID AND RI.TIPO_TAREA LIKE '%MYSTERY_SHOPPER%' ";
        $select .= " WHERE MS.SUCURSAL_ID=$SUCURSAL_ID AND MS.FECHA_REVISION='$FECHA_REVISION' AND U.USUARIO='$USUARIO_ASIGNADO' AND MS.ESTATUS='A' AND T.ESTATUS = 'A'";
        $select .= " ORDER BY T.RENGLON, TD.RENGLON ASC;";
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $mystery_shopper["MYSTERY_SHOPPER_ID"]          =$row["MYSTERY_SHOPPER_ID"];
                $mystery_shopper["MYSTERY_SHOPPER_DETALLE_ID"]  =$row["MYSTERY_SHOPPER_DETALLE_ID"];
                $mystery_shopper["TAREA_DET_ID"]                =$row["TAREA_DET_ID"];
                $mystery_shopper["ESTATUS"]                     =$row["ESTATUS"];
                $mystery_shopper["GRUPO_TAREA"]                 =$row["GRUPO_TAREA"];
                $mystery_shopper["DESCRIPCION"]                 =$row["DESCRIPCION"];
                $mystery_shopper["RESPUESTA_TAREA_DETALLE_ID"]  =$row["RESPUESTA_TAREA_DETALLE_ID"];
                $mystery_shopper["TIPO_DATO"]                   =$row["TIPO_DATO"];
                $mystery_shopper["RESULTADO"]                   =$row["RESULTADO"];
                $mystery_shopper["IMAGEN"]                      =$row["IMAGEN"];
                $mystery_shopper["TIPO_TAREA"]                  =$row["TIPO_TAREA"];
                $mystery_shopper["REQUIERE_IMAGEN"]             =$row["REQUIERE_IMAGEN"];
                $mystery_shopper["TIPO_DATO_TAREA"]             =$row["TIPO_DATO_TAREA"];
                $mystery_shopper["NA"]                          =$row["NA"];
                $mystery_shopper["LIBRE"]                       =$row["LIBRE"];
                $result[]=$mystery_shopper;                            
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
        return null; 
    }
}
function MostrarMysteryShopperGrupoTarea($SUCURSAL_ID,$FECHA_REVISION,$USUARIO_ASIGNADO,$GRUPO_TAREA){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    if ($conn){
        $select  = " SELECT MS.MYSTERY_SHOPPER_ID,MSD.MYSTERY_SHOPPER_DETALLE_ID,MSD.TAREA_DET_ID,MSD.ESTATUS,T.NOMBRE AS GRUPO_TAREA,TD.DESCRIPCION , TD.CLAVE ";
        $select .= " ,RTD.RESPUESTA_TAREA_DETALLE_ID,RTD.TIPO_DATO,RTD.RESULTADO,RI.IMAGEN,RI.TIPO_TAREA,TD.REQUIERE_IMAGEN,TD.TIPO_DATO as TIPO_DATO_TAREA,TD.NA, TD.LIBRE ";
        $select .= " FROM MYSTERY_SHOPPER AS MS ";
        $select .= " INNER JOIN MYSTERY_SHOPPER_DETALLE AS MSD ON MS.MYSTERY_SHOPPER_ID=MSD.MYSTERY_SHOPPER_ID ";
        $select .= " INNER JOIN TAREA_DETALLE AS TD ON MSD.TAREA_DET_ID=TD.TAREA_DET_ID ";
        $select .= " INNER JOIN TAREAS AS T ON TD.TAREA_ID=T.TAREA_ID ";
        $select .= " INNER JOIN USUARIOS AS U ON MS.USUARIO_ASIGNADO=U.USUARIO_ID ";
        $select .= " LEFT JOIN RESPUESTA_TAREA_DETALLE AS RTD ON MSD.MYSTERY_SHOPPER_DETALLE_ID=RTD.MYSTERY_SHOPPER_DETALLE_ID ";
        $select .= " LEFT JOIN REPOSITORIO_IMAGEN AS RI ON RTD.RESPUESTA_TAREA_DETALLE_ID=RI.RESPUESTA_TAREA_DETALLE_ID AND RI.TIPO_TAREA LIKE '%MYSTERY_SHOPPER%' ";
        $select .= " WHERE MS.SUCURSAL_ID=$SUCURSAL_ID AND MS.FECHA_REVISION='$FECHA_REVISION' AND U.USUARIO='$USUARIO_ASIGNADO' AND MS.ESTATUS='A' AND T.ESTATUS = 'A'";
        $select .= " AND T.NOMBRE = '$GRUPO_TAREA' ";
        $select .= " ORDER BY T.RENGLON, TD.RENGLON ASC;";
        
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $mystery_shopper["MYSTERY_SHOPPER_ID"]          =$row["MYSTERY_SHOPPER_ID"];
                $mystery_shopper["MYSTERY_SHOPPER_DETALLE_ID"]  =$row["MYSTERY_SHOPPER_DETALLE_ID"];
                $mystery_shopper["TAREA_DET_ID"]                =$row["TAREA_DET_ID"];
                $mystery_shopper["ESTATUS"]                     =$row["ESTATUS"];
                $mystery_shopper["GRUPO_TAREA"]                 =$row["GRUPO_TAREA"];
                $mystery_shopper["DESCRIPCION"]                 =$row["DESCRIPCION"];
                $mystery_shopper["CLAVE"]                       =$row["CLAVE"];
                $mystery_shopper["RESPUESTA_TAREA_DETALLE_ID"]  =$row["RESPUESTA_TAREA_DETALLE_ID"];
                $mystery_shopper["TIPO_DATO"]                   =$row["TIPO_DATO"];
                $mystery_shopper["RESULTADO"]                   =$row["RESULTADO"];
                $mystery_shopper["IMAGEN"]                      =$row["IMAGEN"];
                $mystery_shopper["TIPO_TAREA"]                  =$row["TIPO_TAREA"];
                $mystery_shopper["REQUIERE_IMAGEN"]             =$row["REQUIERE_IMAGEN"];
                $mystery_shopper["TIPO_DATO_TAREA"]             =$row["TIPO_DATO_TAREA"];
                $mystery_shopper["NA"]                          =$row["NA"];
                $mystery_shopper["LIBRE"]                       =$row["LIBRE"];
                $result[]=$mystery_shopper;                            
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
        return null; 
    }
}
/*function SubirImgMysteryShopper($FECHA,$IMAGEN,$NOMBRE_IMAGEN,$MYSTERY_SHOPPER_ID,$RESPUESTA_TAREA_DETALLE_ID){
    $result = -1;
    $hostname="../../../";
    $CLAVE_CLIENTE="";
    $CLAVE_SUCURSAL="";
    $actualPath="";
    $MODULO="MYSTERY_SHOPPER";
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

    if(!stristr($NOMBRE_IMAGEN ,'/', true)){
        if ($conn){
            $select  = " SELECT C.CLAVE AS CLAVE_CLIENTE ,S.CLAVE AS CLAVE_SUCURSAL ";
            $select .= " FROM MYSTERY_SHOPPER AS MS ";
            $select .= " INNER JOIN SUCURSALES AS S ON MS.SUCURSAL_ID=S.SUCURSAL_ID ";
            $select .= " INNER JOIN CLIENTES AS C ON S.CLIENTE_ID=C.CLIENTE_ID ";
            $select .= " WHERE MYSTERY_SHOPPER_ID =$MYSTERY_SHOPPER_ID;";
            $stmt = mysqli_query($conn, $select);
            //Echo $select;
            if ($stmt){
                while ($row = mysqli_fetch_assoc($stmt)){
                    $CLAVE_CLIENTE=$row["CLAVE_CLIENTE"];
                    $CLAVE_SUCURSAL=$row["CLAVE_SUCURSAL"];
                }
                if(strlen($CLAVE_CLIENTE)>0&&strlen($CLAVE_SUCURSAL)>0){
                    $path = "$MODULO/$CLAVE_CLIENTE/$CLAVE_SUCURSAL/$FECHA";
                    //Crear la ruta de la carpeta
                    $actualPath = $hostname."Evidencia/$path";
                    
                    if (!file_exists($actualPath)) {
                        mkdir($actualPath, 0777, true);
                    }
                    if (file_exists($actualPath)) {
                        $actualPath .= "/$NOMBRE_IMAGEN";
                        if(file_put_contents($actualPath, base64_decode($IMAGEN))){
                            //$conn = ABRIR_CONEXION_MYSQL(FALSE);
                            if (file_exists($actualPath)) {
                                //insertar imagen en la Tabla de Producto y prrecio
                                $actualPath="/Evidencia/$path/$NOMBRE_IMAGEN";

                                $insert="INSERT INTO REPOSITORIO_IMAGEN(IMAGEN,ESTATUS,TIPO_TAREA,RESPUESTA_TAREA_DETALLE_ID)
                                VALUES('$actualPath','A','$MODULO',$RESPUESTA_TAREA_DETALLE_ID);";
                                $stmt = mysqli_query($conn, $insert);
                                if ($stmt){
                                    $result = 1;
                                    mysqli_commit($conn);		
                                }
                                else{
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
    }else {
        $result=false;
    }
    return $result;  
}*/
function SubirImgMysteryShopper($FECHA, $IMAGEN, $NOMBRE_IMAGEN, $MYSTERY_SHOPPER_ID, $RESPUESTA_TAREA_DETALLE_ID)
{
    $result = -1;
    $hostname = "../../../";
    $CLAVE_CLIENTE = "";
    $CLAVE_SUCURSAL = "";
    $actualPath = "";
    $MODULO = "MYSTERY_SHOPPER";
    $RUTA_IMAGEN = "";
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

    if (!stristr($NOMBRE_IMAGEN, '/', true)) {
        if ($conn) {
            $select  = " SELECT C.CLAVE AS CLAVE_CLIENTE ,S.CLAVE AS CLAVE_SUCURSAL ";
            $select .= " FROM MYSTERY_SHOPPER AS MS ";
            $select .= " INNER JOIN SUCURSALES AS S ON MS.SUCURSAL_ID=S.SUCURSAL_ID ";
            $select .= " INNER JOIN CLIENTES AS C ON S.CLIENTE_ID=C.CLIENTE_ID ";
            $select .= " WHERE MYSTERY_SHOPPER_ID =$MYSTERY_SHOPPER_ID;";
            $stmt = mysqli_query($conn, $select);
            //Echo $select;
            if ($stmt) {
                while ($row = mysqli_fetch_assoc($stmt)) {
                    $CLAVE_CLIENTE = $row["CLAVE_CLIENTE"];
                    $CLAVE_SUCURSAL = $row["CLAVE_SUCURSAL"];
                }
                $select  = " SELECT cast(IMAGEN AS CHAR(1000)) RUTA_IMAGEN FROM REPOSITORIO_IMAGEN WHERE RESPUESTA_TAREA_DETALLE_ID=$RESPUESTA_TAREA_DETALLE_ID ";
                $stmt = mysqli_query($conn, $select);
                if ($stmt) {
                    while ($row = mysqli_fetch_assoc($stmt)) {
                        $RUTA_IMAGEN = $row["RUTA_IMAGEN"];
                    }
                    //Preguntar si RESPUESTA_TAREA_DETALLE_ID ya tiene una imágen en la tabla REPOSITORIO_IMAGEN
                    //echo $RUTA_IMAGEN;
                    if (strlen($RUTA_IMAGEN) > 0) {
                        //Sobreescribir la foto que esta en la ruta guardada en la tabla REPOSITORIO_IMAGEN
                        $nombreImagen="";
                        $cont=0;
                        $path = "$MODULO/$CLAVE_CLIENTE/$CLAVE_SUCURSAL/$FECHA";
                        for( $i=strlen($RUTA_IMAGEN)-1;$i >0; $i--) {
                            if($RUTA_IMAGEN[$i]=='/'){
                                break;
                            }
                                $cont++;
                            
                        }
                        $nombreImagen=substr ($RUTA_IMAGEN, -$cont); 
                        
                        $actualPath = $hostname . "Evidencia/$path/$nombreImagen";
                        //echo " Actual path: ".$actualPath;
                        try{
                            if(file_exists($actualPath)){
                                //echo " \n\r Archivo Existe ";
                                if(unlink($actualPath))//Elimina la imagen anterior
                                {
                                    //echo " Archivo eliminado ";
                                }//Sube la nueva imagen con el nombre anterior
                                if (file_put_contents($actualPath, base64_decode($IMAGEN))){
                                    if (file_exists($actualPath)) {
                                        $result = 1;
                                        //echo $result;
                                        //mysqli_commit($conn);
                                    } else {
                                        $result = 0;
                                        //echo $result;
                                        //mysqli_rollback($conn);
                                        
                                    }
                                    
                                }
                                else{
                                    //echo " \n\r No se pudo eliminar el archivo".$actualPath;
                                    
                                }
                            }else{
                                //echo " \n\r El archivo no existe ".$actualPath;
                            }
                            
                        }catch(Exception $e) {
                            //echo 'Excepción capturada: '.  $e->getMessage(). "\n".$actualPath;
                            
                        }
                        
                    } else { //Sino insertar la imagen normalmente
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
                                        $insert = "INSERT INTO REPOSITORIO_IMAGEN(IMAGEN,ESTATUS,TIPO_TAREA,RESPUESTA_TAREA_DETALLE_ID)
                                        VALUES('$actualPath','A','$MODULO',$RESPUESTA_TAREA_DETALLE_ID);";
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
                }
                mysqli_close($conn);
            }
        } else {
            $result = false;
        }
        return $result;
    }
}
function InsertarMysteryShopperRespuestaTarea($TAREA_DET_ID,$SUCURSAL_ID,$MYSTERY_SHOPPER_DETALLE_ID,$TIPO_DATO,$RESULTADO,$USUARIO_CREADOR,$FECHA_HORA_CREACION){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = 0;
    $respuesta=0;
    $RESPUESTA_TAREA_DETALLE_ID=0;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);  

    //Buscar si la respuesta a esa tarea existe, en caso de que no exista insertar
    $select  = "SELECT ifnull(RESPUESTA_TAREA_DETALLE_ID,0) AS RESPUESTA_TAREA_DETALLE_ID FROM RESPUESTA_TAREA_DETALLE WHERE ";
    $select .= " (TAREA_DET_ID=$TAREA_DET_ID AND SUCURSAL_ID=$SUCURSAL_ID AND MYSTERY_SHOPPER_DETALLE_ID=$MYSTERY_SHOPPER_DETALLE_ID ";
    $select .= " /*AND TIPO_DATO='$TIPO_DATO' AND RESULTADO='$RESULTADO' AND USUARIO_CREADOR='$USUARIO_CREADOR' AND FECHA_HORA_CREACION='$FECHA_HORA_CREACION'*/);";
    $stmt = mysqli_query($conn, $select); 
    //echo $select."  ";
    if ($stmt){
        while ($row = mysqli_fetch_assoc($stmt)){
            $RESPUESTA_TAREA_DETALLE_ID=$row["RESPUESTA_TAREA_DETALLE_ID"]; 
        }
    }
    //echo $RESPUESTA_TAREA_DETALLE_ID."; ";
    if($RESPUESTA_TAREA_DETALLE_ID===0){
        $query   ="INSERT INTO RESPUESTA_TAREA_DETALLE (TAREA_DET_ID,SUCURSAL_ID,MYSTERY_SHOPPER_DETALLE_ID,TIPO_DATO,RESULTADO,USUARIO_CREADOR,FECHA_HORA_CREACION) ";
        $query  .=" VALUES ($TAREA_DET_ID,$SUCURSAL_ID,$MYSTERY_SHOPPER_DETALLE_ID,'$TIPO_DATO','$RESULTADO','$USUARIO_CREADOR','$FECHA_HORA_CREACION');";
        //echo $query."  ";  
        if (mysqli_query($conn, $query)){
            $respuesta = 1;
        }
        else{
            $respuesta = 0;
        }
        if($respuesta===1){
            //Actualizar Estatus del detalle de la tarea de mystery_shopper
            $query  =" UPDATE MYSTERY_SHOPPER_DETALLE";
            $query .=" SET";
            $query .=" ESTATUS = 'T'";
            $query .=" WHERE MYSTERY_SHOPPER_DETALLE_ID = $MYSTERY_SHOPPER_DETALLE_ID;";
            //echo $query."  ";
            if (mysqli_query($conn, $query)){
                $select  = "SELECT ifnull(RESPUESTA_TAREA_DETALLE_ID,0) AS RESPUESTA_TAREA_DETALLE_ID FROM RESPUESTA_TAREA_DETALLE WHERE ";
                $select .= " (TAREA_DET_ID=$TAREA_DET_ID AND SUCURSAL_ID=$SUCURSAL_ID AND MYSTERY_SHOPPER_DETALLE_ID=$MYSTERY_SHOPPER_DETALLE_ID ";
                $select .= " AND TIPO_DATO='$TIPO_DATO' AND RESULTADO='$RESULTADO' AND USUARIO_CREADOR='$USUARIO_CREADOR' AND FECHA_HORA_CREACION='$FECHA_HORA_CREACION');";
                $stmt = mysqli_query($conn, $select);
                //echo $select."  ";
                if ($stmt){
                    while ($row = mysqli_fetch_assoc($stmt)){
                        $respuesta=$row["RESPUESTA_TAREA_DETALLE_ID"]; 
                    }
                }
                //echo $respuesta."; ";
            }
            else{
                $respuesta = 0;
            }
            //mysqli_close($conn);
        }
        if ($respuesta>0){
            //echo "exito";
            //$result = true;
            if( mysqli_commit($conn)){
                $result = $respuesta;
            }else
            {
                $result = 0;
            }                        
                //$result = $respuesta;
        }
        else{
            //echo "Error";
            mysqli_rollback($conn);
            // $result = false;
        }
        mysqli_close($conn);
    }
    else {
        //Actualizar RESPUESTA_TAREA_DETALLE
        $query  ="UPDATE RESPUESTA_TAREA_DETALLE ";
        $query .=" SET ";
        $query .=" RESULTADO = '$RESULTADO', ";
        $query .=" TIPO_DATO='$TIPO_DATO',";
        $query .=" USUARIO_MODIFICACION = '$USUARIO_CREADOR', ";
        $query .=" FECHA_HORA_MODIFICACION = '$FECHA_HORA_CREACION' ";
        $query .=" WHERE RESPUESTA_TAREA_DETALLE_ID = $RESPUESTA_TAREA_DETALLE_ID; ";
        //echo $query;
        if (mysqli_query($conn, $query)){
           // $result = true;
            mysqli_commit($conn);
        }
        else{
            mysqli_rollback($conn);
            //$result = false;
        }
        mysqli_close($conn);

        $result=$RESPUESTA_TAREA_DETALLE_ID;
    }
}
    else {
        // FALLO LA CONEXION
        $respuesta = 0;
    }
return $result;
}
function ActualizarEstatusMysteryShopper($MYSTERY_SHOPPER_ID,$ESTATUS,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        $query  =" UPDATE MYSTERY_SHOPPER";
        $query .=" SET";
        $query .=" ESTATUS = '$ESTATUS',";
        $query .=" USUARIO_MODIFICACION='$USUARIO_MODIFICACION',";
        $query .=" FECHA_HORA_MODIFICACION='$FECHA_HORA_MODIFICACION'";
        $query .=" WHERE MYSTERY_SHOPPER_ID = $MYSTERY_SHOPPER_ID;";
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

function BuscarMysteryShopperRespuestaTarea($TAREA_DET_ID,$SUCURSAL_ID,$MYSTERY_SHOPPER_DETALLE_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = 0;
    if ($conn){
        if ($conn){

            $select  = "SELECT RESPUESTA_TAREA_DETALLE_ID FROM RESPUESTA_TAREA_DETALLE ";
            $select .= " WHERE ";
            $select .= " TAREA_DET_ID=$TAREA_DET_ID ";
            $select .= " AND SUCURSAL_ID=$SUCURSAL_ID ";
            $select .= " AND MYSTERY_SHOPPER_DETALLE_ID=$MYSTERY_SHOPPER_DETALLE_ID ";
            $stmt = mysqli_query($conn, $select);
            //Echo $select;
            if ($stmt){
                while ($row = mysqli_fetch_assoc($stmt)){
                    $result=$row["RESPUESTA_TAREA_DETALLE_ID"];
                }
            }
        }
        mysqli_close($conn);
    }
    else {
        $result = 0;
    }
return $result;
}
function ActualizarHoraInicioFinMysteryShopper($TIPO,$HORA,$MYSTERY_SHOPPER_ID,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    $TIPO_ACTUALIZACION="";$WHERE_COMPLEMENTO="";
    if(is_null($HORA)||strlen($HORA)===0){

    }
    else{
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE); 
            if ($TIPO==="I") {
                $TIPO_ACTUALIZACION="HORA_INICIO ='$HORA',"; 
                $WHERE_COMPLEMENTO=" AND HORA_INICIO IS NULL";
            }
            elseif($TIPO==="F") {
                $TIPO_ACTUALIZACION="HORA_FIN = '$HORA',";
                $WHERE_COMPLEMENTO=" AND HORA_FIN IS NULL";
            }
            $query  = "UPDATE MYSTERY_SHOPPER ";
            $query .= " SET ";
            $query .= " $TIPO_ACTUALIZACION ";
            $query .= " USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
            $query .= " FECHA_HORA_MODIFICACION ='$FECHA_HORA_MODIFICACION' ";
            $query .= " WHERE MYSTERY_SHOPPER_ID = $MYSTERY_SHOPPER_ID $WHERE_COMPLEMENTO;";
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

function MuestraHoraInicioFinMysteryShopper($MYSTERY_SHOPPER_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    if ($conn){
        $select  ="SELECT HORA_INICIO,HORA_FIN FROM MYSTERY_SHOPPER ";
        $select .=" WHERE MYSTERY_SHOPPER_ID = $MYSTERY_SHOPPER_ID;";
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
    'MostrarMysteryShopper',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'MYSTERY_SHOPPER_ID' => array('name'=>'MYSTERY_SHOPPER_ID','type'=>'xsd:int'),
        'MYSTERY_SHOPPER_DETALLE_ID' => array('name'=>'MYSTERY_SHOPPER_DETALLE_ID','type'=>'xsd:int'),
        'TAREA_DET_ID' => array('name'=>'TAREA_DET_ID','type'=>'xsd:int'),
        'ESTATUS' => array('name'=>'ESTATUS','type'=>'xsd:string'),
        'GRUPO_TAREA' => array('name'=>'GRUPO_TAREA','type'=>'xsd:string'),
        'DESCRIPCION' => array('name'=>'DESCRIPCION','type'=>'xsd:string'),
        'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string'),
        'RESPUESTA_TAREA_DETALLE_ID' => array('name'=>'RESPUESTA_TAREA_DETALLE_ID','type'=>'xsd:string'),
        'TIPO_DATO' => array('name'=>'TIPO_DATO','type'=>'xsd:string'),
        'RESULTADO' => array('name'=>'RESULTADO','type'=>'xsd:string'),
        'IMAGEN' => array('name'=>'IMAGEN','type'=>'xsd:string'),
        'TIPO_TAREA' => array('name'=>'TIPO_TAREA','type'=>'xsd:string'),
        'REQUIERE_IMAGEN' => array('name'=>'REQUIERE_IMAGEN','type'=>'xsd:string'),
        'TIPO_DATO_TAREA' => array('name'=>'TIPO_DATO_TAREA','type'=>'xsd:string'),
        'NA' => array('name'=>'NA','type'=>'xsd:string'),
        'LIBRE' => array('name'=>'LIBRE','type'=>'xsd:string')
    )
);    
$server->wsdl->addComplexType(
    'MostrarMysteryShopperArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarMysteryShopper[]')),
    'tns:MostrarMysteryShopper'
    );
$server->register(
    'MostrarMysteryShopperGrupoTarea',
    array(
        'SUCURSAL_ID'=>'xsd:int',
        'FECHA_REVISION'=>'xsd:string',
        'USUARIO_ASIGNADO'=>'xsd:string',
        'GRUPO_TAREA'=> 'xsd:string'
    ),
    array('return'=> 'tns:MostrarMysteryShopperArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con las datos del mystery shopper especificado');
    
$server->register(
    'MostrarMysteryShopper',
    array(
        'SUCURSAL_ID'=>'xsd:int',
        'FECHA_REVISION'=>'xsd:string',
        'USUARIO_ASIGNADO'=>'xsd:string'
    ),
    array('return'=> 'tns:MostrarMysteryShopperArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con las datos del mystery shopper especificado');

$server->register(
    'SubirImgMysteryShopper',
    array(
        'FECHA'=>'xsd:string',
        'IMAGEN'=>'xsd:string',
        'NOMBRE_IMAGEN'=>'xsd:string',
        'MYSTERY_SHOPPER_ID'=>'xsd:int',
        'RESPUESTA_TAREA_DETALLE_ID'=>'xsd:int'
    ),
    array('return'=>'xsd:int'),
    $namespace,
    false,
    'rpc',
    false,
    'Servicio para subir imagen al servidor');

$server->register(
    'InsertarMysteryShopperRespuestaTarea',
    array(
        'TAREA_DET_ID'=>'xsd:int',
        'SUCURSAL_ID'=>'xsd:int',
        'MYSTERY_SHOPPER_DETALLE_ID'=>'xsd:int',
        'TIPO_DATO'=>'xsd:string',
        'RESULTADO'=>'xsd:string',
        'USUARIO_CREADOR'=>'xsd:string',
        'FECHA_HORA_CREACION'=>'xsd:string'
        ),
    array('return'=>'xsd:int'),
    $namespace,
    false,
    'rpc',
    false,
    'Inserta un registro en la respuesta de una tarea en mystery shopper ');

$server->register(
    'BuscarMysteryShopperRespuestaTarea',
    array(
        'TAREA_DET_ID'=>'xsd:int',
        'SUCURSAL_ID'=>'xsd:int',
        'MYSTERY_SHOPPER_DETALLE_ID'=>'xsd:int'
    ),
    array('return'=>'xsd:int'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve el valor del id de la respuesta de la tarea mystery shopper especificada');
    
$server->register(
    'ActualizarEstatusMysteryShopper',
    array(
        'MYSTERY_SHOPPER_ID'=>'xsd:int',
        'ESTATUS'=>'xsd:string',
        'USUARIO_MODIFICACION'=>'xsd:string',
        'FECHA_HORA_MODIFICACION'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza el estatus en mystery shopper ');
        
$server->register(
    'ActualizarHoraInicioFinMysteryShopper',
    array(
        'TIPO'=>'xsd:string',
        'HORA'=>'xsd:string',
        'MYSTERY_SHOPPER_ID'=>'xsd:int',
        'USUARIO_MODIFICACION'=>'xsd:string',
        'FECHA_HORA_MODIFICACION'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza la hora de inicio y la hora de fin de mystery shopper');
$server->wsdl->addComplexType(
    'MuestraHoraInicioFinMysteryShopper',
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
    'MuestraHoraInicioFinMysteryShopperArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MuestraHoraInicioFinMysteryShopper[]')),
    'tns:MuestraHoraInicioFinMysteryShopper'
    );
$server->register(
    'MuestraHoraInicioFinMysteryShopper',
    array(
        'MYSTERY_SHOPPER_ID'=>'xsd:int'
    ),
    array('return'=> 'tns:MuestraHoraInicioFinMysteryShopperArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Muestra la hora de inicio y fin de la tarea de mystery shopper');
    
    function ActualizarMysteryShopperDetalleEstatus($MYSTERY_SHOPPER_DETALLE_ID)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = 0;
    $respuesta = 0;
    $RESPUESTA_TAREA_DETALLE_ID = 0;
    if ($conn) {
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

        //Actualizar Estatus del detalle de la tarea de mystery_shopper
        $query  = " UPDATE MYSTERY_SHOPPER_DETALLE";
        $query .= " SET";
        $query .= " ESTATUS = 'T'";
        $query .= " WHERE MYSTERY_SHOPPER_DETALLE_ID = $MYSTERY_SHOPPER_DETALLE_ID AND ESTATUS = 'P';";

        if (mysqli_query($conn, $query)) {
            $result = 1;
        } else {
            $result = 0;
        }

        if ($result) {
            mysqli_commit($conn);
        } else {
            mysqli_rollback($conn);
        }
        mysqli_close($conn);
    }
    return $result;
}

function ActualizaEstatusTareasMysteryShopper($MYSTERY_SHOPPER_DETALLE_ID)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    $id = "";
    if ($conn) {
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        $select  = " SELECT MYSTERY_SHOPPER_DETALLE_ID FROM MYSTERY_SHOPPER AS MS ";
        $select .= " INNER JOIN MYSTERY_SHOPPER_DETALLE AS MSD ON MS.MYSTERY_SHOPPER_ID=MSD.MYSTERY_SHOPPER_ID ";
        $select .= " INNER JOIN TAREA_DETALLE AS TD ON MSD.TAREA_DET_ID=TD.TAREA_DET_ID ";
        $select .= " WHERE MSD.ESTATUS='P'AND MS.MYSTERY_SHOPPER_ID = ";
        $select .= " (SELECT MSD1. MYSTERY_SHOPPER_ID FROM MYSTERY_SHOPPER_DETALLE AS MSD1 WHERE MYSTERY_SHOPPER_DETALLE_ID=$MYSTERY_SHOPPER_DETALLE_ID) ";
        $select .= " AND TD.TAREA_ID= ";
        $select .= " (SELECT TD1.TAREA_ID FROM MYSTERY_SHOPPER_DETALLE AS MSD2 ";
        $select .= " INNER JOIN TAREA_DETALLE AS TD1 ON MSD2.TAREA_DET_ID=TD1.TAREA_DET_ID ";
        $select .= " WHERE MSD2.MYSTERY_SHOPPER_DETALLE_ID=$MYSTERY_SHOPPER_DETALLE_ID)";
        $stmt = mysqli_query($conn, $select);
        //echo $select;
        if ($stmt) {
            while ($row = mysqli_fetch_assoc($stmt)) {
                $id .= $row["MYSTERY_SHOPPER_DETALLE_ID"] . ",";
            }
            $id = trim($id, ',');
            //echo $id;
            
            $query  = " UPDATE MYSTERY_SHOPPER_DETALLE";
            $query .= " SET";
            $query .= " ESTATUS = 'T'";
            $query .= " WHERE MYSTERY_SHOPPER_DETALLE_ID in( $id);";

//echo $query;
            if (mysqli_query($conn, $query)) {
                $result = true;
                mysqli_commit($conn);
            } else {
                mysqli_rollback($conn);
                $result = false;
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
$server->register(
    'ActualizarMysteryShopperDetalleEstatus',
    array(
        'MYSTERY_SHOPPER_DETALLE_ID' => 'xsd:int'
    ),
    array('return' => 'xsd:int'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza el estatus del detalle id en mystery shopper detalle '
);
$server->register(
    'ActualizaEstatusTareasMysteryShopper',
    array(
        'MYSTERY_SHOPPER_DETALLE_ID' => 'xsd:int'
    ),
    array('return' => 'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza el estatus de varias tareas de mystery shopper '
);