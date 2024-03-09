<?php 
function BuscarBarridoID($FECHA,$SUCURSAL_ID,$conn){
    $result = 0;
    if ($conn){
        $select  = "SELECT BARRIDO_ID FROM BARRIDO WHERE FECHA='$FECHA' AND SUCURSAL_ID=$SUCURSAL_ID ;";
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $faltante=$row["BARRIDO_ID"];
                $result=$faltante;
            }
            return $result;
        }
        else{
            return 0; 
        }
    }
    else {
        return -1; 
    }
}
function BuscarBarridoDetalleID($BARRIDO_ID,$ARTICULO_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = 0;
    if ($conn){
        $select  = "SELECT FD.BARRIDO_DETALLE_ID FROM BARRIDO_DETALLE AS FD WHERE FD.BARRIDO_ID=$BARRIDO_ID AND FD.ARTICULO_ID=$ARTICULO_ID;";
        $stmt = mysqli_query($conn, $select);
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $faltante=$row["BARRIDO_DETALLE_ID"];
                $result=$faltante;
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
        return -1; 
    }
}
function InsertarBarrido($FECHA,$SUCURSAL_ID,$USUARIO_CREACION,$FECHA_HORA_CREACION,$ARTICULO_ID,$PRECIO, $BD/*,$PRECIO_ARTICULO*/){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = 0;
    $BARRIDO_ID=0;
    $BARRIDO_ID=BuscarBarridoID($FECHA,$SUCURSAL_ID,$conn);
    //echo "Barridos id". $BARRIDO_ID." ";
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        if($BARRIDO_ID===0||(
            /*($USUARIO_CREACION===0||$USUARIO_CREACION===null||strlen($USUARIO_CREACION)===0||is_null($USUARIO_CREACION) )&&
            ($FECHA_HORA_CREACION===0||$FECHA_HORA_CREACION===null||strlen($FECHA_HORA_CREACION)===0||is_null($FECHA_HORA_CREACION) )&&*/
            ($ARTICULO_ID===0||$ARTICULO_ID===null||strlen($ARTICULO_ID)===0||is_null($ARTICULO_ID) )&&
            ($PRECIO===0||$PRECIO===null||strlen($PRECIO)===0||is_null($PRECIO) )/*&&
            ($PRECIO_ARTICULO===0||$PRECIO_ARTICULO===null||strlen($PRECIO_ARTICULO)===0||is_null($PRECIO_ARTICULO))*/
            )){
            //insertamos el encabezado 
            $query   ="INSERT INTO BARRIDO (SUCURSAL_ID,ESTATUS,FECHA,USUARIO_CREACION,FECHA_HORA_CREACION) ";
            $query  .="VALUES ($SUCURSAL_ID,'A','$FECHA','$USUARIO_CREACION','$FECHA_HORA_CREACION');";
            //echo $query;
            if (mysqli_query($conn, $query)){
                $result = 1;
            }
            else{
                $result = 0;
            }
            if($result===1){
                $BARRIDO_ID=BuscarBarridoID($FECHA,$SUCURSAL_ID,$conn);
                //echo "Barridos id". $BARRIDO_ID." ";
            }
            else {
                $result=0;
            }
        }
        elseif($BARRIDO_ID===-1){
            $result=0;
        }
        $result=$BARRIDO_ID;
        //ECHO $BARRIDO_ID." - "."$USUARIO_CREACION-"."$FECHA_HORA_CREACION-"."$ARTICULO_ID-"."$PRECIO-"."$PRECIO_ARTICULO";
        if($BARRIDO_ID>0 &&(
            ( strlen($USUARIO_CREACION)>2)&&
            ( strlen($FECHA_HORA_CREACION)>2)&&
            ($ARTICULO_ID>0 )&&
            ($PRECIO>=0 )
            )){
            //buscamos el detalle
            $BARRIDO_DETALLE_ID=0;
            $BARRIDO_DETALLE_ID=BuscarBarridoDetalleID($BARRIDO_ID,$ARTICULO_ID, $BD);
            //echo " Barridos detalle_id: ".$BARRIDO_DETALLE_ID." ";
            if($BARRIDO_DETALLE_ID===0){
                //Insertamos el detalle
                $query   ="INSERT INTO BARRIDO_DETALLE (BARRIDO_ID,ARTICULO_ID,PRECIO/*,PRECIO_ARTICULO*/) ";
                
                $query   .=" VALUES($BARRIDO_ID,$ARTICULO_ID,$PRECIO)";
                //$query   =" VALUES($BARRIDO_ID,$ARTICULO_ID,$PRECIO /*,$PRECIO_ARTICULO*/)";

                //echo $query;
                if (mysqli_query($conn, $query)){
                    $result = $BARRIDO_ID;
                }
                else{
                    $result = 0;
                }
            }
            elseif($BARRIDO_DETALLE_ID===-1 || $BARRIDO_DETALLE_ID>0){
                $result=0;
            }
        }
        if ($result >0){                    
            mysqli_commit($conn);
        }
        else{
            mysqli_rollback($conn);
        }
        mysqli_close($conn);
    }
    else {
        $result = false;
    }
