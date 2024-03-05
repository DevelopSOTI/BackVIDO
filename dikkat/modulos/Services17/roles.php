<?php
// <editor-fold defaultstate="collapsed" desc="Inserta los roles del sistema">
    function InsertarRol($DESCRIPCION,$CLAVE,$USUARIO_CREADOR,$FECHA_HORA_CREACION){
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = false;
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                    // <editor-fold defaultstate="collapsed" desc="INSERSION DE ROLES">                 
                      $query   ="INSERT INTO ROLES (DESCRIPCION,CLAVE,USUARIO_CREADOR,FECHA_HORA_CREACION)";
                      $query  .= "VALUES ('$DESCRIPCION','$CLAVE','$USUARIO_CREADOR','$FECHA_HORA_CREACION')";                 
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

 // <editor-fold defaultstate="collapsed" desc="Actualiza el rol en el sistema">
 function ActualizaRol($ROL_ID,$DESCRIPCION,$CLAVE,$USUARIO_MODIFICACION,$FECHA_HORA_MODIFICACION){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DEL CLIENTE">                 
                  $query  = "UPDATE ROLES ";
                   $query  = "SET ";
                   $query  = "DESCRIPCION = '$DESCRIPCION', ";
                   $query  = "CLAVE = '$CLAVE', ";
                   $query  = "USUARIO_MODIFICACION = '$USUARIO_MODIFICACION', ";
                   $query  = "FECHA_HORA_MODIFICACION ='$FECHA_HORA_MODIFICACION' ";
                   $query  = "WHERE ROL_ID = '$ROL_ID';";                 
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
 // <editor-fold defaultstate="collapsed" desc="Muestra los datos del rol del sistema">
 function MostrarRoles($ROL_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){  
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DEL ROL EN EL SISTEMA">                 
                  $select  = "SELECT ROL_ID, CLAVE, DESCRIPCION, USUARIO_CREADOR, FECHA_HORA_CREACION, USUARIO_MODIFICACION, FECHA_HORA_MODIFICACION FROM ROLES";
                  if($ROL_ID>0){
                    $select  .= " WHERE ROL_ID=$ROL_ID;";  
                  }               
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
 
    
    $server->register(
        'InsertarRol',
        array(
            'DESCRIPCION'=>'xsd:string',
            'CLAVE'=>'xsd:string',
            'USUARIOCREADOR'=>'xsd:string',
            'FECHACREACION'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta rol en el sistema');

    $server->register(
        'ActualizarRol',
        array(
            'ROL_ID'=>'xsd:int',
            'DESCRIPCION'=>'xsd:string',
            'CLAVE'=>'xsd:string',
            'USUARIOMODIFICACION'=>'xsd:string',
            'FECHAMODIFICACION'=>'xsd:string'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Actualiza el rol indicado en el sistema');


        $server->wsdl->addComplexType(
            'MostrarRoles',
            'complexType',
            'struct',
            'all',
            '',
            array(
                'ROL_ID' => array('name'=>'ROL_ID','type'=>'xsd:int'),
                'DESCRIPCION' => array('name'=>'DESCRIPCION','type'=>'xsd:string'),
                'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string'),
                'USUARIO_CREADOR' => array('name'=>'USUARIO_CREADOR','type'=>'xsd:string'),
                'FECHA_HORA_CREACION' => array('name'=>'FECHA_HORA_CREACION','type'=>'xsd:string'),
                'USUARIO_MODIFICACION' => array('name'=>'USUARIO_MODIFICACION','type'=>'xsd:string'),
                'FECHA_HORA_MODIFICACION' => array('name'=>'FECHA_HORA_MODIFICACION','type'=>'xsd:string')
            )
        );    
        $server->wsdl->addComplexType(
            'MostrarRolesArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarRoles[]')),
            'tns:MostrarRoles'
            );
        $server->register(
            'MostrarRoles',
            array('ROL_ID'=>'xsd:int'),
            array('return'=> 'tns:MostrarRolesArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con los datos de los roles');