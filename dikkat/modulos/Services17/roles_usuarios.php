<?php
// <editor-fold defaultstate="collapsed" desc="Inserta las los roles de usuario en el sistema">
    function InsertarRolUsuario($USUARIO_ID,$ROL_ID){
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = false;
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                    // <editor-fold defaultstate="collapsed" desc="INSERSION DE ROL A USUARIO">                 
                      $query   ="INSERT INTO ROLES_USUARIOS (USUARIO_ID,ROL_ID) ";
                      $query  .="VALUES($USUARIO_ID,$ROL_ID);";
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

         // <editor-fold defaultstate="collapsed" desc="Actualiza la marca en el sistema">
 function EliminarRolUsuario($USUARIO_ID,$ROL_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE LA MARCA">                 
                  $query  = "DELETE FROM ROLES_USUARIOS WHERE USUARIO_ID=$USUARIO_ID AND ROL_ID=$ROL_ID;";                 
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

 // <editor-fold defaultstate="collapsed" desc="Muestra los datos de la marca indicada en el sistema">
 function MostrarRolesUsuario($USUARIO_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result=NULL;
    if ($conn){  
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LA MARCA EN EL SISTEMA">                 
                  $select  = "SELECT R.ROL_ID,R.DESCRIPCION,R.CLAVE FROM soticomm_INMEX.ROLES_USUARIOS AS RU ";
                  $select .= " INNER JOIN soticomm_INMEX.ROLES AS R ON RU.ROL_ID=R.ROL_ID  ";
                  $select .= " WHERE RU.USUARIO_ID=$USUARIO_ID;";              
                // </editor-fold>
                
                    $stmt = mysqli_query($conn, $select);            

                    if ($stmt){
                        while ($row = mysqli_fetch_assoc($stmt)){
                            $roles["ROL_ID"]=$row["ROL_ID"];
                            $roles["DESCRIPCION"]=$row["DESCRIPCION"];
                            $roles["CLAVE"]=$row["CLAVE"];
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
                    // FALLO LA CONEXION
                    return null; 
                }
                
                mysqli_close($conn);
}
// </editor-fold>


    $server->register(
        'InsertarRolUsuario',
        array(
            'USUARIO_ID'=>'xsd:int',
            'ROL_ID'=>'xsd:int'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta el rol del usuario en el sistema');

        $server->register(
            'EliminarRolUsuario',
            array(
                'USUARIO_ID'=>'xsd:int',
                'ROL_ID'=>'xsd:int'
                ),
            array('return'=>'xsd:boolean'),
            $namespace,
            false,
            'rpc',
            false,
            'Elimina el rol del usuario en el sistema');
 
            $server->wsdl->addComplexType(
                'MostrarRolesUsuario',
                'complexType',
                'struct',
                'all',
                '',
                array(
                    'ROL_ID' => array('name'=>'ROL_ID','type'=>'xsd:int'),
                    'DESCRIPCION' => array('name'=>'DESCRIPCION','type'=>'xsd:string'),
                    'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string')
                )
            );    
            $server->wsdl->addComplexType(
                'MostrarRolesUsuarioArray',
                'complexType',
                'array',
                '',
                'SOAP-ENC:Array',
                array(),
                array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarRolesUsuario[]')),
                'tns:MostrarRolesUsuario'
                );
            $server->register(
                'MostrarRolesUsuario',
                array('USUARIO_ID'=>'xsd:int'),
                array('return'=> 'tns:MostrarRolesUsuarioArray'),
                $namespace,
                false,
                'rpc',
                false,
                'Devuelve un arreglo con los roles del usuario indicado indicada');


