<?php
// <editor-fold defaultstate="collapsed" desc="Inserta los usuarios del sistema">
    function InsertarUsuario($NOMBRE,$APELLIDOP,$APELLIDOM,$USUARIO,$PASS,$USUARIOCREADOR,$FECHACREACION,$BD){
        $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
        $result = false;
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                    // <editor-fold defaultstate="collapsed" desc="INSERSION DE USUARIOS">                 
                      $query  ="INSERT INTO USUARIOS (NOMBRE,APELLIDO_P,APELLIDO_M,USUARIO,PASS,ESTATUS,USUARIO_CREADOR,FECHA_HORA_CREACION) ";
                      $query .= "VALUES('$NOMBRE','$APELLIDOP','$APELLIDOM','$USUARIO','$PASS','A','$USUARIOCREADOR','$FECHACREACION');";                 
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
    
    // <editor-fold defaultstate="collapsed" desc="Actualiza los usuarios del sistema">
    function ActualizaUsuario($USUARIOID,$NOMBRE,$APELLIDOP,$APELLIDOM,$USUARIO,$PASS,$ESTATUS,$USUARIOMODIFICACION,$FECHAMODIFICACION,$BD){
        $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
        $result = false;
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                    // <editor-fold defaultstate="collapsed" desc="UPDATE DE LOS USUARIOS">                 
                      $query  = "UPDATE USUARIOS SET ";
                      $query .= " NOMBRE='$NOMBRE',APELLIDO_P='$APELLIDOP' ,APELLIDO_M='$APELLIDOM' ";
                      $query .= " ,USUARIO='$USUARIO' ,PASS='$PASS' ,ESTATUS='$ESTATUS',";
                      $query .= " USUARIO_MODIFICACION='$USUARIOMODIFICACION' ,FECHA_HORA_MODIFICACION= '$FECHAMODIFICACION'";
                      $query .= " WHERE USUARIO_ID=$USUARIOID;";                 
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

    // <editor-fold defaultstate="collapsed" desc="Muestra los usuarios del sistema">
    function MostrarUsuarios($BD){
        $conn = ABRIR_CONEXION_MYSQL(FALSE,$BD);
        $result = false;
        if ($conn){  
                    // <editor-fold defaultstate="collapsed" desc="SELECCION DE TODOS LOS USUARIOS">                 
                      $select  = "SELECT * FROM USUARIOS";                 
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
    'InsertarUsuario',
    array(
        'NOMBRE'=>'xsd:string',
        'APELLIDOP'=>'xsd:string',
        'APELLIDOM'=>'xsd:string',
        'USUARIO'=>'xsd:string',
        'PASS'=>'xsd:string',
        'ESTATUS'=>'xsd:string',
        'USUARIOCREADOR'=>'xsd:string',
        'FECHACREACION'=>'xsd:string',
        'BD'=>'xsd:string'
        ),
    array('return'=>'xsd:boolean'),
    $namespace,
    false,
    'rpc',
    false,
    'Inserta el usuario en el sistema');

    $server->register(
        'ActualizarUsuario',
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
        'Actualiza el usuario indicado en el sistema');

        $server->wsdl->addComplexType(
            'MostrarUsuarios',
            'complexType',
            'struct',
            'all',
            '',
            array(
                'USUARIO_ID' => array('name'=>'USUARIO_ID','type'=>'xsd:int'),
                'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
                'APELLIDO_P' => array('name'=>'APELLIDO_P','type'=>'xsd:string'),
                'APELLIDO_M' => array('name'=>'APELLIDO_M','type'=>'xsd:string'),
                'USUARIO' => array('name'=>'USUARIO','type'=>'xsd:string'),
                'PASS' => array('name'=>'PASS','type'=>'xsd:string'),
                'ESTATUS' => array('name'=>'ESTATUS','type'=>'xsd:string'),
                'USUARIO_CREADOR' => array('name'=>'USUARIO_CREADOR','type'=>'xsd:string'),
                'FECHA_HORA_CREACION' => array('name'=>'FECHA_HORA_CREACION','type'=>'xsd:string'),
                'USUARIO_MODIFICACION' => array('name'=>'USUARIO_MODIFICACION','type'=>'xsd:string'),
                'FECHA_HORA_MODIFICACION' => array('name'=>'FECHA_HORA_MODIFICACION','type'=>'xsd:string')
            )
        );    
        $server->wsdl->addComplexType(
            'MostrarUsuariosArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarUsuarios[]')),
            'tns:MostrarUsuarios'
            );
            $server->register(
                'MostrarUsuarios',
                array(
                    'BD'=>'xsd:string'
                ),//parametros de entrada
                array('return'=> 'tns:MostrarUsuariosArray'),//retornar
                $namespace,
                false,
                'rpc',
                false,
                'Devuelve un arreglo con los usuarios');