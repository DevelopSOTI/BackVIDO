<?php

function ReporteFaltantesSolucion($FALTANTES_ID, $SUCURSAL_ID, $ARTICULO_ID, $FECHA_INI_FAL, $FECHA_FIN_FAL, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    $hostname = $_SERVER['SERVER_NAME'];
    if ($conn) {
        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">
        $select = "SELECT S.*";
        $select .= " FROM(";
        $select .= "     SELECT";
        $select .= "         F.SUCURSAL_ID,";
        $select .= "         F.FALTANTES_ID,";
        $select .= "         FD.FALTANTES_DETALLE_ID,";
        $select .= "         A.ARTICULO_ID,";
        $select .= "         A.SKU,";
        $select .= "         A.NOMBRE,";
        $select .= "         A.DESCRIPCION,";
        $select .= "         FD.STOCK_FISICO,";
        $select .= "         FD.PRECIO_ARTICULO,";
        $select .= "         A.IMAGEN,";
        $select .= "         IFNULL(SP.NOMBRE,'') as SOLUCION ";
        $select .= "         ,F.FECHA,";
        $select .= "         (";
        $select .= "             SELECT E.EXISTENCIA";
        $select .= "             FROM EXISTENCIAS E";
        $select .= "             WHERE E.ARTICULO_ID = FD.ARTICULO_ID";
        $select .= "             AND E.SUCURSAL_ID = F.SUCURSAL_ID";
        $select .= "             AND E.FECHA >= F.FECHA";
        $select .= "             ORDER BY E.FECHA";
        $select .= "             LIMIT 1";
        $select .= "         ) AS EXISTENCIA_TEORICA";
        $select .= "     FROM FALTANTES AS F";
        $select .= "     INNER JOIN FALTANTES_DETALLE AS FD ON F.FALTANTES_ID = FD.FALTANTES_ID";
        $select .= "     INNER JOIN ARTICULOS AS A ON FD.ARTICULO_ID = A.ARTICULO_ID";
        $select .= "     LEFT JOIN SOLUCION SOL on SOL.FALTANTES_ID = F.FALTANTES_ID ";
        $select .= "     LEFT JOIN SOLUCION_DETALLE SD on SD.SOLUCION_ID = SOL.SOLUCION_ID AND SD.ARTICULO_ID = FD.ARTICULO_ID";
        $select .= "     LEFT JOIN SOLUCION_OPCIONES SP on SP.SOLUCION_OPCIONES_ID = SD.SOLUCION_OPCIONES_ID";
        $select .= "     WHERE F.ESTATUS = 'P'";
        if ($SUCURSAL_ID !== "0") {
            $select .= "     AND F.SUCURSAL_ID in ( $SUCURSAL_ID )";
        }
        if($FALTANTES_ID !== "0"){
            $select .= "     AND F.FALTANTES_ID in( $FALTANTES_ID )";
        }
        if(isset($FECHA_INI_FAL) && isset($FECHA_FIN_FAL)){
            $fecha_ini = date('Y-m-d', strtotime($FECHA_INI_FAL));
            $fecha_fin = date('Y-m-d', strtotime($FECHA_FIN_FAL));
            
            $select .= " AND F.FECHA BETWEEN '$fecha_ini' and '$fecha_fin'";
        }
        if($ARTICULO_ID !=="0"){
            $select .= " AND A.ARTICULO_ID IN( $ARTICULO_ID )";
        }
       
        $select .= " ) AS S";
        $select .= " WHERE (S.EXISTENCIA_TEORICA - S.STOCK_FISICO) > 0";
        $select .= " ORDER BY S.FALTANTES_DETALLE_ID DESC;";
        echo $select;
        // </editor-fold>    
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            $result = array();
            while ($row = mysqli_fetch_assoc($stmt)) {
                $faltante["FALTANTES_ID"] = $row["FALTANTES_ID"];
                $faltante["FALTANTES_DETALLE_ID"] = $row["FALTANTES_DETALLE_ID"];
                $faltante["ARTICULO_ID"] = $row["ARTICULO_ID"];
                $faltante["SKU"] = $row["SKU"];
                $faltante["NOMBRE"] = $row["NOMBRE"];
                $faltante["DESCRIPCION"] = $row["DESCRIPCION"];
                $faltante["STOCK_FISICO"] = $row["STOCK_FISICO"];
                $faltante["PRECIO_ARTICULO"] = $row["PRECIO_ARTICULO"];
                $faltante["IMAGEN"] = $hostname . "/articulos/" . $row["IMAGEN"];
                $faltante["SOLUCION"] = $row["SOLUCION"];
                $faltante["FECHA"] = $row["FECHA"];
                $faltante["EXISTENCIA_TEORICA"] = $row["EXISTENCIA_TEORICA"];
                $result[] = $faltante;
            }
            mysqli_close($conn);
            return json_encode(["results" => $result]); // Aquí se devuelve la respuesta en formato JSON
        } else {
            mysqli_close($conn);
            return json_encode(["results" => $result]); // Aquí se devuelve la respuesta en formato JSON
        }
        mysqli_close($conn);
    } else {
        // FALLO LA CONEXION
        return json_encode(["results" => $result]); // Aquí se devuelve la respuesta en formato JSON
    }
}

$server->register(
    'ReporteFaltantesSolucion',
    array(
        'FALTANTES_ID' => 'xsd:string',
        'SUCURSAL_ID' => 'xsd:string',
        'ARTICULO_ID' => 'xsd:string',
        'FECHA_INI_FAL' => 'xsd:string',
        'FECHA_FIN_FAL' => 'xsd:string',
        'BD' => 'xsd:string'
    ),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve la informacion para el reporte de faltantes con existencias'
);

