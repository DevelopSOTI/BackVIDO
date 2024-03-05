<?php
// <editor-fold defaultstate="collapsed" desc="Inserta los usuarios del sistema">
//use JetBrains\PhpStorm\Internal\ReturnTypeContract;
include '../../ChastityBelt.php';
include '../../functions.php';
function LoginClaveRol2($USUARIO,$PASS){
    $conn = ABRIR_CONEXION_MYSQLI(FALSE);
    $provUsuario=null;
    if ($conn){
        $validador= new ChastityBelt();
        if($validador->ValidaCadena($USUARIO,strlen($USUARIO),1)) {
            if($validador->ValidaCadena($USUARIO,strlen($PASS),1)) {

                $select  ="SELECT C.CLAVE FROM USUARIOS AS U ";
                $select .=" INNER JOIN ROLES_USUARIOS AS RU ON U.USUARIO_ID=RU.USUARIO_ID ";
                $select .=" INNER JOIN ROLES AS C ON RU.ROL_ID=C.ROL_ID";
                $select .=" WHERE U.USUARIO=$USUARIO AND U.PASS=$PASS";  
                $stmt = mysqli_query($conn, $select); 
                if ($stmt){
                
                    while ($row = mysqli_fetch_assoc($stmt)){
                        $prov["CLAVE"] = $row["CLAVE"];
                        $provUsuario[] = $prov;
                    } 
                }
                else{
                    
                    mysqli_close($conn);
                    return null; 
                }
            }  
        }          
    }
    else {
        // FALLO LA CONEXION
        return null; 
    }
    mysqli_close($conn);
    return $provUsuario;
}

function LoginUsuario2($USUARIO){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $provUsuario=null;
    if ($conn){
        $validador= new ChastityBelt();
        if($validador->ValidaCadena($USUARIO,strlen($USUARIO),1)) {           
            $select  ="SELECT C.CLAVE FROM USUARIOS AS U ";
            $select .=" INNER JOIN ROLES_USUARIOS AS RU ON U.USUARIO_ID=RU.USUARIO_ID ";
            $select .=" INNER JOIN ROLES AS C ON RU.ROL_ID=C.ROL_ID";
            $select .=" WHERE U.USUARIO='$USUARIO'";        
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $prov["CLAVE"] = $row["CLAVE"];
                $provUsuario[] = $prov;
            }       
        }
        else{
                    mysqli_close($conn);
            return null; 
        }
    }

    }
    else {
        // FALLO LA CONEXION
        return null; 
    }
    mysqli_close($conn);
    return $provUsuario;
}
function LoginNombreUsuario2($USUARIO){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $NombreUsuario="";
    if ($conn){
        $validador= new ChastityBelt();
        if($validador->ValidaCadena($USUARIO,strlen($USUARIO),1)) {       
                             
            $select  ="SELECT CONCAT(U.NOMBRE,' ', U.APELLIDO_P) AS NOMBRE FROM USUARIOS AS U ";
            $select .=" WHERE U.USUARIO='$USUARIO'";                 
      
        $stmt = mysqli_query($conn, $select);            

        if ($stmt){
            while ($row = mysqli_fetch_assoc($stmt)){
                $NombreUsuario = $row["NOMBRE"];
            }       
        }
        else{
            $NombreUsuario="";
        }
    }
    }
    else {
        // FALLO LA CONEXION
        $NombreUsuario="";
    }
    mysqli_close($conn);
    return $NombreUsuario;
}

$server->wsdl->addComplexType(
    'LoginClaveRol2',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:int')
    )
);
$server->wsdl->addComplexType(
    'LoginUsuario2',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:int')
    )
);

$server->wsdl->addComplexType(
'LoginClaveRolArray2',
'complexType',
'array',
'',
'SOAP-ENC:Array',
array(),
array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:LoginClaveRol2[]')),
'tns:LoginClaveRol'
);
$server->wsdl->addComplexType(
    'LoginUsuarioArray2',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:LoginClaveRol2[]')),
    'tns:LoginClaveRol'
    );


    $server->wsdl->addComplexType(
        'provUsuario2',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string')
        )
    );
    
    $server->wsdl->addComplexType(
        'provUsuarioArray2',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:provUsuario2[]')),
        'tns:provUsuario'
    );


$server->register(
'LoginClaveRol2',
array('USUARIO'=>'xsd:string',
         'PASS'=>'xsd:string'),
array('return'=> 'tns:provUsuarioArray2'),
$namespace,
false,
'rpc',
false,
'Devuelve un arreglo con los roles asignados al usuario');

$server->register(
    'LoginUsuario2',
    array('USUARIO'=>'xsd:string'),
    array('return'=> 'tns:LoginUsuarioArray2'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve un arreglo con los roles asignados al usuario');

    $server->register(
        'LoginNombreUsuario2',
        array('USUARIO'=>'xsd:string'),
        array('return'=> 'xsd:string'),
        $namespace,
        false,
        'rpc',
        false,
        'Devuelve el nombre del usuario indicado');


  