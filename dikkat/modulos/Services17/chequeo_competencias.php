<?php
function MostrarChequeoCompetencia($FECHA_REVISION,$USUARIO_ASIGNADO,$SUCURSAL_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    if ($conn){ 
        $USUARIO_ASIGNADO=trim($USUARIO_ASIGNADO);
        $select  = "SELECT CC.CHEQUEO_COMPETENCIA_ID,C.NOMBRE AS CLIENTE_SOLICITANTE,CC.FOLIO,CC.FECHA_REVISION, CCD.CHEQUEO_COMPETENCIA_DETALLE_ID, CCD.SUCURSALES_CHEQUEO_COMPETENCIA_ID ";
        $select .= " ,SCC.SUCURSAL_ID, PA.PERSONAL_ANAQUEL_ID, CCD.ASIGNACION_ID,A.FOLIO AS PROD_PREC_FOLIO,A.FECHA AS PROD_PREC_FECHA_REV,A.ESTATUS AS PROD_PREC_ESTATUS";
        //$select .= " , IFNULL(CCD.ATENCION_CLIENTES,0) AS ATEN_CLIEN, IFNULL(CCD.CANTIDAD_CLIENTES,0) AS CANT_CLIEN, IFNULL(CCD.CANTIDAD_ACTIVACIONES,0) AS CANT_ACTIV,IFNULL( CMC.COMPETENCIA_CAJAS_ID,0)  AS COMP_CAJA ";
        $select .= " FROM CHEQUEO_COMPETENCIA AS CC ";
        $select .= " INNER JOIN CHEQUEO_COMPETENCIA_DETALLE AS CCD ON CC.CHEQUEO_COMPETENCIA_ID=CCD.CHEQUEO_COMPETENCIA_ID ";
        $select .= " INNER JOIN CLIENTES AS C ON CC.CLIENTE_SOLICITO_ID=C.CLIENTE_ID ";
        $select .= " INNER JOIN SUCURSALES_CHEQUEO_COMPETENCIA AS SCC ON CCD.SUCURSALES_CHEQUEO_COMPETENCIA_ID=SCC.SUCURSALES_CHEQUEO_COMPETENCIA_ID ";
        $select .= " LEFT JOIN ASIGNACIONES AS A ON A.ASIGNACION_ID=CCD.ASIGNACION_ID ";
        $select .= " LEFT JOIN PERSONAL_ANAQUEL as PA ON PA.CHEQUEO_COMPETENCIA_DETALLE_ID=CCD.CHEQUEO_COMPETENCIA_DETALLE_ID ";
        $select .= " LEFT JOIN COMPETENCIA_CAJAS AS CMC ON CCD.CHEQUEO_COMPETENCIA_DETALLE_ID=CMC.CHEQUEO_COMPETENCIA_DETALLE_ID ";
        $select .= " WHERE CC.FECHA_REVISION='$FECHA_REVISION' AND CC.ESTATUS='A' AND CC.USUARIO_ASIGNADO='$USUARIO_ASIGNADO' AND SCC.SUCURSAL_ID=$SUCURSAL_ID ;";
    
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $chequeo["CHEQUEO_COMPETENCIA_ID"]          =$row["CHEQUEO_COMPETENCIA_ID"];
                $chequeo["CLIENTE_SOLICITANTE"]             =$row["CLIENTE_SOLICITANTE"];
                $chequeo["FOLIO"]                           =$row["FOLIO"];
                $chequeo["FECHA_REVISION"]                  =$row["FECHA_REVISION"];
                $chequeo["CHEQUEO_COMPETENCIA_DETALLE_ID"]  =$row["CHEQUEO_COMPETENCIA_DETALLE_ID"];
                $chequeo["SUCURSAL_ID"]                     =$row["SUCURSAL_ID"];
                $chequeo["PERSONAL_ANAQUEL_ID"]             =$row["PERSONAL_ANAQUEL_ID"];
                $chequeo["ASIGNACION_ID"]                   =$row["ASIGNACION_ID"];
                $chequeo["PROD_PREC_FOLIO"]                 =$row["PROD_PREC_FOLIO"];
                $chequeo["PROD_PREC_FECHA_REV"]             =$row["PROD_PREC_FECHA_REV"];
                $chequeo["PROD_PREC_ESTATUS"]               =$row["PROD_PREC_ESTATUS"];
                /*$chequeo["ATEN_CLIEN"]                      =$row["ATEN_CLIEN"];
                $chequeo["CANT_CLIEN"]                      =$row["CANT_CLIEN"];
                $chequeo["CANT_ACTIV"]                      =$row["CANT_ACTIV"];
                $chequeo["COMP_CAJA"]                       =$row["COMP_CAJA"];*/
                $result[]=$chequeo;                            
            } 
            
        mysqli_close($conn);
        //print_r($result);
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

function ActualizarChequeoCompetenciaDetalle( $CHEQUEO_COMPETENCIA_ID
,$CANTIDAD_CLIENTES,$CHEQUEO_COMPETENCIA_DETALLE_ID,$SUCURSAL_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn){ 
        //Buscar sucursal chequeo competencia id
        $select  = "SELECT SUCURSALES_CHEQUEO_COMPETENCIA_ID FROM SUCURSALES_CHEQUEO_COMPETENCIA ";
        $select .= "WHERE SUCURSAL_ID=$SUCURSAL_ID AND CHEQUEO_COMPETENCIA_ID=$CHEQUEO_COMPETENCIA_ID";
        //echo $select;
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $sucursalCheqComp=$row["SUCURSALES_CHEQUEO_COMPETENCIA_ID"];
            }
        //si lo encuentra actualiza
        //echo " - ".$sucursalCheqComp." - ";
        if(strlen($sucursalCheqComp)>0 && $sucursalCheqComp>0)
        {
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
            $query  = "UPDATE CHEQUEO_COMPETENCIA_DETALLE "; 
            $query .= " SET "; 
            $query .= " CHEQUEO_COMPETENCIA_ID = $CHEQUEO_COMPETENCIA_ID, "; 
            $query .= " SUCURSALES_CHEQUEO_COMPETENCIA_ID = $sucursalCheqComp, ";
            $query .= " CANTIDAD_CLIENTES = $CANTIDAD_CLIENTES "; 
            $query .= " WHERE CHEQUEO_COMPETENCIA_DETALLE_ID = $CHEQUEO_COMPETENCIA_DETALLE_ID;";  
            //echo $query;
            if (mysqli_query($conn, $query)){
                $result = true;
                
                mysqli_commit($conn);
            }
            else{
                $result = false;
                
                mysqli_rollback($conn);
            }
                mysqli_close($conn);
                //si no lo encuentra retorna falso
            }
            elseif(is_null($sucursalCheqComp)) {
                
            }
        }
        else {
            // FALLO LA CONEXION
            $result = false;
        }
    }
        return $result;
}
function ActualizarEstatusChequeoCompetencia( $CHEQUEO_COMPETENCIA_ID,$ESTATUS,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn){ 
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
            $query  = "UPDATE CHEQUEO_COMPETENCIA ";
            $query .= " SET ";
            $query .= " ESTATUS = '$ESTATUS', ";
            $query .= " USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
            $query .= " FECHA_HORA_MODIFICACION = '$FECHA_HORA_MODIFICACION' ";
            $query .= " WHERE CHEQUEO_COMPETENCIA_ID = $CHEQUEO_COMPETENCIA_ID;";  
            
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
    else {
        // FALLO LA CONEXION
        $result = false;
    }
    return $result;
}
function InsertarCompetenciaCajas($COMPETENCIA_CAJAS_CANTIDAD,$COMPETENCIA_CAJAS_ABIERTAS,$COMPETENCIA_CAJAS_CERRADAS
,$COMPETENCIA_CAJAS_MAS4CLIENTES,$CHEQUEO_COMPETENCIA_DETALLE_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);               
                  $query   ="INSERT INTO COMPETENCIA_CAJAS(COMPETENCIA_CAJAS_CANTIDAD,COMPETENCIA_CAJAS_ABIERTAS,COMPETENCIA_CAJAS_CERRADAS ";
                  $query  .=" ,COMPETENCIA_CAJAS_MAS4CLIENTES,CHEQUEO_COMPETENCIA_DETALLE_ID) ";
                  $query  .=" VALUES($COMPETENCIA_CAJAS_CANTIDAD,$COMPETENCIA_CAJAS_ABIERTAS,$COMPETENCIA_CAJAS_CERRADAS ";
                  $query  .=" ,$COMPETENCIA_CAJAS_MAS4CLIENTES,$CHEQUEO_COMPETENCIA_DETALLE_ID);";
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
function BuscarCompetenciaCajas($CHEQUEO_COMPETENCIA_DETALLE_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = 0;
    if ($conn){
        $select  = "SELECT COMPETENCIA_CAJAS_ID FROM COMPETENCIA_CAJAS ";
        $select.=" WHERE CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID;";
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $competencia=$row["COMPETENCIA_CAJAS_ID"];
                $result=$competencia; 
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
function ActualizaCompetenciaCajas($COMPETENCIA_CAJAS_CANTIDAD,$COMPETENCIA_CAJAS_ABIERTAS,$COMPETENCIA_CAJAS_CERRADAS
,$COMPETENCIA_CAJAS_MAS4CLIENTES,$CHEQUEO_COMPETENCIA_DETALLE_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        $query  = "UPDATE COMPETENCIA_CAJAS ";
        $query .= " SET ";
        $query .= " COMPETENCIA_CAJAS_CANTIDAD = $COMPETENCIA_CAJAS_CANTIDAD, ";
        $query .= " COMPETENCIA_CAJAS_ABIERTAS = $COMPETENCIA_CAJAS_ABIERTAS, ";
        $query .= " COMPETENCIA_CAJAS_CERRADAS = $COMPETENCIA_CAJAS_CERRADAS, ";
        $query .= " COMPETENCIA_CAJAS_MAS4CLIENTES = $COMPETENCIA_CAJAS_MAS4CLIENTES ";
        $query .= " WHERE CHEQUEO_COMPETENCIA_DETALLE_ID = $CHEQUEO_COMPETENCIA_DETALLE_ID;";
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
function InsertarPersonalAnaquel($SUCURSAL_ID,$CHEQUEO_COMPETENCIA_DETALLE_ID,$FECHA_REVISION,$USUARIO_CREADOR,$FECHA_HORA_CREACION, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE); 
        $query   ="INSERT INTO PERSONAL_ANAQUEL(SUCURSAL_ID,CHEQUEO_COMPETENCIA_DETALLE_ID,ESTATUS,FECHA_REVISION,USUARIO_CREADOR,FECHA_HORA_CREACION) ";
        $query .= " VALUES($SUCURSAL_ID,$CHEQUEO_COMPETENCIA_DETALLE_ID,'A','$FECHA_REVISION','$USUARIO_CREADOR','$FECHA_HORA_CREACION');";
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
function BuscarPersonalAnaquel($SUCURSAL_ID,$FECHA_REVISION,$CHEQUEO_COMPETENCIA_DETALLE_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = 0;
    if ($conn){
        $select  = "SELECT PERSONAL_ANAQUEL_ID FROM PERSONAL_ANAQUEL WHERE ";
        $select .= "SUCURSAL_ID=$SUCURSAL_ID AND ESTATUS='A' AND FECHA_REVISION='$FECHA_REVISION' AND CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID;";
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $personal=$row["PERSONAL_ANAQUEL_ID"];
                $result=$personal; 
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
function BuscarPersonalAnaquelDetalle($SUCURSAL_ID,$FECHA_REVISION,$CHEQUEO_COMPETENCIA_DETALLE_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = NULL;
    if ($conn){
        $select  = "SELECT PA.PERSONAL_ANAQUEL_ID,PAD.PERSONAL_ANAQUEL_DETALLE_ID, PAD.PROVEEROR_ID,P.NOMBRE, PAD.CANTIDAD FROM PERSONAL_ANAQUEL AS PA "; 
        $select .= " INNER JOIN PERSONAL_ANAQUEL_DETALLE AS PAD ON PA.PERSONAL_ANAQUEL_ID=PAD.PERSONAL_ANAQUEL_ID ";
        $select .= " LEFT JOIN PROVEEDORES AS P ON PAD.PROVEEROR_ID=P.PROVEEDOR_ID ";
        $select .= " WHERE SUCURSAL_ID=$SUCURSAL_ID AND PA.ESTATUS='A' AND FECHA_REVISION='$FECHA_REVISION' AND CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID";
        $select .= " ORDER BY PAD.PERSONAL_ANAQUEL_DETALLE_ID ASC;";
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $personal["PERSONAL_ANAQUEL_ID"]        =$row["PERSONAL_ANAQUEL_ID"];
                $personal["PERSONAL_ANAQUEL_DETALLE_ID"]=$row["PERSONAL_ANAQUEL_DETALLE_ID"];
                $personal["PROVEEROR_ID"]               =$row["PROVEEROR_ID"];
                $personal["NOMBRE"]                     =$row["NOMBRE"];
                $personal["CANTIDAD"]                   =$row["CANTIDAD"];
                $result[]=$personal; 
            }
            mysqli_close($conn);
            return $result;
        }
        else{
            mysqli_close($conn);
            return NULL;
        }
        mysqli_close($conn);
    }
    else {
        // FALLO LA CONEXION
        return NULL; 
    }
}
function ActualizarEstatusPersonalAnaquel($SUCURSAL_ID,$CHEQUEO_COMPETENCIA_DETALLE_ID,$FECHA_REVISION,$ESTATUS,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        $query  = "UPDATE PERSONAL_ANAQUEL "; 
        $query .= " SET "; 
        $query .= " ESTATUS = '$ESTATUS', "; 
        $query .= " USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', "; 
        $query .= " FECHA_HORA_MODIFICACION = '$FECHA_HORA_MODIFICACION' "; 
        $query .= " WHERE "; 
        $query .= " SUCURSAL_ID = $SUCURSAL_ID "; 
        $query .= " AND FECHA_REVISION = '$FECHA_REVISION' AND CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID;";  
        
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
    else {
        // FALLO LA CONEXION
        $result = false;
    }
    return $result;
}
function InsertarPersonalAnaquelDetalle($PERSONAL_ANAQUEL_ID,$PROVEEROR_ID,$CANTIDAD, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE); 
        $prov="";
        if($PROVEEROR_ID===0){
            $prov="NULL";
        }else {
            $prov=$PROVEEROR_ID;
        }
        
        $query   ="INSERT INTO PERSONAL_ANAQUEL_DETALLE(PERSONAL_ANAQUEL_ID,PROVEEROR_ID,CANTIDAD)";
        $query  .="VALUES($PERSONAL_ANAQUEL_ID,$prov,$CANTIDAD);";
          
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
function ActualizaPersonalAnaquelDetalle($PERSONAL_ANAQUEL_DETALLE_ID,$PERSONAL_ANAQUEL_ID,$PROVEEROR_ID,$CANTIDAD, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn){
        $prov="";
        if($PROVEEROR_ID===0){
            $prov="NULL";
        }else {
            $prov=$PROVEEROR_ID;
        }
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);                 
        $query  = "UPDATE PERSONAL_ANAQUEL_DETALLE ";
        $query .= "SET ";
        $query .= "PERSONAL_ANAQUEL_ID = $PERSONAL_ANAQUEL_ID, ";
        $query .= "PROVEEROR_ID = $prov, ";
        $query .= "CANTIDAD = $CANTIDAD ";
        $query .= "WHERE PERSONAL_ANAQUEL_DETALLE_ID = $PERSONAL_ANAQUEL_DETALLE_ID;";
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
function SucursalesUsuarioChequeoCompetencia($USUARIO_ASIGNADO,$FECHA_REVISION, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result=null;
    if ($conn){ 
        $USUARIO_ASIGNADO=trim($USUARIO_ASIGNADO);
        $select  = "SELECT S.NOMBRE,S.LAT_LONG,S.GEO_CERCA,S.SUCURSAL_ID ";
        $select .= " FROM CHEQUEO_COMPETENCIA as CC ";
        $select .= " INNER JOIN SUCURSALES_CHEQUEO_COMPETENCIA AS SCC ON CC.CHEQUEO_COMPETENCIA_ID=SCC.CHEQUEO_COMPETENCIA_ID ";
        $select .= " INNER JOIN SUCURSALES AS S ON SCC.SUCURSAL_ID=S.SUCURSAL_ID ";
        $select .= " INNER JOIN USUARIOS AS U ON CC.USUARIO_ASIGNADO=U.USUARIO ";
        $select .= " WHERE ";
        $select .= " (FECHA_REVISION='$FECHA_REVISION')AND (CC.ESTATUS='A') AND (U.USUARIO='$USUARIO_ASIGNADO');";     
        
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $sucursales["NOMBRE"] = $row["NOMBRE"];
                $sucursales["LAT_LONG"]= $row["LAT_LONG"];
                $sucursales["GEO_CERCA"] = $row["GEO_CERCA"];
                $sucursales["SUCURSAL_ID"] = $row["SUCURSAL_ID"];
                $result[]=$sucursales;
            }     
            mysqli_close($conn);
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
    return $result;
}
function BuscarCatalogoActividadID($NOMBRE, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result="";
    if ($conn){ 
        $select = "SELECT CATALOGO_ACTIVIDADES_ID, NOMBRE, ESTATUS FROM CATALOGO_ACTIVIDADES WHERE NOMBRE='$NOMBRE' AND ESTATUS='A';";     
        
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $ACTIVIDAD = $row["CATALOGO_ACTIVIDADES_ID"];
                $result=$ACTIVIDAD;
            }     
            mysqli_close($conn);
        }
        else{
            mysqli_close($conn);
            return "";
        }
    }
    else {
        // FALLO LA CONEXION
        return "";
    }
    return $result;
}
function BuscarCatalogoChequeoID($NOMBRE, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result="";
    if ($conn){ 
        $select = "SELECT CATALOGO_CHEQUEO_ID, NOMBRE, ESTATUS FROM CATALOGO_CHEQUEO WHERE NOMBRE='$NOMBRE' AND ESTATUS='A';";     
        
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $chequeo = $row["CATALOGO_CHEQUEO_ID"];
                $result=$chequeo;
            }     
            mysqli_close($conn);
        }
        else{
            mysqli_close($conn);
            return "";
        }
    }
    else {
        // FALLO LA CONEXION
        return "";
    }
    return $result;
}
function ExisteActivacion($CHEQUEO_COMPETENCIA_DETALLE_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result=0;
    if ($conn){ 
        $select  = "SELECT ACTIVACION_ID, CHEQUEO_COMPETENCIA_DETALLE_ID, ESTATUS ";
        $select .= " FROM ACTIVACION WHERE CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID AND ESTATUS='A';";     
        
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $activacion = $row["ACTIVACION_ID"];
                $result=$activacion;
            }     
            mysqli_close($conn);
        }
        else{
            mysqli_close($conn);
            return 0;
        }
    }
    else {
        // FALLO LA CONEXION
        return 0;
    }
    return $result;
}
function ExisteAtencionClientes($CHEQUEO_COMPETENCIA_DETALLE_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result=0;
    if ($conn){ 
        $select  = "SELECT ATENCION_CLIENTES_ID,CHEQUEO_COMPETENCIA_DETALLE_ID,ESTATUS FROM ATENCION_CLIENTES ";
        $select .= " WHERE CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID AND ESTATUS ='A';";     
        
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $atencion = $row["ATENCION_CLIENTES_ID"];
                $result=$atencion;
            }     
            mysqli_close($conn);
        }
        else{
            mysqli_close($conn);
            return 0;
        }
    }
    else {
        // FALLO LA CONEXION
        return 0;
    }
    return $result;
}
function InsertarActivacion($ESTATUS,$USUARIO_CREADOR,$FECHA_HORA_CREACION,$CHEQUEO_COMPETENCIA_DETALLE_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);  
                     
                  $query   ="INSERT INTO ACTIVACION ";
                  $query  .=" (CHEQUEO_COMPETENCIA_DETALLE_ID, ";
                  $query  .=" ESTATUS, ";
                  $query  .=" USUARIO_CREADOR, ";
                  $query  .=" FECHA_HORA_CREACION) ";
                  $query  .=" VALUES ";
                  $query  .=" ($CHEQUEO_COMPETENCIA_DETALLE_ID, ";
                  $query  .=" '$ESTATUS', ";
                  $query  .=" '$USUARIO_CREADOR', ";
                  $query  .=" '$FECHA_HORA_CREACION');";
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
function InsertarAtencionClientes($ESTATUS,$USUARIO_CREADOR,$FECHA_HORA_CREACION,$CHEQUEO_COMPETENCIA_DETALLE_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    $fecha = date("Y-m-d H:i:s");
    //echo $fecha;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);     
                
                  $query   ="INSERT INTO ATENCION_CLIENTES ";
                  $query  .=" (CHEQUEO_COMPETENCIA_DETALLE_ID, ";
                  $query  .=" ESTATUS, ";
                  $query  .=" USUARIO_CREADOR, ";
                  $query  .=" FECHA_HORA_CREACION) ";
                  $query  .=" VALUES ";
                  $query  .=" ($CHEQUEO_COMPETENCIA_DETALLE_ID, ";
                  $query  .=" '$ESTATUS', ";
                  $query  .=" '$USUARIO_CREADOR', ";
                 // $query  .=" '$FECHA_HORA_CREACION');";
                 $query .=   "'" . $fecha . "'" . ")";
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
function InsertarActivacionDetalle($ACTIVACION_ID,$CATALOGO_CHEQUEO_ID,$VALOR, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        $select  =" SELECT ACTIVACION_DETALLE_ID ";
        $select .=" FROM ACTIVACION_DETALLE  ";
        $select .=" WHERE ACTIVACION_ID=$ACTIVACION_ID ";
        $select .=" AND CATALOGO_CHEQUEO_ID=$CATALOGO_CHEQUEO_ID;";
        $stmt = mysqli_query($conn, $select);   
        $activacionDetalleID=0;
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $activacionDetalleID = $row["ACTIVACION_DETALLE_ID"];
            }     
        } 
        if($activacionDetalleID===0 || is_null($activacionDetalleID)||strlen($activacionDetalleID)===0||$activacionDetalleID===null){
            $query   ="INSERT INTO ACTIVACION_DETALLE ";
            $query  .=" (ACTIVACION_ID, ";
            $query  .=" CATALOGO_CHEQUEO_ID, ";
            $query  .=" VALOR) ";
            $query  .=" VALUES ";
            $query  .=" ($ACTIVACION_ID, ";
            $query  .=" $CATALOGO_CHEQUEO_ID, ";
            $query  .=" '$VALOR');"; 
        }
        else{
            $query ="UPDATE ACTIVACION_DETALLE ";
            $query  .=" SET ";
            $query  .=" VALOR = '$VALOR' ";
            $query  .=" WHERE  ";
            $query  .=" ACTIVACION_ID = $ACTIVACION_ID ";
            $query  .=" AND CATALOGO_CHEQUEO_ID = $CATALOGO_CHEQUEO_ID; ";
        }
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

