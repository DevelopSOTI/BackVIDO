<?php
// <editor-fold defaultstate="collapsed" desc="Inserta las categorias del sistema">
    function InsertarCategoria($NOMBRE,$CLAVE,$DEPARTAMENTO_ID){
        $conn = ABRIR_CONEXION_MYSQL(FALSE);
        $result = false;
        if ($conn){
            mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                    // <editor-fold defaultstate="collapsed" desc="INSERSION DE CATEGORIAS">                 
                      $query   ="INSERT INTO CATEGORIAS(NOMBRE,CLAVE,DEPARTAMENTO_ID)";
                      $query  .="VALUES('$NOMBRE','$CLAVE',$DEPARTAMENTO_ID);";                 
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

 // <editor-fold defaultstate="collapsed" desc="Actualiza la categoría en el sistema">
 function ActualizaCategoria($CATEGORIA_ID,$NOMBRE,$CLAVE,$DEPARTAMENTO_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = false;
    if ($conn){
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);       
                // <editor-fold defaultstate="collapsed" desc="UPDATE DE LA CATEGORÍA">                 
                  $query  = "UPDATE CATEGORIAS ";
                  $query  .= "SET ";
                  $query  .= "NOMBRE = '$NOMBRE', ";
                  $query  .= "CLAVE = '$CLAVE', ";
                  $query  .= "DEPARTAMENTO_ID = $DEPARTAMENTO_ID ";
                  $query  .= "WHERE CATEGORIA_ID = $CATEGORIA_ID;";                 
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
// <editor-fold defaultstate="collapsed" desc="Muestra los datos de los categorias del sistema">
function MostrarCategorias($DEPARTAMENTO_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    if ($conn){  
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">                 
                  $select  = "SELECT CATEGORIA_ID, NOMBRE, CLAVE FROM CATEGORIAS WHERE DEPARTAMENTO_ID=$DEPARTAMENTO_ID;";
                               
                // </editor-fold>
                
                    $stmt = mysqli_query($conn, $select);            

                    if ($stmt){
                        while ($row = mysqli_fetch_assoc($stmt)){
                            $roles["CATEGORIA_ID"]=$row["CATEGORIA_ID"];
                            $roles["NOMBRE"]=$row["NOMBRE"];
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
}
function MostrarCategoriaDatos($CATEGORIA_ID){
    $conn = ABRIR_CONEXION_MYSQL(FALSE);
    $result = null;
    if ($conn){  
                // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">                 
                  $select  = "SELECT D.DEPARTAMENTO_ID, D.NOMBRE, D.CLAVE FROM CATEGORIAS AS C ";
                  $select .= " INNER JOIN DEPARTAMENTOS AS D ON D.DEPARTAMENTO_ID = C.DEPARTAMENTO_ID";
                  $select .= " WHERE C.CATEGORIA_ID=$CATEGORIA_ID;";
                              // ECHO $select;
                // </editor-fold>
                
                    $stmt = mysqli_query($conn, $select);            

                    if ($stmt){
                        while ($row = mysqli_fetch_assoc($stmt)){
                            $roles["CATEGORIA_ID"]=$row["DEPARTAMENTO_ID"];
                            $roles["NOMBRE"]=$row["NOMBRE"];
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
}
// </editor-fold>

    $server->register(
        'InsertarCategoria',
        array(
            'NOMBRE'=>'xsd:string',
            'CLAVE'=>'xsd:string',
            'DEPARTAMENTO_ID'=>'xsd:int'
            ),
        array('return'=>'xsd:boolean'),
        $namespace,
        false,
        'rpc',
        false,
        'Inserta una categoría en el sistema');

        $server->register(
            'ActualizaCategoria',
            array(
                'CATEGORIA_ID'=>'xsd:int',
                'NOMBRE'=>'xsd:string',
                'CLAVE'=>'xsd:string',
                'DEPARTAMENTO_ID'=>'xsd:int'
                ),
            array('return'=>'xsd:boolean'),
            $namespace,
            false,
            'rpc',
            false,
            'Actualiza la categoría en el sistema');

            
        $server->wsdl->addComplexType(
            'MostrarCategorias',
            'complexType',
            'struct',
            'all',
            '',
            array(
                'CATEGORIA_ID' => array('name'=>'CATEGORIA_ID','type'=>'xsd:int'),
                'NOMBRE' => array('name'=>'NOMBRE','type'=>'xsd:string'),
                'CLAVE' => array('name'=>'CLAVE','type'=>'xsd:string')
            )
        );    
        $server->wsdl->addComplexType(
            'MostrarCategoriasArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:MostrarCategorias[]')),
            'tns:MostrarCategorias'
            );
        $server->register(
            'MostrarCategorias',
            array('DEPARTAMENTO_ID'=>'xsd:int'),
            array('return'=> 'tns:MostrarCategoriasArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con las categorías del departamento del sistema');
        $server->register(
            'MostrarCategoriaDatos',
            array('CATEGORIA_ID'=>'xsd:int'),
            array('return'=> 'tns:MostrarCategoriasArray'),
            $namespace,
            false,
            'rpc',
            false,
            'Devuelve un arreglo con los datos de una categoría');