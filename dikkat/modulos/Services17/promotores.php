<?php header("Content-Type: text/html;charset=utf-8");
// <editor-fold defaultstate="collapsed" desc="Inserta el promotor en la base de datos">
function InsertarPromotor($NOMBRE,$FECHA_NACIMIENTO,$RFC,$NSS,$PROVEEDOR_ID,$CATEGORIA_PROMOTOR_ID,$TELEFONO,$CORREO,$PROMOTOR_JEFE,$CLAVE){
    $conn = ABRIR_CONEXION_MYSQLI(FALSE);
    $result = false;
    if ($conn){ 
                // <editor-fold defaultstate="collapsed" desc="INSERSION EL PROMOTOR EN LA BASE DE DATOS">
                $PromotorJefe="";      
                
                if(is_null($PROMOTOR_JEFE) || $PROMOTOR_JEFE===0){
                    $PromotorJefe="NULL";    
                }       else {
                    $PromotorJefe= utf8_converter($PROMOTOR_JEFE);    
                }
                  //$query  =" INSERT INTO PROMOTORES(NOMBRE,FECHA_NACIMIENTO,RFC,NSS,PROVEEDOR_ID,CATEGORIA_PROMOTOR_ID,TELEFONO,CORREO,PROMOTOR_JEFE,CLAVE)";
                  //$query .=" VALUES('$NOMBRE','$FECHA_NACIMIENTO','$RFC','$NSS',$PROVEEDOR_ID,$CATEGORIA_PROMOTOR_ID,'$TELEFONO','$CORREO','$PromotorJefe','$CLAVE');";
                  $query  = " INSERT INTO PROMOTORES(NOMBRE,FECHA_NACIMIENTO,RFC,NSS,PROVEEDOR_ID,CATEGORIA_PROMOTOR_ID,TELEFONO,CORREO,PROMOTOR_JEFE,CLAVE) ";
                  $query .= " VALUES (?,?,?,?,?,?,?,?,?,?)";
                $conn->set_charset("utf-8");
                 $stmt=$conn->prepare($query);
                 $stmt->bind_param('sssiiissss',$NOMBRE,$FECHA_NACIMIENTO,$RFC,$NSS,$PROVEEDOR_ID,$CATEGORIA_PROMOTOR_ID,$TELEFONO,$CORREO,$PromotorJefe,$CLAVE);
                 mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);                       
                // </editor-fold>
                //echo $query;
                if($stmt->execute()===true){
                    $result = true;
                    mysqli_commit($conn);
                }else{
                    mysqli_rollback($conn);
                    $result = false;
                }
                $stmt->close();
                //if (mysqli_query($conn, $query)){
                //    $result = true;
                //    mysqli_commit($conn);
                //}
                //else{
                //    mysqli_rollback($conn);
                //    $result = false;
                //}
        mysqli_close($conn);
    }
    else {
        // FALLO LA CONEXION
        $result = false;
    }