return $result;
}
function MostrarBarridos($FECHA,$SUCURSAL_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    $hostname=$_SERVER['SERVER_NAME'];
    if ($conn){
        $select  = "SELECT F.BARRIDO_ID,FD.BARRIDO_DETALLE_ID,A.ARTICULO_ID,A.SKU,A.NOMBRE,A.DESCRIPCION,FD.PRECIO/*,FD.PRECIO_ARTICULO*/,A.IMAGEN, D.NOMBRE AS CATEGORIA  ";
        $select .= " FROM BARRIDO AS F ";
        $select .= " INNER JOIN BARRIDO_DETALLE AS FD ON F.BARRIDO_ID =FD.BARRIDO_ID ";
        $select .= " INNER JOIN ARTICULOS AS A ON FD.ARTICULO_ID=A.ARTICULO_ID ";
        $select .= " INNER JOIN CATEGORIAS AS C ON A.CATEGORIA_ID=C.CATEGORIA_ID";
        $select .= " INNER JOIN DEPARTAMENTOS AS D ON C.DEPARTAMENTO_ID=D.DEPARTAMENTO_ID ";
        $select .= " WHERE F.FECHA='$FECHA' AND F.SUCURSAL_ID=$SUCURSAL_ID AND F.ESTATUS ='A' ORDER BY FD.BARRIDO_DETALLE_ID DESC;";
        $stmt = mysqli_query($conn, $select);
        //echo $select;
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $faltante["BARRIDO_ID"]         =$row["BARRIDO_ID"];
                $faltante["BARRIDO_DETALLE_ID"] =$row["BARRIDO_DETALLE_ID"];
                $faltante["ARTICULO_ID"]        =$row["ARTICULO_ID"];
                $faltante["SKU"]                =$row["SKU"];
                $faltante["NOMBRE"]             =$row["NOMBRE"];
                $faltante["DESCRIPCION"]        =$row["DESCRIPCION"];
                $faltante["PRECIO"]             =$row["PRECIO"];
                /*$faltante["PRECIO_ARTICULO"]    =$row["PRECIO_ARTICULO"];*/
                $faltante["IMAGEN"]             =$hostname."/articulos/".$row["IMAGEN"];
                $faltante["CATEGORIA"]          =$row["CATEGORIA"];
                $result[]=$faltante;
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
function MostrarArticulosBarrido($ARTICULO_ID,$MARCA_ID,$CATEGORIA_ID,$SKU, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    $where="";
    $hostname=$_SERVER['SERVER_NAME'];
    if ($conn){
        if($ARTICULO_ID>0)
        {
            $where=" WHERE ARTICULO_ID=$ARTICULO_ID";
        }
        elseif($SKU===0 || strlen($SKU)>0){
            $SKU = trim($SKU," \t\n\r");
            $where=" WHERE SKU='$SKU' AND ESTATUS='A';";
        }
        elseif($MARCA_ID>0 && $CATEGORIA_ID>0){
            $where=" WHERE MARCA_ID=$MARCA_ID AND CATEGORIA_ID=$CATEGORIA_ID;";
        }
        elseif($MARCA_ID>0 && $CATEGORIA_ID===0){
            $where=" WHERE MARCA_ID=$MARCA_ID;";
        }
        elseif($MARCA_ID===0 && $CATEGORIA_ID>0){
            $where=" WHERE CATEGORIA_ID=$CATEGORIA_ID;";
        }
        else{
            $where=";";
        }
        $select  = "SELECT ARTICULO_ID,MARCA_ID,NOMBRE,PRECIO,SKU,DESCRIPCION,IMAGEN,USUARIO_CREADOR,FECHA_HORA_CREACION ";
        $select .= " ,USUARIO_MODIFICACION,FECHA_HORA_MODIFICACION,CATEGORIA_ID ";
        $select .= " FROM ARTICULOS ".$where;
        $stmt = mysqli_query($conn, $select); 
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $roles["ARTICULO_ID"]               =$row["ARTICULO_ID"];
                $roles["MARCA_ID"]                  =$row["MARCA_ID"];
                $roles["NOMBRE"]                    =$row["NOMBRE"];
                $roles["PRECIO"]                    =$row["PRECIO"];
                $roles["SKU"]                       =$row["SKU"];
                $roles["DESCRIPCION"]               =$row["DESCRIPCION"];
                $roles["IMAGEN"]                    =$hostname."/articulos/".$row["IMAGEN"];
                $roles["USUARIO_CREADOR"]           =$row["USUARIO_CREADOR"];
                $roles["FECHA_HORA_CREACION"]       =$row["FECHA_HORA_CREACION"];
                $roles["USUARIO_MODIFICACION"]      =$row["USUARIO_MODIFICACION"];
                $roles["FECHA_HORA_MODIFICACION"]   =$row["FECHA_HORA_MODIFICACION"];
                $roles["CATEGORIA_ID"]              =$row["CATEGORIA_ID"];
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
function ActualizarHoraInicioFinBarridos($TIPO,$HORA,$BARRIDO_ID,$USARIO_MODIFICACION,$FECHA_HORA_MODIFICACION, $BD){
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
            $query  = "UPDATE BARRIDO ";
            $query .= " SET ";
            $query .= " $TIPO_ACTUALIZACION ";
            $query .= " USARIO_MODIFICACION = '$USARIO_MODIFICACION', ";
            $query .= " FECHA_HORA_MODIFICACION ='$FECHA_HORA_MODIFICACION' ";
            $query .= " WHERE BARRIDO_ID = $BARRIDO_ID $WHERE_COMPLEMENTO;";
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
    }
    return $result;
}
function ExisteBarridos($BARRIDO_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = false;
    $HORA_INICIO="";
    if ($conn){
        $select  = "SELECT HORA_INICIO FROM BARRIDO ";
        $select .= " WHERE BARRIDO_ID = $BARRIDO_ID;"; 
        $stmt = mysqli_query($conn, $select); 
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $HORA_INICIO=$row["HORA_INICIO"];
            }
            if(STRLEN($HORA_INICIO)>0){
                $result=true;
            }else {
                $result = false;
            }
        }else {
            $result = false;
        }
        mysqli_close($conn);
    }
    else {
        $result = false;
    }
    return $result;
}
function MuestraHoraInicioFinBarridos($BARRIDO_ID, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    if ($conn){
        $select  ="SELECT HORA_INICIO,HORA_FIN FROM BARRIDO ";
        $select .=" WHERE BARRIDO_ID = $BARRIDO_ID;";
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
        $result = null;
    }
    return $result;
}
function MuestraBitacoraBarrido($SUCURSAL_ID,$FECHA, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = "";
    if ($conn){
        $hostname=$_SERVER['SERVER_NAME'];
        $select  ="SELECT B.IMAGEN FROM BITACORA_BARRIDO AS B ";
        $select .=" INNER JOIN SUCURSALES AS S ON B.CLIENTE_ID=S.CLIENTE_ID ";
        $select .=" WHERE ";
        $select .=" CONVERT(B.FECHA_REVISION, DATE)='$FECHA' AND S.SUCURSAL_ID=$SUCURSAL_ID ";
        $stmt = mysqli_query($conn, $select);              
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $caducidades =$row["IMAGEN"];
                $result=$hostname."/".$caducidades;  
            }
        }else {
            $result = "";
        }
        mysqli_close($conn);
    }
    else {
        $result = "";
    }
    return $result;
}
function ExisteArticuloBarridos($SUCURSAL_ID,$FECHA,$SKU, $BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = "";
    $BARRIDO_DETALLE_ID="";
    $ARTICULO_ID="";
    if ($conn){
        $select=" select ARTICULO_ID from ARTICULOS where SKU='$SKU'";
        $stmt = mysqli_query($conn, $select); 
        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $ARTICULO_ID=$row["ARTICULO_ID"];
            }
            if(STRLEN($ARTICULO_ID)>0){
                $select  = " select fd.BARRIDO_DETALLE_ID from BARRIDO as f ";
                $select .= " inner join BARRIDO_DETALLE as fd on f.BARRIDO_ID=fd.BARRIDO_ID ";
                $select .= " inner join ARTICULOS as a on fd.ARTICULO_ID=a.ARTICULO_ID "; 
                $select .= " where f.SUCURSAL_ID=$SUCURSAL_ID and f.FECHA='$FECHA' and a.SKU='$SKU';"; 
                $stmt = mysqli_query($conn, $select); 
                if ($stmt){
                    while ($row = mysqli_fetch_assoc($stmt)){
                        $BARRIDO_DETALLE_ID=$row["BARRIDO_DETALLE_ID"];
                    }
                    if(STRLEN($BARRIDO_DETALLE_ID)>0){
                        $result="true";
                    }else {
                        $result = "false";
                    }
                }else {
                    $result = "Error al ejecutar la consulta";
                }
            }else {
                $result = "SKU $SKU no encontrado";
            }
        }
        mysqli_close($conn);
    }    
    else {
        $result = "Error al tratar de establecer la conexion a la base de datos";
    }
    return $result;
}
$server->register(
    'ExisteArticuloBarridos',
    array(
        'SUCURSAL_ID'=>'xsd:int',
        'FECHA'=>'xsd:string',
        'SKU'=>'xsd:string',
        'BD'=>'xsd:string'
        ),
    array('return'=>'xsd:string'),
    $namespace,
    false,
    'rpc',
    false,
    'Verifica si existe un articulo en el listado de barrido');