function InsertarAtencionClientesDetalle($ATENCION_CLIENTES_ID,$CATALOGO_CHEQUEO_ID,$CATALOGO_ACTIVIDADES_ID,$VALOR, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    $atencionClientesDetalleID=0;
    $query="";
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);    
        $select  =" SELECT ifnull(ATENCION_CLIENTES_DETALLE_ID,0)ATENCION_CLIENTES_DETALLE_ID ";
        $select .=" FROM ATENCION_CLIENTES_DETALLE  ";
        $select .=" WHERE ATENCION_CLIENTES_ID=$ATENCION_CLIENTES_ID ";
        $select .=" AND CATALOGO_CHEQUEO_ID=$CATALOGO_CHEQUEO_ID ";
        $select .=" AND CATALOGO_ACTIVIDADES_ID=$CATALOGO_ACTIVIDADES_ID;";
        $stmt = mysqli_query($conn, $select);   
        //ECHO $select." " ;
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $atencionClientesDetalleID = $row["ATENCION_CLIENTES_DETALLE_ID"];
            }     
        }
        //ECHO $atencionClientesDetalleID." ";
        if($atencionClientesDetalleID===0|| is_null($atencionClientesDetalleID)||strlen($atencionClientesDetalleID)===0||$atencionClientesDetalleID===null){
            $query   ="INSERT INTO ATENCION_CLIENTES_DETALLE ";
            $query  .=" (ATENCION_CLIENTES_ID, ";
            $query  .=" CATALOGO_CHEQUEO_ID, ";
            $query  .=" CATALOGO_ACTIVIDADES_ID, ";
            $query  .=" VALOR) ";
            $query  .=" VALUES ";
            $query  .=" ($ATENCION_CLIENTES_ID, ";
            $query  .=" $CATALOGO_CHEQUEO_ID, ";
            $query  .=" $CATALOGO_ACTIVIDADES_ID, ";
            $query  .=" '$VALOR'); ";
        }else{
            $query   ="UPDATE ATENCION_CLIENTES_DETALLE ";
            $query  .=" SET ";
            $query  .=" VALOR = '$VALOR' ";
            $query  .=" WHERE ATENCION_CLIENTES_DETALLE_ID = $atencionClientesDetalleID; ";
        }
        //ECHO $query;
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
function MostrarBusquedaProveedores($NOMBRE, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result=null;
    if ($conn){                             
            $select  ="SELECT PROVEEDOR_ID,NOMBRE FROM PROVEEDORES WHERE NOMBRE LIKE '%$NOMBRE%';";  
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                 $categorias["PROVEEDOR_ID"]    = $row["PROVEEDOR_ID"];
                 $categorias["NOMBRE"]          = $row["NOMBRE"];
                 $result[]=$categorias;
            }     
            mysqli_close($conn);
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
    return $result;
}
function MostrarCompetenciaCajas($CHEQUEO_COMPETENCIA_DETALLE_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result=null;
    if ($conn){                             
            $select  =" SELECT COMPETENCIA_CAJAS_ID, COMPETENCIA_CAJAS_CANTIDAD, COMPETENCIA_CAJAS_ABIERTAS, "; 
            $select .=" COMPETENCIA_CAJAS_CERRADAS, COMPETENCIA_CAJAS_MAS4CLIENTES "; 
            $select .=" FROM COMPETENCIA_CAJAS WHERE CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID;";  
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                 $cajas["COMPETENCIA_CAJAS_ID"]             = $row["COMPETENCIA_CAJAS_ID"];
                 $cajas["COMPETENCIA_CAJAS_CANTIDAD"]       = $row["COMPETENCIA_CAJAS_CANTIDAD"];
                 $cajas["COMPETENCIA_CAJAS_ABIERTAS"]       = $row["COMPETENCIA_CAJAS_ABIERTAS"];
                 $cajas["COMPETENCIA_CAJAS_CERRADAS"]       = $row["COMPETENCIA_CAJAS_CERRADAS"];
                 $cajas["COMPETENCIA_CAJAS_MAS4CLIENTES"]   = $row["COMPETENCIA_CAJAS_MAS4CLIENTES"];
                 $result[]=$cajas;
            }     
            mysqli_close($conn);
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
    return $result;
}
/*Servico que recibe como parametro un arreglo*/
function ModificacionPersonalAtendiendoAnaquel($SUCURSAL_ID,$FECHA_REVISION,$CHEQUEO_COMPETENCIA_DETALLE_ID,$USUARIO,$FECHA,$RegistrosPersonalAnaquel, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $cambios = 0;
    $proceso = "";
    $query="";
    $result = "";
    $personalAnaquel=0;
    if ($conn)
    {
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        for ($r = 0; $r < count($RegistrosPersonalAnaquel); $r++)
        {
            $cambios++;
            $personaldetalleid = 0;
            $personalAnaquel=0;
            $ren = $RegistrosPersonalAnaquel[$r];
            //Busca el registro, en caso de que no lo encuente inserta y si existe actualiza el registro del encabezado
            $select  = "SELECT * FROM PERSONAL_ANAQUEL WHERE ";
            $select .= "SUCURSAL_ID=$SUCURSAL_ID AND ESTATUS='A' AND FECHA_REVISION='$FECHA_REVISION'";
            $select .= " AND CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID;";

            $stmt = mysqli_query($conn, $select);            

            if ($stmt){
                while ($row = mysqli_fetch_assoc($stmt)){
                    $personalAnaquel= $row["PERSONAL_ANAQUEL_ID"];
                }
                if($personalAnaquel===0|| is_null($personalAnaquel)||strlen($personalAnaquel) )
                {
                    //Si no lo encuentra lo inserta
                    $query  =" INSERT INTO PERSONAL_ANAQUEL (SUCURSAL_ID,CHEQUEO_COMPETENCIA_DETALLE_ID,ESTATUS,FECHA_REVISION,USUARIO_CREADOR,FECHA_HORA_CREACION) ";
                    $query .=" VALUES ($SUCURSAL_ID,$CHEQUEO_COMPETENCIA_DETALLE_ID,'A','$FECHA_REVISION','$USUARIO','$FECHA'); ";
                    if (mysqli_query($conn, $query))
                    {
                        //Lo vuelve a buscar
                        $select  = "SELECT * FROM PERSONAL_ANAQUEL WHERE ";
                        $select .= "SUCURSAL_ID=$SUCURSAL_ID AND ESTATUS='A' AND FECHA_REVISION='$FECHA_REVISION'";
                        $select .= " AND CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID;";
                        $stmt = mysqli_query($conn, $select);
                        if ($stmt)
                        {
                            while ($row = mysqli_fetch_assoc($stmt))
                            {
                                $personalAnaquel = $row["PERSONAL_ANAQUEL_ID"];
                            }
                        }
                        else
                        {
                            mysqli_rollback($conn);
                            $result .= "Se presento un error con al insertar el encabezado de personal anaquel "."\n";
                            $cambios = 0;
                            break;
                        }
                    }
                    else
                    {
                        mysqli_rollback($conn);
                        $result .= "Se presento un error con al insertar el encabezado de personal anaquel "."\n";
                        $cambios = 0;
                        break;
                    }
                }
                // insercion del detalle
                if($personalAnaquel>0)
                {
                    $select  = "SELECT PA.PERSONAL_ANAQUEL_ID,PAD.PERSONAL_ANAQUEL_DETALLE_ID, PAD.PROVEEROR_ID,P.NOMBRE, PAD.CANTIDAD FROM PERSONAL_ANAQUEL AS PA ";
                    $select .= " INNER JOIN PERSONAL_ANAQUEL_DETALLE AS PAD ON PA.PERSONAL_ANAQUEL_ID=PAD.PERSONAL_ANAQUEL_ID ";
                    $select .= " LEFT JOIN PROVEEDORES AS P ON PAD.PROVEEROR_ID=P.PROVEEDOR_ID ";
                    $select .= " WHERE SUCURSAL_ID=$SUCURSAL_ID AND ESTATUS='A' AND FECHA_REVISION='$FECHA_REVISION' ";
                    $select .= " AND CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID";
                    $select .= " ORDER BY PAD.PERSONAL_ANAQUEL_DETALLE_ID ASC;";

                    $stmt = mysqli_query($conn, $select);

                    if ($stmt)
                    {
                        while ($row = mysqli_fetch_assoc($stmt))
                        {
                            $personaldetalleid = $row["PERSONAL_ANAQUEL_DETALLE_ID"];
                        }
                        if ($personaldetalleid == 0)
                        {
                            //Insertar
                            $prov="";
                            if($ren["PROVEEROR_ID"]===0||is_null($ren["PROVEEROR_ID"])){
                                $prov="NULL";
                            }else {
                                $prov=$ren["PROVEEROR_ID"];
                            }

                            $query   ="INSERT INTO PERSONAL_ANAQUEL_DETALLE(PERSONAL_ANAQUEL_ID,PROVEEROR_ID,CANTIDAD)";
                            $query  .="VALUES($personalAnaquel,$prov".",".$ren["CANTIDAD"].");";
                            //Ejecutar la query

                            $proceso = "Se registro el personal anaquel " . $ren["PROVEEDOR_NOMBRE"];
                            if (mysqli_query($conn, $query))
                            {
                                $result .= $proceso . "\n";
                            }
                            else
                            {
                                mysqli_rollback($conn);
                                $result .= "Se presento un error al tratar de insertar el personal anaquel detalle " . $ren["PROVEEDOR_ID"]." - " . $ren["PROVEEDOR_NOMBRE"]." - ". $ren["CANTIDAD"] .$query . "\n";
                                $cambios = 0;
                                break;
                            }
                        }
                        else
                        {
                            //Actualizar
                            $prov="";
                            if($ren["PROVEEROR_ID"]===0){
                                $prov="NULL";
                            }else {
                                $prov=$ren["PROVEEROR_ID"];
                            }

                            $query  = "UPDATE PERSONAL_ANAQUEL_DETALLE "; 
                            $query .= " SET "; 
                            $query .= " PERSONAL_ANAQUEL_ID = $personalAnaquel, ";
                            $query .= " PROVEEROR_ID = $prov, ";
                            $query .= " CANTIDAD = ".$ren["CANTIDAD"];
                            $query .= " WHERE "; 
                            $query .= " PERSONAL_ANAQUEL_DETALLE_ID = $personaldetalleid;";  
                            
                            $proceso = "Se actualizo el personal anaquel " . $ren["PROVEEDOR_NOMBRE"];
                        }
                        if (mysqli_query($conn, $query))
                        {
                            $result .= $proceso . "\n";
                        }
                        else
                        {
                            mysqli_rollback($conn);
                            $result .= "Se presento un error al actualizar el personal anaquel detalle con los datos: ". $ren["PROVEEDOR_ID"]." - " . $ren["PROVEEDOR_NOMBRE"]." - ". $ren["CANTIDAD"] . "\n";
                            $cambios = 0;
                            break;
                        }
                    }
                    else
                    {
                        mysqli_rollback($conn);
                        $result .= "Se presento un error al buscar el encabezado del personal anaquel " . $ren["PROVEEDOR_NOMBRE"] . "\n";
                        $cambios = 0;
                        break;
                    }
                }
                if ($cambios > 0)
                {
                    mysqli_commit($conn);
                }
                mysqli_close($conn);
            }
        }
    }
    else
    {
        $result .= "Se presento un error al establecer conexion." . "\n";
    } 
    return $result;
}
function MostrarActivaciones($ACTIVACION_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result=null;
    if ($conn){                             
            $select  ="SELECT AD.ACTIVACION_DETALLE_ID,AD.CATALOGO_CHEQUEO_ID,CC.NOMBRE,VALOR ";
            $select .=" FROM ACTIVACION_DETALLE AS AD ";
           $select .=" INNER JOIN CATALOGO_CHEQUEO AS CC ON AD.CATALOGO_CHEQUEO_ID=CC.CATALOGO_CHEQUEO_ID ";
           $select .=" WHERE ";
           $select .=" AD.ACTIVACION_ID=$ACTIVACION_ID;";  
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                 $categorias["ACTIVACION_DETALLE_ID"]   = $row["ACTIVACION_DETALLE_ID"];
                 $categorias["CATALOGO_CHEQUEO_ID"]     = $row["CATALOGO_CHEQUEO_ID"];
                 $categorias["NOMBRE"]                  = $row["NOMBRE"];
                 $categorias["VALOR"]                   = $row["VALOR"];
                 $result[]=$categorias;
            }     
            mysqli_close($conn);
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
    return $result;
}
function MostrarAtencionClientes($ATENCION_CLIENTES_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result=null;
    if ($conn){                             
            $select  ="SELECT ACD.ATENCION_CLIENTES_DETALLE_ID,CC.CATALOGO_CHEQUEO_ID,CC.NOMBRE AS CHEQUEO,CA.NOMBRE AS ACTIVIDAD,VALOR";  
            $select .=" FROM ATENCION_CLIENTES_DETALLE AS ACD";  
            $select .=" INNER JOIN CATALOGO_CHEQUEO AS CC ON ACD.CATALOGO_CHEQUEO_ID=CC.CATALOGO_CHEQUEO_ID";  
            $select .=" INNER JOIN CATALOGO_ACTIVIDADES AS CA ON ACD.CATALOGO_ACTIVIDADES_ID=CA.CATALOGO_ACTIVIDADES_ID";  
            $select .=" WHERE";  
            $select .=" ACD.ATENCION_CLIENTES_ID=$ATENCION_CLIENTES_ID;";  
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                 $categorias["ATENCION_CLIENTES_DETALLE_ID"]    = $row["ATENCION_CLIENTES_DETALLE_ID"];
                 $categorias["CATALOGO_CHEQUEO_ID"]             = $row["CATALOGO_CHEQUEO_ID"];
                 $categorias["CHEQUEO"]                         = $row["CHEQUEO"];
                 $categorias["ACTIVIDAD"]                       = $row["ACTIVIDAD"];
                 $categorias["VALOR"]                           = $row["VALOR"];
                 $result[]=$categorias;
            }     
            mysqli_close($conn);
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
    return $result;
}
function ActualizarHoraInicioFinChequeoCompetencias($TIPO,$HORA,$CHEQUEO_COMPETENCIA_ID,$USARIO_MODIFICACION,$FECHA_HORA_MODIFICACION, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
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
            $query  = "UPDATE CHEQUEO_COMPETENCIA ";
            $query .= " SET ";
            $query .= " $TIPO_ACTUALIZACION ";
            $query .= " USARIO_MODIFICACION = '$USARIO_MODIFICACION', ";
            $query .= " FECHA_HORA_MODIFICACION ='$FECHA_HORA_MODIFICACION' ";
            $query .= " WHERE FALTANTES_ID = $CHEQUEO_COMPETENCIA_ID $WHERE_COMPLEMENTO;";
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
function MuestraHoraInicioFinChequeoCompetencias($CHEQUEO_COMPETENCIA_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    if ($conn){
        $select  ="SELECT HORA_INICIO,HORA_FIN FROM CHEQUEO_COMPETENCIA ";
        $select .=" WHERE CHEQUEO_COMPETENCIA_ID = $CHEQUEO_COMPETENCIA_ID;";
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
function ActualizarHoraInicioFinTareaChequeoCompetencias($TIPO,$HORA,$TAREA,$TAREA_ID,$USARIO_MODIFICACION,$FECHA_HORA_MODIFICACION, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    $fecha = date("Y-m-d H:i:s");
    $TIPO_ACTUALIZACION="";$WHERE_COMPLEMENTO="";$TABLA="";$TABLA_ID="";
    if(is_null($HORA)||strlen($HORA)===0){

    }
    else{
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE); 
          // if( $TIPO $HORA $TAREA $TAREA_ID $USARIO_MODIFICACION $FECHA_HORA_MODIFICACION)
            if ($TIPO==="I") {
                $TIPO_ACTUALIZACION="HORA_INICIO ='$HORA',"; 
                $WHERE_COMPLEMENTO=" AND HORA_INICIO IS NULL";
            }
            elseif($TIPO==="F") {
                $TIPO_ACTUALIZACION="HORA_FIN = '$HORA',";
                $WHERE_COMPLEMENTO=" AND HORA_FIN IS NULL";
            }
            
            if($TAREA==="PA") {
                $TABLA="PERSONAL_ANAQUEL";
                $TABLA_ID ="PERSONAL_ANAQUEL_ID";
            }
            elseif($TAREA==="CC") {
                $TABLA="CONTEO_CLIENTES";
                $TABLA_ID ="CONTEO_CLIENTES_ID";
            }
            elseif($TAREA==="LC") {
                $TABLA="COMPETENCIA_CAJAS";
                $TABLA_ID ="COMPETENCIA_CAJAS_ID";
            }
            elseif($TAREA==="AC") {
                $TABLA="ATENCION_CLIENTES";
                $TABLA_ID ="ATENCION_CLIENTES_ID";
            }
            elseif($TAREA==="AA") {
                $TABLA="ACTIVACION";
                $TABLA_ID ="ACTIVACION_ID";
            }
            elseif($TAREA==="PP") {
                $TABLA="ASIGNACIONES_DETALLE_SUCURSALES";
                $TABLA_ID ="ASIGNACIONES_DETALLE_SUCURSALES_ID";
            }
            elseif($TAREA==="MS") {
                $TABLA="MYSTERY_SHOPPER";
                $TABLA_ID ="MYSTERY_SHOPPER_ID";
            }

            $query  = "UPDATE $TABLA ";
            $query .= " SET ";
            $query .= " $TIPO_ACTUALIZACION ";
            $query .= " USUARIO_MODIFICACION = '$USARIO_MODIFICACION', ";
           // $query .= " FECHA_HORA_MODIFICACION ='$FECHA_HORA_MODIFICACION' ";
           $query .= " FECHA_HORA_MODIFICACION = '" . $fecha . "'" ;
            $query .= " WHERE $TABLA_ID = $TAREA_ID $WHERE_COMPLEMENTO;";
            
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
/* Cantidad de clientes */
function InsertarConteoClientes($CHEQUEO_COMPETENCIA_DETALLE_ID,$CANTIDAD,$HORA_INICIO,$HORA_FIN,$USUARIO_CREADOR,$FECHA_HORA_CREACION, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    $horaInicio="";
    $horaFin="";
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE); 
        if(strlen($HORA_INICIO)===0||$HORA_INICIO===NULL||is_null($HORA_INICIO))
        {
            $horaInicio="NULL";
        }else
        {
            $horaInicio="'$HORA_INICIO'";
        }
        if(strlen($HORA_FIN)===0||$HORA_FIN===NULL||is_null($HORA_FIN))
        {
            $horaFin="NULL";
        }else
        {
            $horaFin="'$HORA_FIN'";
        }
        //Validar que solo exista un encabezado

       $conteoClientesID=0;
        $select  ="SELECT CONTEO_CLIENTES_ID FROM CONTEO_CLIENTES ";
        $select .=" WHERE CHEQUEO_COMPETENCIA_DETALLE_ID = $CHEQUEO_COMPETENCIA_DETALLE_ID;";
        $stmt = mysqli_query($conn, $select);              
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $conteoClientesID =$row["CONTEO_CLIENTES_ID"];
            }
        }else {
            $conteoClientesID = -1;
        }
        if($conteoClientesID===0){
            $query   ="INSERT INTO CONTEO_CLIENTES ";
            $query  .=" (CHEQUEO_COMPETENCIA_DETALLE_ID,CANTIDAD,HORA_INICIO,HORA_FIN,USUARIO_CREADOR,FECHA_HORA_CREACION) ";
            $query  .=" VALUES ";
            $query  .=" ($CHEQUEO_COMPETENCIA_DETALLE_ID,$CANTIDAD,$horaInicio,$horaFin,'$USUARIO_CREADOR','$FECHA_HORA_CREACION');"; 
        
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
        
        $result = false;
    }
    return $result;
}
function ActualizaHoraInicioFinConteoClientes($TIPO,$HORA,$CHEQUEO_COMPETENCIA_DETALLE_ID,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    $TIPO_ACTUALIZACION="";$WHERE_COMPLEMENTO="";
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
                $query  = "UPDATE CONTEO_CLIENTES ";
                $query  .=" SET ";
                $query  .=" $TIPO_ACTUALIZACION  ";
                $query  .=" USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
                $query  .=" FECHA_HORA_MODIFICACION = '$FECHA_HORA_MODIFICACION' ";
                $query  .=" WHERE CHEQUEO_COMPETENCIA_DETALLE_ID = $CHEQUEO_COMPETENCIA_DETALLE_ID $WHERE_COMPLEMENTO; ";
                
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
function ActualizaCantidadConteoClientes($CANTIDAD,$CHEQUEO_COMPETENCIA_DETALLE_ID,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
       
                  $query  = "UPDATE CONTEO_CLIENTES ";
                  $query  .=" SET ";
                  $query  .=" CANTIDAD = $CANTIDAD, ";
                  $query  .=" USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
                  $query  .=" FECHA_HORA_MODIFICACION = '$FECHA_HORA_MODIFICACION' ";
                  $query  .=" WHERE CHEQUEO_COMPETENCIA_DETALLE_ID = $CHEQUEO_COMPETENCIA_DETALLE_ID ; ";
                  
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

function MostrarConteoClientes($CHEQUEO_COMPETENCIA_DETALLE_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    if ($conn){
        $select  = "SELECT CONTEO_CLIENTES_ID,CANTIDAD,HORA_INICIO,HORA_FIN ";
        $select .= " FROM CONTEO_CLIENTES WHERE CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID; ";
        
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $roles["CONTEO_CLIENTES_ID"]=$row["CONTEO_CLIENTES_ID"];
                $roles["CANTIDAD"]          =$row["CANTIDAD"];
                $roles["HORA_INICIO"]       =$row["HORA_INICIO"];
                $roles["HORA_FIN"]          =$row["HORA_FIN"];
                $result[]=$roles;                            
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
function MostrarHoraInicioFinActivicadesChequeoCompetencia($CHEQUEO_COMPETENCIA_DETALLE_ID, $SUCURSAL_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    if ($conn){
        $select  = "Select CHEQUEO_COMPETENCIA_DETALLE_ID,'PA' AS TAREA,HORA_INICIO,HORA_FIN from PERSONAL_ANAQUEL as PA WHERE CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID; ";
        
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $tareas["CHEQUEO_COMPETENCIA_DETALLE_ID"]   =$row["CHEQUEO_COMPETENCIA_DETALLE_ID"];
                $tareas["TAREA"]                            =$row["TAREA"];
                $tareas["HORA_INICIO"]                      =$row["HORA_INICIO"];
                $tareas["HORA_FIN"]                         =$row["HORA_FIN"];
                $result[]=$tareas;                            
            }
        } 
        /*--------------CONTEO_CLIENTES------------- */
        $select  = "Select CHEQUEO_COMPETENCIA_DETALLE_ID,'CC' AS TAREA,HORA_INICIO,HORA_FIN from CONTEO_CLIENTES as CC WHERE CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID; ";
        
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $tareas["CHEQUEO_COMPETENCIA_DETALLE_ID"]   =$row["CHEQUEO_COMPETENCIA_DETALLE_ID"];
                $tareas["TAREA"]                            =$row["TAREA"];
                $tareas["HORA_INICIO"]                      =$row["HORA_INICIO"];
                $tareas["HORA_FIN"]                         =$row["HORA_FIN"];
                $result[]=$tareas;                            
            }
        } 
        /*--------------COMPETENCIA_CAJAS------------ */
        $select  = "Select CHEQUEO_COMPETENCIA_DETALLE_ID,'LC' AS TAREA,HORA_INICIO,HORA_FIN from COMPETENCIA_CAJAS as LC WHERE CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID; ";
        
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $tareas["CHEQUEO_COMPETENCIA_DETALLE_ID"]   =$row["CHEQUEO_COMPETENCIA_DETALLE_ID"];
                $tareas["TAREA"]                            =$row["TAREA"];
                $tareas["HORA_INICIO"]                      =$row["HORA_INICIO"];
                $tareas["HORA_FIN"]                         =$row["HORA_FIN"];
                $result[]=$tareas;                            
            }
        } 
        /*---------------ACTIVACION-------------------*/
        $select  = "Select CHEQUEO_COMPETENCIA_DETALLE_ID,'AA' AS TAREA,HORA_INICIO,HORA_FIN from ACTIVACION as AA WHERE CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID; ";
        
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $tareas["CHEQUEO_COMPETENCIA_DETALLE_ID"]   =$row["CHEQUEO_COMPETENCIA_DETALLE_ID"];
                $tareas["TAREA"]                            =$row["TAREA"];
                $tareas["HORA_INICIO"]                      =$row["HORA_INICIO"];
                $tareas["HORA_FIN"]                         =$row["HORA_FIN"];
                $result[]=$tareas;                            
            }
        } 
        /*------------ATENCION_CLIENTES-----------------*/
        $select  = "Select CHEQUEO_COMPETENCIA_DETALLE_ID,'AC' AS TAREA,HORA_INICIO,HORA_FIN from ATENCION_CLIENTES as AC WHERE CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID; ";
        
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $tareas["CHEQUEO_COMPETENCIA_DETALLE_ID"]   =$row["CHEQUEO_COMPETENCIA_DETALLE_ID"];
                $tareas["TAREA"]                            =$row["TAREA"];
                $tareas["HORA_INICIO"]                      =$row["HORA_INICIO"];
                $tareas["HORA_FIN"]                         =$row["HORA_FIN"];
                $result[]=$tareas;                            
            }
        }
        /*--------------PRODUCTO_PRECIO---------------*/
        $select  = "Select CHEQUEO_COMPETENCIA_DETALLE_ID,'PP' AS TAREA,HORA_INICIO,HORA_FIN from ASIGNACIONES_DETALLE_SUCURSALES as PP ";
        $select .= "INNER JOIN CHEQUEO_COMPETENCIA_DETALLE as CCD on PP.ASIGNACION_ID=CCD.ASIGNACION_ID WHERE CHEQUEO_COMPETENCIA_DETALLE_ID=$CHEQUEO_COMPETENCIA_DETALLE_ID  AND SUCURSAL_ID=$SUCURSAL_ID; ";
        
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $tareas["CHEQUEO_COMPETENCIA_DETALLE_ID"]   =$row["CHEQUEO_COMPETENCIA_DETALLE_ID"];
                $tareas["TAREA"]                            =$row["TAREA"];
                $tareas["HORA_INICIO"]                      =$row["HORA_INICIO"];
                $tareas["HORA_FIN"]                         =$row["HORA_FIN"];
                $result[]=$tareas;                            
            }
        }        
        mysqli_close($conn);
            return $result;
    }
    else {
        
        return null; 
    }
}
function MuestraHoraInicioFinCompetenciaCajas($COMPETENCIA_CAJAS_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    if ($conn){
        $select  ="SELECT HORA_INICIO,HORA_FIN FROM COMPETENCIA_CAJAS ";
        $select .=" WHERE COMPETENCIA_CAJAS_ID = $COMPETENCIA_CAJAS_ID;";
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
function ActualizarHoraInicioFinCompetenciaCajas($TIPO,$HORA,$COMPETENCIA_CAJAS_ID,$USARIO_MODIFICACION,$FECHA_HORA_MODIFICACION, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
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
            $query  = "UPDATE COMPETENCIA_CAJAS ";
            $query .= " SET ";
            $query .= " $TIPO_ACTUALIZACION ";
            $query .= " USARIO_MODIFICACION = '$USARIO_MODIFICACION', ";
            $query .= " FECHA_HORA_MODIFICACION ='$FECHA_HORA_MODIFICACION' ";
            $query .= " WHERE FALTANTES_ID = $COMPETENCIA_CAJAS_ID $WHERE_COMPLEMENTO;";
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
function EliminarPersonalAtendiendoAnaquelDetalle($PERSONAL_ANAQUEL_DETALLE_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    if(is_null($PERSONAL_ANAQUEL_DETALLE_ID)||strlen($PERSONAL_ANAQUEL_DETALLE_ID)===0){

    }
    else{
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
            $query  = " DELETE FROM PERSONAL_ANAQUEL_DETALLE ";
            $query .= " WHERE PERSONAL_ANAQUEL_DETALLE_ID=$PERSONAL_ANAQUEL_DETALLE_ID;";
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
$server->register(
    'EliminarPersonalAtendiendoAnaquelDetalle',
    array(
        'PERSONAL_ANAQUEL_DETALLE_ID'=>'xsd:int',
        'BD'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Elimina el Personal anaquel detalle id indicado');
$server->wsdl->addComplexType(
    'MostrarChequeoCompetencia',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'CHEQUEO_COMPETENCIA_ID'            => array('name'=>'CHEQUEO_COMPETENCIA_ID','type'=>'xsd:int'),
        'CLIENTE_SOLICITANTE'               => array('name'=>'CLIENTE_SOLICITANTE','type'=>'xsd:string'),
        'FOLIO'                             => array('name'=>'FOLIO','type'=>'xsd:string'),
        'FECHA_REVISION'                    => array('name'=>'FECHA_REVISION','type'=>'xsd:string'),
        'CHEQUEO_COMPETENCIA_DETALLE_ID'    => array('name'=>'CHEQUEO_COMPETENCIA_DETALLE_ID','type'=>'xsd:int'),
        'SUCURSALES_CHEQUEO_COMPETENCIA_ID' => array('name'=>'SUCURSALES_CHEQUEO_COMPETENCIA_ID','type'=>'xsd:int'),
        'SUCURSAL_ID'                       => array('name'=>'SUCURSAL_ID','type'=>'xsd:int'),
        'PERSONAL_ANAQUEL_ID'               => array('name'=>'PERSONAL_ANAQUEL_ID','type'=>'xsd:int'),
        'ASIGNACION_ID'                     => array('name'=>'ASIGNACION_ID','type'=>'xsd:int'),
        'PROD_PREC_FOLIO'                   => array('name'=>'PROD_PREC_FOLIO','type'=>'xsd:string'),
        'PROD_PREC_FECHA_REV'               => array('name'=>'PROD_PREC_FECHA_REV','type'=>'xsd:string'),
        'PROD_PREC_ESTATUS'                 => array('name'=>'PROD_PREC_ESTATUS','type'=>'xsd:string')/*,
        'ATEN_CLIEN'                        => array('name'=>'ATEN_CLIEN','type'=>'xsd:string'),
        'CANT_CLIEN'                        => array('name'=>'CANT_CLIEN','type'=>'xsd:string'),
        'CANT_ACTIV'                        => array('name'=>'CANT_ACTIV','type'=>'xsd:string'),
        'COMP_CAJA'                         => array('name'=>'COMP_CAJA','type'=>'xsd:string')*/
    )
);    
$server->wsdl->addComplexType(
    'MostrarChequeoCompetenciaArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarChequeoCompetencia[]')),
    'tns:MostrarChequeoCompetencia'
    );
$server->register(
    'MostrarChequeoCompetencia',
    array(
        'FECHA_REVISION'=>'xsd:string',
        'USUARIO_ASIGNADO'=>'xsd:string',
        'SUCURSAL_ID'=>'xsd:int',
        'BD'=>'xsd:string'
    ),
    array('return'=> 'tns:MostrarChequeoCompetenciaArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los datos del chequeo de competencia');

    $server->register(
        'ActualizarChequeoCompetenciaDetalle',
        array(
            'CHEQUEO_COMPETENCIA_ID'=>'xsd:int',
            'CANTIDAD_CLIENTES'=>'xsd:int',
            'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
            'SUCURSAL_ID'=>'xsd:int',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza un registro de la tabla chequeo de competencia detalle');

    $server->register(
        'ActualizarEstatusChequeoCompetencia',
        array(
            'CHEQUEO_COMPETENCIA_ID'=>'xsd:int',
            'ESTATUS'=>'xsd:string',
            'USUARIO_MODIFICACION'=>'xsd:string',
            'FECHA_HORA_MODIFICACION'=>'xsd:string',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza el estatus del encabezado del chequeo de competencia ');
    $server->register(
        'InsertarCompetenciaCajas',
        array(
            'COMPETENCIA_CAJAS_CANTIDAD'=>'xsd:int',
            'COMPETENCIA_CAJAS_ABIERTAS'=>'xsd:int',
            'COMPETENCIA_CAJAS_CERRADAS'=>'xsd:int',
            'COMPETENCIA_CAJAS_MAS4CLIENTES'=>'xsd:int',
            'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta competencia de cajas en el sistema');

    $server->register(
        'BuscarCompetenciaCajas',
        array('CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
        'BD'=>'xsd:string'),
        array('return'=> 'xsd:int'),
        $namespace,
        false,
        'rpc',
        false,
        'Busca una competencia de cajas dependiendo de CHEQUEO_COMPETENCIA_DETALLE_ID');
    $server->register(
        'ActualizaCompetenciaCajas',
        array(
            'COMPETENCIA_CAJAS_CANTIDAD'=>'xsd:int',
            'COMPETENCIA_CAJAS_ABIERTAS'=>'xsd:int',
            'COMPETENCIA_CAJAS_CERRADAS'=>'xsd:int',
            'COMPETENCIA_CAJAS_MAS4CLIENTES'=>'xsd:int',
            'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza competencia de cajas desde un CHEQUEO_COMPETENCIA_DETALLE_ID');
    $server->register(
        'InsertarPersonalAnaquel',
        array(
            'SUCURSAL_ID'=>'xsd:int',
            'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
            'FECHA_REVISION'=>'xsd:string',
            'USUARIO_CREADOR'=>'xsd:string',
            'FECHA_HORA_CREACION'=>'xsd:string',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta personal atendiendo anaquel');

    $server->register(
        'BuscarPersonalAnaquel',
        array(
            'SUCURSAL_ID'=>'xsd:int',
            'FECHA_REVISION'=>'xsd:string',
            'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
            'BD'=>'xsd:string'
        ),
        array('return'=> 'xsd:int'),
        $namespace,
        false,
        'rpc',
        false,
        'Busca el id de personal atendiendo anaquel dependiendo de la sucursal y la fecha de revision');
        $server->wsdl->addComplexType(
            'BuscarPersonalAnaquelDetalle',
            'complexType',
            'struct',
            'all',
            '',
            array(             
                'PERSONAL_ANAQUEL_ID'               => array('name'=>'PERSONAL_ANAQUEL_ID','type'=>'xsd:int'),
                'PERSONAL_ANAQUEL_DETALLE_ID'       => array('name'=>'PERSONAL_ANAQUEL_DETALLE_ID','type'=>'xsd:int'),
                'PROVEEROR_ID'                      => array('name'=>'PROVEEROR_ID','type'=>'xsd:int'),
                'NOMBRE'                            => array('name'=>'NOMBRE','type'=>'xsd:string'),
                'CANTIDAD'                          => array('name'=>'CANTIDAD','type'=>'xsd:string')
            )
        );    
        $server->wsdl->addComplexType(
            'BuscarPersonalAnaquelDetalleArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:BuscarPersonalAnaquelDetalle[]')),
            'tns:BuscarPersonalAnaquelDetalle'
            );
        $server->register(
            'BuscarPersonalAnaquelDetalle',
            array(
                'SUCURSAL_ID'=>'xsd:int',
                'FECHA_REVISION'=>'xsd:string',
                'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
                'BD'=>'xsd:string'
            ),
            array('return'=> 'tns:BuscarPersonalAnaquelDetalleArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con los datos del personal anaquel detalle especificado');
    $server->register(
        'ActualizarEstatusPersonalAnaquel',
        array(
            'SUCURSAL_ID'=>'xsd:int',
            'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
            'FECHA_REVISION'=>'xsd:string',
            'ESTATUS'=>'xsd:string',
            'USUARIO_MODIFICACION'=>'xsd:string',
            'FECHA_HORA_MODIFICACION'=>'xsd:string',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza estatus de personal atendiendo anaquel dependiendo de la sucursal y la fecha de revision');

    $server->register(
        'InsertarPersonalAnaquelDetalle',
        array(
            'PERSONAL_ANAQUEL_ID'=>'xsd:int',
            'PROVEEROR_ID'=>'xsd:int',
            'CANTIDAD'=>'xsd:int',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta detalle de personal atendiendo anaquel');
    $server->register(
        'ActualizaPersonalAnaquelDetalle',
        array(
            'PERSONAL_ANAQUEL_DETALLE_ID'=>'xsd:int',
            'PERSONAL_ANAQUEL_ID'=>'xsd:int',
            'PROVEEROR_ID'=>'xsd:int',
            'CANTIDAD'=>'xsd:int',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza el detalle del personal atendiendo anaquel dependiendo de PERSONAL_ANAQUEL_DETALLE_ID');

        $server->wsdl->addComplexType(
            'SucursalesUsuarioChequeoCompetencia',
            'complexType',
            'struct',
            'all',
            '',
            array(
                'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
                'LAT_LONG' => array('name'=>'LAT_LONG','type'=>'xsd:string'),
                'GEO_CERCA' => array('name'=>'GEO_CERCA','type'=>'xsd:string'),
                'SUCURSAL_ID' => array('name'=>'SUCURSAL_ID','type'=>'xsd:string')
            )
        );    

        $server->wsdl->addComplexType(
            'SucursalesUsuarioChequeoCompetenciaArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:SucursalesUsuarioChequeoCompetencia[]')),
            'tns:SucursalesUsuarioChequeoCompetencia'
            );

        $server->register(
            'SucursalesUsuarioChequeoCompetencia',
            array(
                'USUARIO_ASIGNADO'=>'xsd:string',
                'FECHA_REVISION'=>'xsd:string',
                'BD'=>'xsd:string'
        ),
            array('return'=> 'tns:SucursalesUsuarioChequeoCompetenciaArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con las sucursales del usuario para chequeo de competencias'); 

    $server->register(
        'BuscarCatalogoActividadID',
        array(
            'NOMBRE'=>'xsd:string',
            'BD'=>'xsd:string'
        ),
        array('return'=> 'xsd:string'),
        $namespace,
        false,
        'rpc',
        false,
        'Devuelve el Id del catalogo de actividad especificado por el nombre');

    $server->register(
        'BuscarCatalogoChequeoID',
        array(
            'NOMBRE'=>'xsd:string',
            'BD'=>'xsd:string'
        ),
        array('return'=> 'xsd:string'),
        $namespace,
        false,
        'rpc',
        false,
        'Devuelve el Id del catalogo de chequeo especificado por el nombre');

    $server->register(
        'ExisteActivacion',
        array(
            'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
            'BD'=>'xsd:string'
        ),
        array('return'=> 'xsd:int'),
        $namespace,
        false,
        'rpc',
        false,
        'Devuelve el Id de la activación de un chequeo de competencia');

    $server->register(
        'ExisteAtencionClientes',
        array(
            'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
            'BD'=>'xsd:string'
        ),
        array('return'=> 'xsd:int'),
        $namespace,
        false,
        'rpc',
        false,
        'Devuelve el Id de la atención a clientes de un chequeo de competencia');

    $server->register(
        'InsertarActivacion',
        array(
            'ESTATUS'=>'xsd:string',
            'USUARIO_CREADOR'=>'xsd:string',
            'FECHA_HORA_CREACION'=>'xsd:string',
            'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:string',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta el encabezado de Activacion');

    $server->register(
        'InsertarAtencionClientes',
        array(
            'ESTATUS'=>'xsd:string',
            'USUARIO_CREADOR'=>'xsd:string',
            'FECHA_HORA_CREACION'=>'xsd:string',
            'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:string',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta el encabezado de atención a clientes');

    $server->register(
        'InsertarActivacionDetalle',
        array(
            'ACTIVACION_ID'=>'xsd:int',
            'CATALOGO_CHEQUEO_ID'=>'xsd:int',
            'VALOR'=>'xsd:string',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta el detalle de la Activacion');
        
    $server->register(
        'InsertarAtencionClientesDetalle',
        array(
            'ATENCION_CLIENTES_ID'=>'xsd:int',
            'CATALOGO_CHEQUEO_ID'=>'xsd:int',
            'CATALOGO_ACTIVIDADES_ID'=>'xsd:int',
            'VALOR'=>'xsd:string',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta el detalle de atenciion a clientes');
        
        $server->wsdl->addComplexType(
            'MostrarBusquedaProveedores',
            'complexType',
            'struct',
            'all',
            '',
            array(          
                'PROVEEDOR_ID' => array('name'=>'PROVEEDOR_ID','type'=>'xsd:string'),      
                'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string')
            )
        );    
        
        $server->wsdl->addComplexType(
            'MostrarBusquedaProveedoresArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(/*'NOMBRE','xsd:string'*/),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarBusquedaProveedores[]')),
            'tns:MostrarBusquedaProveedores'
            );
    
        $server->register(
            'MostrarBusquedaProveedores',
            array('NOMBRE'=>'xsd:string',
            'BD'=>'xsd:string'),
            array('return'=> 'tns:MostrarBusquedaProveedoresArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con el resultado de la busqueda de proveedor');

$server->wsdl->addComplexType(
    'MostrarCompetenciaCajas',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'COMPETENCIA_CAJAS_ID' => array('name'=>'COMPETENCIA_CAJAS_ID','type'=>'xsd:int'),
        'COMPETENCIA_CAJAS_CANTIDAD' => array('name'=>'COMPETENCIA_CAJAS_CANTIDAD','type'=>'xsd:int'),     
        'COMPETENCIA_CAJAS_ABIERTAS' => array('name'=>'COMPETENCIA_CAJAS_ABIERTAS','type'=>'xsd:int'),     
        'COMPETENCIA_CAJAS_CERRADAS' => array('name'=>'COMPETENCIA_CAJAS_CERRADAS','type'=>'xsd:int'),     
        'COMPETENCIA_CAJAS_MAS4CLIENTES' => array('name'=>'COMPETENCIA_CAJAS_MAS4CLIENTES','type'=>'xsd:int')
    )
);    

$server->wsdl->addComplexType(
    'MostrarCompetenciaCajasArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(/*'NOMBRE','xsd:string'*/),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarCompetenciaCajas[]')),
    'tns:MostrarCompetenciaCajas'
    );

$server->register(
    'MostrarCompetenciaCajas',
    array('CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
    'BD'=>'xsd:string'),
    array('return'=> 'tns:MostrarCompetenciaCajasArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los datos de la competencia de cajas');


$server->wsdl->addComplexType(
    'RegistrosPersonalAnaquel',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'PROVEEDOR_ID' => array('name'=>'PROVEEDOR_ID','type'=>'xsd:string'),
        'PROVEEDOR_NOMBRE' => array('name'=>'PROVEEDOR_NOMBRE','type'=>'xsd:string'),
        'CANTIDAD' => array('CANTIDAD'=>'Nombre','type'=>'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'RegistrosPersonalAnaquelArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:RegistrosPersonalAnaquel[]')),
    'tns:RegistrosPersonalAnaquel'
);
$server->register(
    'ModificacionPersonalAtendiendoAnaquel',
    array(
         'SUCURSAL_ID'=>'xsd:int',
        'FECHA_REVISION'=>'xsd:string',
        'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
        'USUARIO'=>'xsd:string',
        'FECHA'=>'xsd:string',
        'soapObjects'=>'tns:RegistrosPersonalAnaquelArray',
        'BD'=>'xsd:string'
    ),
    array('return'=>'xsd:string'),
    $namespace,
    false,
    'rpc',
    false,
    'Procesa un arreglo para la interaccion con la tabla del personal atendiendo anaquel.');         
    
    $server->wsdl->addComplexType(
        'MostrarActivaciones',
        'complexType',
        'struct',
        'all',
        '',
        array(          
            'ACTIVACION_DETALLE_ID' => array('name'=>'ACTIVACION_DETALLE_ID','type'=>'xsd:string'),      
            'CATALOGO_CHEQUEO_ID' => array('name'=>'CATALOGO_CHEQUEO_ID','type'=>'xsd:string'),      
            'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),      
            'VALOR' => array('name'=>'VALOR','type'=>'xsd:string')
        )
    );    
    
    $server->wsdl->addComplexType(
        'MostrarActivacionesArray',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(/*'NOMBRE','xsd:string'*/),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarActivaciones[]')),
        'tns:MostrarActivaciones'
        );

    $server->register(
        'MostrarActivaciones',
        array('ACTIVACION_ID'=>'xsd:int',
        'BD'=>'xsd:string'),
        array('return'=> 'tns:MostrarActivacionesArray'),
        $namespace,
        false,
        'rpc',
        false,
        'Devuelve un arreglo con el resultado de las activaciones');
       
        $server->wsdl->addComplexType(
            'MostrarAtencionClientes',
            'complexType',
            'struct',
            'all',
            '',
            array(          
                'ATENCION_CLIENTES_DETALLE_ID' => array('name'=>'ATENCION_CLIENTES_DETALLE_ID','type'=>'xsd:string'),      
                'CATALOGO_CHEQUEO_ID' => array('name'=>'CATALOGO_CHEQUEO_ID','type'=>'xsd:string'),      
                'CHEQUEO' => array('name'=>'CHEQUEO','type'=>'xsd:string'),   
                'ACTIVIDAD' => array('name'=>'ACTIVIDAD','type'=>'xsd:string'),     
                'VALOR' => array('name'=>'VALOR','type'=>'xsd:string')
            )
        );    
        
        $server->wsdl->addComplexType(
            'MostrarAtencionClientesArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(/*'NOMBRE','xsd:string'*/),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarAtencionClientes[]')),
            'tns:MostrarAtencionClientes'
            );
    
        $server->register(
            'MostrarAtencionClientes',
            array('ATENCION_CLIENTES_ID'=>'xsd:int',
            'BD'=>'xsd:string'),
            array('return'=> 'tns:MostrarAtencionClientesArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con el resultado de la atencion a clientes');

            $server->register(
            'ActualizarHoraInicioFinChequeoCompetencias',
            array(
                'TIPO'=>'xsd:string',
                'HORA'=>'xsd:string',
                'CHEQUEO_COMPETENCIA_ID'=>'xsd:int',
                'USARIO_MODIFICACION'=>'xsd:string',
                'FECHA_HORA_MODIFICACION'=>'xsd:string',
                'BD'=>'xsd:string'
                ),
            array('return'=>'xsd:boolean'),
            $namespace,
            false,
            'rpc',
            false,
            'Actualiza la hora de inicio y la hora de fin de chequeo de competencias');

        
        $server->wsdl->addComplexType(
            'MuestraHoraInicioFinChequeoCompetencias',
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
            'MuestraHoraInicioFinChequeoCompetenciasArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MuestraHoraInicioFinChequeoCompetencias[]')),
            'tns:MuestraHoraInicioFinChequeoCompetencias'
            );
        $server->register(
            'MuestraHoraInicioFinChequeoCompetencias',
            array(
                'CHEQUEO_COMPETENCIA_ID'=>'xsd:int',
                'BD'=>'xsd:string'
            ),
            array('return'=> 'tns:MuestraHoraInicioFinChequeoCompetenciasArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Muestra la hora de inicio y fin de la tarea de chequeo competencias');

            
        $server->register(
            'ActualizarHoraInicioFinTareaChequeoCompetencias',
            array(
                'TIPO'=>'xsd:string',
                'HORA'=>'xsd:string',
                'TAREA'=>'xsd:string',
                'TAREA_ID'=>'xsd:int',
                'USARIO_MODIFICACION'=>'xsd:string',
                'FECHA_HORA_MODIFICACION'=>'xsd:string',
                'BD'=>'xsd:string'
                ),
            array('return'=>'xsd:boolean'),
            $namespace,
            false,
            'rpc',
            false,
            'Actualiza la hora de inicio y la hora de fin de las diferentes tareas de chequeo de competencias');

$server->register(
    'InsertarConteoClientes',
    array(
        'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
        'CANTIDAD'=>'xsd:int',
        'HORA_INICIO'=>'xsd:string',
        'HORA_FIN'=>'xsd:string',
        'USUARIO_CREADOR'=>'xsd:string',
        'FECHA_HORA_CREACION'=>'xsd:string',
        'BD'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Inserta conteo de clientes en el sistema');

    $server->register(
        'ActualizaHoraInicioFinConteoClientes',
        array(
            'TIPO'=>'xsd:string',
            'HORA'=>'xsd:string',
            'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
            'USUARIO_MODIFICACION'=>'xsd:string',
            'FECHA_HORA_MODIFICACION'=>'xsd:string',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza conteo de clientes en el sistema');

        $server->register(
            'ActualizaCantidadConteoClientes',
            array(
                'CANTIDAD'=>'xsd:int',
                'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
                'USUARIO_MODIFICACION'=>'xsd:string',
                'FECHA_HORA_MODIFICACION'=>'xsd:string',
                'BD'=>'xsd:string'
                ),
            array('return'=>'xsd:boolean'),
            $namespace,
            false,
            'rpc',
            false,
            'Actualiza la cantidad en conteo de clientes en el sistema');
        
    $server->wsdl->addComplexType(
        'MostrarConteoClientes',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'CONTEO_CLIENTES_ID' => array('name'=>'CONTEO_CLIENTES_ID','type'=>'xsd:int'),
            'CANTIDAD' => array('name'=>'CANTIDAD','type'=>'xsd:int'),
            'HORA_INICIO' => array('name'=>'HORA_INICIO','type'=>'xsd:string'),
            'HORA_FIN' => array('name'=>'HORA_FIN','type'=>'xsd:string')
        )
    );    
    $server->wsdl->addComplexType(
        'MostrarConteoClientesArray',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarConteoClientes[]')),
        'tns:MostrarConteoClientes'
        );
    $server->register(
        'MostrarConteoClientes',
        array(
                'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
                'BD'=>'xsd:string'
             ),
        array('return'=> 'tns:MostrarConteoClientesArray'),
        $namespace,
        false,
        'rpc',
        false,
        'Devuelve un arreglo con los datos de conteo de clinetes del sistema');


$server->wsdl->addComplexType(
    'MostrarHoraInicioFinActivicadesChequeoCompetencia',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'CHEQUEO_COMPETENCIA_DETALLE_ID' => array('name'=>'CHEQUEO_COMPETENCIA_DETALLE_ID','type'=>'xsd:string'),
        'TAREA' => array('name'=>'TAREA','type'=>'xsd:string'),
        'HORA_INICIO' => array('name'=>'HORA_INICIO','type'=>'xsd:string'),
        'HORA_FIN' => array('name'=>'HORA_FIN','type'=>'xsd:string')
    )
);    
$server->wsdl->addComplexType(
    'MostrarHoraInicioFinActivicadesChequeoCompetenciaArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarHoraInicioFinActivicadesChequeoCompetencia[]')),
    'tns:MostrarHoraInicioFinActivicadesChequeoCompetencia'
    );
$server->register(
    'MostrarHoraInicioFinActivicadesChequeoCompetencia',
    array(//
        'CHEQUEO_COMPETENCIA_DETALLE_ID'=>'xsd:int',
        'SUCURSAL_ID'=>'xsd:int',
        'BD'=>'xsd:string'
    ),
    array('return'=> 'tns:MostrarHoraInicioFinActivicadesChequeoCompetenciaArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Muestra la hora de inicio y fin de las tareas de chequeo competencias');

    
    $server->wsdl->addComplexType(
        'MuestraHoraInicioFinCompetenciaCajas',
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
        'MuestraHoraInicioFinCompetenciaCajasArray',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MuestraHoraInicioFinCompetenciaCajas[]')),
        'tns:MuestraHoraInicioFinCompetenciaCajas'
        );
    $server->register(
        'MuestraHoraInicioFinCompetenciaCajas',
        array(
            'COMPETENCIA_CAJAS_ID'=>'xsd:int',
            'BD'=>'xsd:string'
        ),
        array('return'=> 'tns:MuestraHoraInicioFinCompetenciaCajasArray'),
        $namespace,
        false,
        'rpc',
        false,
        'Muestra la hora de inicio y fin de la tarea de competencia de cajas');
        
        $server->register(
            'ActualizarHoraInicioFinCompetenciaCajas',
            array(
                'TIPO'=>'xsd:string',
                'HORA'=>'xsd:string',
                'COMPETENCIA_CAJAS_ID'=>'xsd:int',
                'USARIO_MODIFICACION'=>'xsd:string',
                'FECHA_HORA_MODIFICACION'=>'xsd:string',
                'BD'=>'xsd:string'
                ),
            array('return'=>'xsd:boolean'),
            $namespace,
            false,
            'rpc',
            false,
            'Actualiza la hora de inicio y la hora de fin de competencia de cajas');