return $result;
}
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Actualiza los datos del promotor">
function ActualizaPromotor($PROMOTOR_ID,$NOMBRE,$FECHA_NACIMIENTO,$RFC,$NSS,$PROVEEDOR_ID,$CATEGORIA_PROMOTOR_ID,$TELEFONO,$CORREO,$PROMOTOR_JEFE,$CLAVE){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE lOS DATOS DEL PROMOTOR">                 
                  $query  = " UPDATE PROMOTORES ";
                  $query .= " SET ";
                  $query .= " NOMBRE = '$NOMBRE', ";
                  $query .= " FECHA_NACIMIENTO = '$FECHA_NACIMIENTO', ";
                  $query .= " RFC = '$RFC', ";
                  $query .= " NSS = $NSS, ";
                  $query .= " PROVEEDOR_ID = $PROVEEDOR_ID, ";
                  $query .= " CATEGORIA_PROMOTOR_ID = $CATEGORIA_PROMOTOR_ID, ";
                  $query .= " TELEFONO = '$TELEFONO', ";
                  $query .= " CORREO = '$CORREO', ";
                  $query .= " PROMOTOR_JEFE = '$PROMOTOR_JEFE', ";
                  $query .= " CLAVE = '$CLAVE' ";
                  $query .= " WHERE PROMOTOR_ID =$PROMOTOR_ID;";                 
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
function MostrarPromotoresProveedor($PROVEEDOR_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result=null;
    if ($conn){                             
            $select  =" SELECT P.PROMOTOR_ID, P.NOMBRE, P.FECHA_NACIMIENTO, P.RFC, P.NSS, P.CATEGORIA_PROMOTOR_ID, P.PROVEEDOR_ID ";
            $select .=" , P.TELEFONO, P.PROMOTOR_JEFE, P.CORREO, P.CLAVE  ";
            $select .=" FROM PROMOTORES AS P ";
            $select .=" WHERE P.PROVEEDOR_ID=$PROVEEDOR_ID;";  
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                 $promotor["PROMOTOR_ID"] = $row["PROMOTOR_ID"];
                 $promotor["NOMBRE"] = $row["NOMBRE"];
                 $promotor["FECHA_NACIMIENTO"] = $row["FECHA_NACIMIENTO"];
                 $promotor["RFC"] = $row["RFC"]; 
                 $promotor["NSS"] = $row["NSS"];
                 $promotor["CATEGORIA_PROMOTOR_ID"] = $row["CATEGORIA_PROMOTOR_ID"];
                 $promotor["PROVEEDOR_ID"] = $row["PROVEEDOR_ID"];
                 $promotor["TELEFONO"] = $row["TELEFONO"];
                 $promotor["PROMOTOR_JEFE"] = $row["PROMOTOR_JEFE"];
                 $promotor["CORREO"] = $row["CORREO"];
                 $promotor["CLAVE"] = $row["CLAVE"];
                 $result[]=$promotor;
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
function MostrarPromotorProveedor($PROMOTOR_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result=null;
    if ($conn){                             
            $select  =" SELECT P.PROMOTOR_ID, P.NOMBRE, P.FECHA_NACIMIENTO, P.RFC, P.NSS, P.CATEGORIA_PROMOTOR_ID, P.PROVEEDOR_ID ";
            $select .=" , P.TELEFONO, P.PROMOTOR_JEFE, P.CORREO, P.CLAVE  ";
            $select .=" FROM PROMOTORES AS P ";
            $select .=" WHERE P.PROMOTOR_ID=$PROMOTOR_ID;";  
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                 $promotor["PROMOTOR_ID"] = $row["PROMOTOR_ID"];
                 $promotor["NOMBRE"] = $row["NOMBRE"];
                 $promotor["FECHA_NACIMIENTO"] = $row["FECHA_NACIMIENTO"];
                 $promotor["RFC"] = $row["RFC"]; 
                 $promotor["NSS"] = $row["NSS"];
                 $promotor["CATEGORIA_PROMOTOR_ID"] = $row["CATEGORIA_PROMOTOR_ID"];
                 $promotor["PROVEEDOR_ID"] = $row["PROVEEDOR_ID"];
                 $promotor["TELEFONO"] = $row["TELEFONO"];
                 $promotor["PROMOTOR_JEFE"] = $row["PROMOTOR_JEFE"];
                 $promotor["CORREO"] = $row["CORREO"];
                 $promotor["CLAVE"] = $row["CLAVE"];
                 $result[]=$promotor;
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
function MostrarPromotorProveedorClave($CLAVE){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result=null;
    if ($conn){                             
            $select  =" SELECT P.PROMOTOR_ID, P.NOMBRE, P.FECHA_NACIMIENTO, P.RFC, P.NSS, P.CATEGORIA_PROMOTOR_ID, P.PROVEEDOR_ID ";
            $select .=" , P.TELEFONO, P.PROMOTOR_JEFE, P.CORREO, P.CLAVE  ";
            $select .=" FROM PROMOTORES AS P ";
            $select .=" WHERE P.CLAVE='$CLAVE';";  
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                 $promotor["PROMOTOR_ID"] = $row["PROMOTOR_ID"];
                 $promotor["NOMBRE"] = $row["NOMBRE"];
                 $promotor["FECHA_NACIMIENTO"] = $row["FECHA_NACIMIENTO"];
                 $promotor["RFC"] = $row["RFC"]; 
                 $promotor["NSS"] = $row["NSS"];
                 $promotor["CATEGORIA_PROMOTOR_ID"] = $row["CATEGORIA_PROMOTOR_ID"];
                 $promotor["PROVEEDOR_ID"] = $row["PROVEEDOR_ID"];
                 $promotor["TELEFONO"] = $row["TELEFONO"];
                 $promotor["PROMOTOR_JEFE"] = $row["PROMOTOR_JEFE"];
                 $promotor["CORREO"] = $row["CORREO"];
                 $promotor["CLAVE"] = $row["CLAVE"];
                 $result[]=$promotor;
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
function MostrarPromotorProveedorNombre($NOMBRE){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result=null;
    if ($conn){                             
            $select  =" SELECT P.PROMOTOR_ID, P.NOMBRE, P.FECHA_NACIMIENTO, P.RFC, P.NSS, P.CATEGORIA_PROMOTOR_ID, P.PROVEEDOR_ID ";
            $select .=" , P.TELEFONO, P.PROMOTOR_JEFE, P.CORREO, P.CLAVE  ";
            $select .=" FROM PROMOTORES AS P ";
            $select .=" WHERE P.NOMBRE like '%$NOMBRE%';";  
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                 $promotor["PROMOTOR_ID"] = $row["PROMOTOR_ID"];
                 $promotor["NOMBRE"] = $row["NOMBRE"];
                 $promotor["FECHA_NACIMIENTO"] = $row["FECHA_NACIMIENTO"];
                 $promotor["RFC"] = $row["RFC"]; 
                 $promotor["NSS"] = $row["NSS"];
                 $promotor["CATEGORIA_PROMOTOR_ID"] = $row["CATEGORIA_PROMOTOR_ID"];
                 $promotor["PROVEEDOR_ID"] = $row["PROVEEDOR_ID"];
                 $promotor["TELEFONO"] = $row["TELEFONO"];
                 $promotor["PROMOTOR_JEFE"] = $row["PROMOTOR_JEFE"];
                 $promotor["CORREO"] = $row["CORREO"];
                 $promotor["CLAVE"] = $row["CLAVE"];
                 $result[]=$promotor;
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
function MostrarPromotorProveedorRFC($RFC){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result=null;
    if ($conn){                             
            $select  =" SELECT P.PROMOTOR_ID, P.NOMBRE, P.FECHA_NACIMIENTO, P.RFC, P.NSS, P.CATEGORIA_PROMOTOR_ID, P.PROVEEDOR_ID ";
            $select .=" , P.TELEFONO, P.PROMOTOR_JEFE, P.CORREO, P.CLAVE  ";
            $select .=" FROM PROMOTORES AS P ";
            $select .=" WHERE P.RFC = '$RFC';";  
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                 $promotor["PROMOTOR_ID"] = $row["PROMOTOR_ID"];
                 $promotor["NOMBRE"] = $row["NOMBRE"];
                 $promotor["FECHA_NACIMIENTO"] = $row["FECHA_NACIMIENTO"];
                 $promotor["RFC"] = $row["RFC"]; 
                 $promotor["NSS"] = $row["NSS"];
                 $promotor["CATEGORIA_PROMOTOR_ID"] = $row["CATEGORIA_PROMOTOR_ID"];
                 $promotor["PROVEEDOR_ID"] = $row["PROVEEDOR_ID"];
                 $promotor["TELEFONO"] = $row["TELEFONO"];
                 $promotor["PROMOTOR_JEFE"] = $row["PROMOTOR_JEFE"];
                 $promotor["CORREO"] = $row["CORREO"];
                 $promotor["CLAVE"] = $row["CLAVE"];
                 $result[]=$promotor;
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

function MostrarJefesPromotores($SUCURSAL_ID,$CLAVE_PROVEEDOR,$BD){
    $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
    $result=null;
    if ($conn){                             
            $select  =" SELECT P.PROMOTOR_JEFE FROM PROMOTORES AS P ";
            $select .=" INNER JOIN PROVEEDORES AS PR ON P.PROVEEDOR_ID=PR.PROVEEDOR_ID ";
            $select .=" INNER JOIN PROVEEDORES_CLIENTES AS PC ON PR.PROVEEDOR_ID = PC.PROVEEDOR_ID ";
            $select .=" INNER JOIN CLIENTES AS C ON PC.CLIENTE_ID=C.CLIENTE_ID ";
            $select .=" INNER JOIN SUCURSALES AS S ON C.CLIENTE_ID=S.CLIENTE_ID ";
            $select .=" WHERE ";
            $select .=" (P.PROMOTOR_JEFE IS NOT NULL OR LENGTH(P.PROMOTOR_JEFE)>0) ";
            $select .=" AND ( S.SUCURSAL_ID =$SUCURSAL_ID) ";
            $select .=" AND (PC.CLAVE ='$CLAVE_PROVEEDOR');";  
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
             while ($row = mysqli_fetch_assoc($stmt)){
                 $promotor["PROMOTOR_JEFE"] = $row["PROMOTOR_JEFE"];
                 $result[]=$promotor;
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
function ExisteClavePromotor($CLAVE){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result=0;
    if ($conn){                             
            $select  ="select PROMOTOR_ID from soticomm_INMEX.PROMOTORES WHERE CLAVE ='$CLAVE';";  
            
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $promotor= $row["PROMOTOR_ID"];
            }
            if($promotor>0){
                $result=1;
            }
            else{
                $result=0;
            }     
            mysqli_close($conn);
        }
        else{            
            mysqli_close($conn);
            return -1;
        }
    }
    else {
        // FALLO LA CONEXION
        return -1;
    }
    return $result;
}

$server->register(
    'InsertarPromotor',
    array(
        'NOMBRE'=>'xsd:string',
        'FECHA_NACIMIENTO'=>'xsd:string',
        'RFC'=>'xsd:string',
        'NSS'=>'xsd:int',
        'PROVEEDOR_ID'=>'xsd:int',
        'CATEGORIA_PROMOTOR_ID'=>'xsd:int',
        'TELEFONO'=>'xsd:string',
        'CORREO'=>'xsd:string',
        'PROMOTOR_JEFE'=>'xsd:string',
        'CLAVE'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Inserta el promotor en la base de datos');

    $server->register(
        'ActualizaPromotor',
        array(
            'PROMOTOR_ID'=>'xsd:int',
            'NOMBRE'=>'xsd:string',
            'FECHA_NACIMIENTO'=>'xsd:string',
            'RFC'=>'xsd:string',
            'NSS'=>'xsd:int',
            'PROVEEDOR_ID'=>'xsd:int',
            'CATEGORIA_PROMOTOR_ID'=>'xsd:int',
            'TELEFONO'=>'xsd:string',
            'CORREO'=>'xsd:string',
            'PROMOTOR_JEFE'=>'xsd:string',
            'CLAVE'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza los datos del promotor');

        $server->wsdl->addComplexType(
            'MostrarJefesPromotores',
            'complexType',
            'struct',
            'all',
            '',
            array(

                'PROMOTOR_JEFE' => array('name'=>'PROMOTOR_JEFE','type'=>'xsd:string')
            )
        );    
    $server->wsdl->addComplexType(
        'MostrarJefesPromotoresArray',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarJefesPromotores[]')),
        'tns:MostrarJefesPromotores'
        );

    $server->register(
        'MostrarJefesPromotores',
        array(
            'SUCURSAL_ID'=>'xsd:int',
            'CLAVE_PROVEEDOR'=>'xsd:string'
        ),
        array('return'=> 'tns:MostrarJefesPromotoresArray'),
        $namespace,
        false,
        'rpc',
        false,
        'Devuelve un arreglo con los datos de los jefes de los promotores del proveedor especificado');

        $server->wsdl->addComplexType(
            'MostrarPromotorProveedor',
            'complexType',
            'struct',
            'all',
            '',
            array(

                'PROMOTOR_ID' => array('name'=>'PROMOTOR_ID','type'=>'xsd:int'),
                'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
                'FECHA_NACIMIENTO' => array('name'=>'FECHA_NACIMIENTO','type'=>'xsd:string'),
                'RFC' => array('name'=>'RFC','type'=>'xsd:string'),
                'NSS' => array('name'=>'NSS','type'=>'xsd:int'),
                'CATEGORIA_PROMOTOR_ID' => array('name'=>'CATEGORIA_PROMOTOR_ID','type'=>'xsd:int'),
                'PROVEEDOR_ID' => array('name'=>'PROVEEDOR_ID','type'=>'xsd:int'),
                'TELEFONO' => array('name'=>'TELEFONO','type'=>'xsd:string'),
                'PROMOTOR_JEFE' => array('name'=>'PROMOTOR_JEFE','type'=>'xsd:string'),
                'CORREO' => array('name'=>'CORREO','type'=>'xsd:string'),
                'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string')
            )
        );    
    $server->wsdl->addComplexType(
        'MostrarPromotorProveedorArray',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarPromotorProveedor[]')),
        'tns:MostrarPromotorProveedor'
        );

    $server->register(
        'MostrarPromotorProveedor',
        array(
            'PROMOTOR_ID'=>'xsd:int'
        ),
        array('return'=> 'tns:MostrarPromotorProveedorArray'),
        $namespace,
        false,
        'rpc',
        false,
        'Devuelve un arreglo con los datos de los promotores del proveedor especificado por ID');
    $server->register(
        'MostrarPromotorProveedorClave',
        array(
            'CLAVE'=>'xsd:string'
        ),
        array('return'=> 'tns:MostrarPromotorProveedorArray'),
        $namespace,
        false,
        'rpc',
        false,
        'Devuelve un arreglo con los datos del promotor especificado por clave');

    $server->register(
        'MostrarPromotorProveedorNombre',
        array(
            'NOMBRE'=>'xsd:string'
        ),
        array('return'=> 'tns:MostrarPromotorProveedorArray'),
        $namespace,
        false,
        'rpc',
        false,
        'Devuelve un arreglo con los datos del promotor especificado por Nombre');
    $server->register(
        'MostrarPromotorProveedorRFC',
        array(
            'RFC'=>'xsd:string'
        ),
        array('return'=> 'tns:MostrarPromotorProveedorArray'),
        $namespace,
        false,
        'rpc',
        false,
        'Devuelve un arreglo con los datos del promotor especificado por RFC');
        $server->register(
            'MostrarPromotoresProveedor',
            array(
                'PROVEEDOR_ID'=>'xsd:int'
            ),
            array('return'=> 'tns:MostrarPromotorProveedorArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con los datos de los promotores del proveedor ID especificado');

        

        function MostrarCategoriasPromotores(){
            $conn = ABRIR_CONEXION_MYSQL(FALSE);
            $result=null;
            if ($conn){                             
                    $select  ="SELECT CATEGORIA_PROMOTOR_ID, NOMBRE, CLAVE FROM CATEGORIAS_PROMOTOR";  
                    
                $stmt = mysqli_query($conn, $select);            
        
                if ($stmt){
                     while ($row = mysqli_fetch_assoc($stmt)){
                         $categorias["CATEGORIA_PROMOTOR_ID"] = $row["CATEGORIA_PROMOTOR_ID"];
                         $categorias["NOMBRE"] = $row["NOMBRE"];
                         $categorias["CLAVE"] = $row["CLAVE"];
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
        
        $server->wsdl->addComplexType(
            'MostrarCategoriasPromotores',
            'complexType',
            'struct',
            'all',
            '',
            array(

                'CATEGORIA_PROMOTOR_ID' => array('name'=>'CATEGORIA_PROMOTOR_ID','type'=>'xsd:int'),
                'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),                
                'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string')
            )
        );    
        $server->wsdl->addComplexType(
            'MostrarCategoriasPromotoresArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarCategoriasPromotores[]')),
            'tns:MostrarCategoriasPromotores'
            );
    
        $server->register(
            'MostrarCategoriasPromotores',
            array(),
            array('return'=> 'tns:MostrarCategoriasPromotoresArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con las categorias de los promotores');
        $server->register(
            'ExisteClavePromotor',
            array('CLAVE'=>'xsd:string'),
            array('return'=>'xsd:int'),
            $namespace,
            false,
            'rpc',
            false,
            'Verifica si existe la clave de promotor en la base de datos');