$server->register(
    'InsertarBarrido',
    array(
        'FECHA'=>'xsd:string',
        'SUCURSAL_ID'=>'xsd:int',
        'USUARIO_CREACION'=>'xsd:string',
        'FECHA_HORA_CREACION'=>'xsd:string',
        'ARTICULO_ID'=>'xsd:string',
        'PRECIO'=>'xsd:string'/*,
        'PRECIO_ARTICULO'=>'xsd:string'*/,
        'BD'=>'xsd:string'
        ),
    array('return'=>'xsd:int'),
    $namespace,
    false,
    'rpc',
    false,
    'Inserta un articulo de faltante en el sistema');   
$server->wsdl->addComplexType(
    'MostrarBarridos',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'BARRIDO_ID' => array('name'=>'BARRIDO_ID','type'=>'xsd:int'),            
        'BARRIDO_DETALLE_ID' => array('name'=>'BARRIDO_DETALLE_ID','type'=>'xsd:int'),
        'ARTICULO_ID' => array('name'=>'ARTICULO_ID','type'=>'xsd:int'),
        'SKU' => array('name'=>'SKU','type'=>'xsd:string'),
        'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
        'DESCRIPCION' => array('name'=>'DESCRIPCION','type'=>'xsd:string'),
        'PRECIO' => array('name'=>'PRECIO','type'=>'xsd:string'),
        /*'PRECIO_ARTICULO' => array('name'=>'PRECIO_ARTICULO','type'=>'xsd:string'),*/
        'IMAGEN' => array('name'=>'IMAGEN','type'=>'xsd:string'),
        'CATEGORIA' => array('name'=>'CATEGORIA','type'=>'xsd:string')
    )
);    
$server->wsdl->addComplexType(
    'MostrarBarridosArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarBarridos[]')),
    'tns:MostrarBarridos'
    );
