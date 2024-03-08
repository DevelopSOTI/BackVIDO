<?php
// <editor-fold defaultstate="collapsed" desc="Inserta las sucursales del sistema">
    function InsertarSucursal($CLIENTE_ID,$NOMBRE,$DIR_SUC_ID,$GEO_CERCA,$UBICACION,$REFERENCIA,$USUARIO_CREADOR,$FECHA_HORA_CREACION){
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = false;
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                    // <editor-fold defaultstate="collapsed" desc="INSERSION DE SUCURSALES">                 
                      $query  ="INSERT INTO SUCURSALES ";
                      $query  .="( ";
                      $query  .="CLIENTE_ID, ";
                      $query  .="NOMBRE, ";
                      $query  .="DIR_SUC_ID, ";
                      $query  .="GEO_CERCA, ";
                      $query  .="UBICACION, ";
                      $query  .="REFERENCIA, ";
                      $query  .="ESTATUS, ";
                      $query  .="USUARIO_CREADOR, ";
                      $query  .="FECHA_HORA_CREACION, ";
                      $query  .="USUARIO_MODIFICACION, ";
                      $query  .="FECHA_HORA_MODIFICACION) ";
                      $query  .="VALUES ";
                      $query  .="( ";
                      $query  .=" $CLIENTE_ID, ";
                      $query  .="'$NOMBRE', ";
                      $query  .=" $DIR_SUC_ID, ";
                      $query  .="'$GEO_CERCA', ";
                      $query  .="'$UBICACION', ";
                      $query  .="'$REFERENCIA', ";
                      $query  .="'A', ";
                      $query  .="'$USUARIO_CREADOR', ";
                      $query  .="'$FECHA_HORA_CREACION', ";
                      $query  .="NULL, ";
                      $query  .="NULL);";                 
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
// <editor-fold defaultstate="collapsed" desc="Actualiza las sucursales del sistema">
function ActualizaSucursal($SUCURSAL_ID,$CLIENTE_ID,$NOMBRE,$DIR_SUC_ID,$GEO_CERCA,$UBICACION,$REFERENCIA,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE LAS SUCURSALES">                 
                  $query  = "UPDATE SUCURSALES ";
                  $query  .=" SET ";
                  $query  .=" CLIENTE_ID =$CLIENTE_ID, ";
                  $query  .=" NOMBRE = '$NOMBRE', ";
                  $query  .=" DIR_SUC_ID = $DIR_SUC_ID, ";
                  $query  .=" GEO_CERCA = '$GEO_CERCA', ";
                  $query  .=" UBICACION = '$UBICACION', ";
                  $query  .=" REFERENCIA = '$REFERENCIA', ";
                  $query  .=" USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
                  $query  .=" FECHA_HORA_MODIFICACION = '$FECHA_HORA_MODIFICACION' ";
                  $query  .=" WHERE SUCURSAL_ID = $SUCURSAL_ID;";                 
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
    // <editor-fold defaultstate="collapsed" desc="Muestra todas las sucursales por cliente">
    function MostrarSucursales($CLIENTE_ID){
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = false;
        if ($conn){  
                    // <editor-fold defaultstate="collapsed" desc="SELECCION DE TODOS LAS SUCURSALES POR CLIENTE">                 
                      $select  = "SELECT SUCURSAL_ID, CLIENTE_ID, DIR_SUC_ID, NOMBRE, GEO_CERCA, UBICACION, REFERENCIA, ESTATUS FROM SUCURSALES WHERE CLIENTE_ID =$CLIENTE_ID;";                 
                    // </editor-fold>
                    
                        $stmt = mysqli_query($conn, $select);            

                        if ($stmt){
                            mysqli_close($conn);
                            return $stmt->fetch_all(MYSQLI_ASSOC); 
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
 // <editor-fold defaultstate="collapsed" desc="Muestra el nombre de la sucursal">
 function MostrarNombreSucursal($SUCURSAL_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = "";
    if ($conn){  
    // <editor-fold defaultstate="collapsed" desc="MUESTRA EL NOMBRE DE LA SUCURSAL">                 
        $select  = "SELECT NOMBRE FROM SUCURSALES WHERE SUCURSAL_ID =$SUCURSAL_ID;";                 
    // </editor-fold>
    
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $sucursal= $row["NOMBRE"];
                $result=$sucursal;
            }     
            mysqli_close($conn);
        }
        else{
            mysqli_close($conn);
        }  
    }
    else {
        // FALLO LA CONEXION
    }
    return $result;
}
// </editor-fold>
 // <editor-fold defaultstate="collapsed" desc="Muestra el nombre de la sucursal">
 function MostrarProveedoresSucursal($SUCURSAL_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    if ($conn){  
    // <editor-fold defaultstate="collapsed" desc="MUESTRA EL NOMBRE DE LA SUCURSAL">                 
        $select  = "SELECT P.PROVEEDOR_ID,PC.CLAVE,P.NOMBRE ";
        $select .= " FROM  soticomm_INMEX.PROVEEDORES_CLIENTES AS PC ";
        $select .= " INNER JOIN soticomm_INMEX.SUCURSALES  AS S ON PC.CLIENTE_ID=S.CLIENTE_ID ";
        $select .= " INNER JOIN soticomm_INMEX.PROVEEDORES AS P ON PC.PROVEEDOR_ID=P.PROVEEDOR_ID ";
        $select .= " WHERE ";
        $select .= " S.SUCURSAL_ID=$SUCURSAL_ID;";                 
    // </editor-fold>
    
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $proveedores["PROVEEDOR_ID"]    = $row["PROVEEDOR_ID"];
                $proveedores["CLAVE"]           = $row["CLAVE"];
                $proveedores["NOMBRE"]          = $row["NOMBRE"];
                $result[]=$proveedores;
            }     
            mysqli_close($conn);
        }
        else{
            mysqli_close($conn);
        }  
    }
    else {
        // FALLO LA CONEXION
    }
    return $result;
}
// </editor-fold>

    $server->register(
        'InsertarSucursal',
        array(
            'CLIENTE_ID'=>'xsd:int',
            'NOMBRE'=>'xsd:string',
            'DIR_SUC_ID'=>'xsd:int',
            'GEO_CERCA'=>'xsd:string',
            'UBICACION'=>'xsd:string',
            'REFERENCIA'=>'xsd:string',
            'USUARIO_CREADOR'=>'xsd:string',
            'FECHA_HORA_CREACION'=>'xsd:string',
            'BD'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta la sucursal en el sistema');

        $server->register(
            'ActualizaSucursal',
            array(
                'USUARIOID'=>'xsd:int',
                'NOMBRE'=>'xsd:string',
                'APELLIDOP'=>'xsd:string',
                'APELLIDOM'=>'xsd:string',
                'USUARIO'=>'xsd:string',
                'PASS'=>'xsd:string',
                'ESTATUS'=>'xsd:string',
                'USUARIOMODIFICACION'=>'xsd:string',
                'FECHAMODIFICACION'=>'xsd:string',
                'BD'=>'xsd:string'
                ),
            array('return'=>'xsd:boolean'),
            $namespace,
            false,
            'rpc',
            false,
            'Actualiza la sucursal indicada en el sistema');

            $server->wsdl->addComplexType(
                'MostrarSucursales',
                'complexType',
                'struct',
                'all',
                '',
                array(
                    'SUCURSAL_ID' => array('name'=>'SUCURSAL_ID','type'=>'xsd:int'),
                    'CLIENTE_ID' => array('name'=>'CLIENTE_ID','type'=>'xsd:int'),
                    'DIR_SUC_ID' => array('name'=>'DIR_SUC_ID','type'=>'xsd:int'),
                    'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
                    'GEO_CERCA' => array('name'=>'GEO_CERCA','type'=>'xsd:string'),
                    'UBICACION' => array('name'=>'UBICACION','type'=>'xsd:string'),
                    'REFERENCIA' => array('name'=>'REFERENCIA','type'=>'xsd:string'),
                    'ESTATUS' => array('name'=>'ESTATUS','type'=>'xsd:string')
                )
            );    
            $server->wsdl->addComplexType(
                'MostrarSucursalesArray',
                'complexType',
                'array',
                '',
                'SOAP-ENC:Array',
                array(),
                array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarSucursales[]')),
                'tns:MostrarSucursales'
                );
                $server->register(
                    'MostrarSucursales',
                    array('CLIENTE_ID'=>'xsd:int'),
                    array('return'=> 'tns:MostrarSucursalesArray'),
                    $namespace,
                    false,
                    'rpc',
                    false,
                    'Devuelve un arreglo con las sucursales de un cliente especificado');

                    $server->register(
                        'MostrarNombreSucursal',
                        array(
                            'SUCURSAL_ID'=>'xsd:int'
                            ),
                        array('return'=>'xsd:string'),
                        $namespace,
                        false,
                        'rpc',
                        false,
                        'Muestra el nombre de la sucursal');

$server->wsdl->addComplexType(
                'MostrarSucursales',
                'complexType',
                'struct',
                'all',
                '',
                array(
                    'SUCURSAL_ID' => array('name'=>'SUCURSAL_ID','type'=>'xsd:int'),
                    'CLIENTE_ID' => array('name'=>'CLIENTE_ID','type'=>'xsd:int'),
                    'DIR_SUC_ID' => array('name'=>'DIR_SUC_ID','type'=>'xsd:int'),
                    'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
                    'GEO_CERCA' => array('name'=>'GEO_CERCA','type'=>'xsd:string'),
                    'UBICACION' => array('name'=>'UBICACION','type'=>'xsd:string'),
                    'REFERENCIA' => array('name'=>'REFERENCIA','type'=>'xsd:string'),
                    'ESTATUS' => array('name'=>'ESTATUS','type'=>'xsd:string')
                )
            );    
            $server->wsdl->addComplexType(
                'MostrarSucursalesArray',
                'complexType',
                'array',
                '',
                'SOAP-ENC:Array',
                array(),
                array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarSucursales[]')),
                'tns:MostrarSucursales'
                );
                $server->register(
                    'MostrarSucursales',
                    array('CLIENTE_ID'=>'xsd:int'),
                    array('return'=> 'tns:MostrarSucursalesArray'),
                    $namespace,
                    false,
                    'rpc',
                    false,
                    'Devuelve un arreglo con las sucursales de un cliente especificado');

        $server->wsdl->addComplexType(
            'MostrarProveedoresSucursal',
            'complexType',
            'struct',
            'all',
            '',
            array(
                'PROVEEDOR_ID' => array('name'=>'PROVEEDOR_ID','type'=>'xsd:int'),
                'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string'),
                'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string')
            )
        );    
        $server->wsdl->addComplexType(
            'MostrarProveedoresSucursalArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarProveedoresSucursal[]')),
            'tns:MostrarProveedoresSucursal'
            );
            $server->register(
                'MostrarProveedoresSucursal',

                array(
                    'SUCURSAL_ID'=>'xsd:int',
                    'BD'=>'xsd:string'
                ),
                array('return'=> 'tns:MostrarProveedoresSucursalArray'),
                $namespace,
                false,
                'rpc',
                false,
                'Devuelve un arreglo con los proveedores asignados a un cliente especificado');