$server->register(
    'MostrarBarridos',
    array(
        'FECHA'=>'xsd:string',
        'SUCURSAL_ID'=>'xsd:int',
        'BD'=>'xsd:string'
    ),
    array('return'=> 'tns:MostrarBarridosArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los articulos faltanes en el sistema en una fecha y sucursal determinada');
$server->wsdl->addComplexType(
    'MostrarArticulosBarrido',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'ARTICULO_ID' => array('name'=>'ARTICULO_ID','type'=>'xsd:int'),
        'MARCA_ID' => array('name'=>'MARCA_ID','type'=>'xsd:int'),
        'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
        'PRECIO' => array('name'=>'PRECIO','type'=>'xsd:string'),
        'SKU' => array('name'=>'SKU','type'=>'xsd:string'),
        'DESCRIPCION' => array('name'=>'DESCRIPCION','type'=>'xsd:string'),
        'IMAGEN' => array('name'=>'IMAGEN','type'=>'xsd:string'),
        'USUARIO_CREADOR' => array('name'=>'USUARIO_CREADOR','type'=>'xsd:string'),
        'FECHA_HORA_CREACION' => array('name'=>'FECHA_HORA_CREACION','type'=>'xsd:string'),
        'USUARIO_MODIFICACION' => array('name'=>'USUARIO_MODIFICACION','type'=>'xsd:string'),
        'FECHA_HORA_MODIFICACION' => array('name'=>'FECHA_HORA_MODIFICACION','type'=>'xsd:string'),
        'CATEGORIA_ID' => array('name'=>'CATEGORIA_ID','type'=>'xsd:string')
    )
);    
$server->wsdl->addComplexType(
    'MostrarArticulosBarridoArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarArticulosBarrido[]')),
    'tns:MostrarArticulosBarrido'
    );
$server->register(
    'MostrarArticulosBarrido',
    array(
        'ARTICULO_ID'=>'xsd:int',
        'MARCA_ID'=>'xsd:int',
        'CATEGORIA_ID'=>'xsd:int',
        'SKU'=>'xsd:string',
        'BD'=>'xsd:string'
    ),
    array('return'=> 'tns:MostrarArticulosBarridoArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los articulos del sistema (usando "0" en articulo id, marca id y categoría id muestra todos los articulos del sistema)');
        
$server->register(
    'ActualizarHoraInicioFinBarridos',
    array(
        'TIPO'=>'xsd:string',
        'HORA'=>'xsd:string',
        'BARRIDO_ID'=>'xsd:int',
        'USARIO_MODIFICACION'=>'xsd:string',
        'FECHA_HORA_MODIFICACION'=>'xsd:string',
        'BD'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Actualiza la hora de inicio y la hora de fin de la barrido');
    
$server->register(
    'ExisteBarridos',
    array(
        'BARRIDO_ID'=>'xsd:int',
        'BD'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Verifica si existe una tarea de barrido');
        
$server->wsdl->addComplexType(
    'MuestraHoraInicioFinBarridos',
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
    'MuestraHoraInicioFinBarridosArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MuestraHoraInicioFinBarridos[]')),
    'tns:MuestraHoraInicioFinBarridos'
    );
$server->register(
    'MuestraHoraInicioFinBarridos',
    array(
        'BARRIDO_ID'=>'xsd:int',
        'BD'=>'xsd:string'
    ),
    array('return'=> 'tns:MuestraHoraInicioFinBarridosArray'),
    $namespace,
    false,
    'rpc',
    false,
    'Muestra la hora de inicio y fin de la tarea de barrido');

$server->register(
    'MuestraBitacoraBarrido',
    array(
        'SUCURSAL_ID'=>'xsd:int',
        'FECHA'=>'xsd:string',
        'BD'=>'xsd:string'
    ),
    array('return'=> 'xsd:string'),
    $namespace,
    false,
    'rpc',
    false,
    'Muestra ruta de la imagen de la bitácora en el servidor');