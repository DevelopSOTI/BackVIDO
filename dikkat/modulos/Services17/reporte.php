<?php

function ReporteFaltantesSolucion($FALTANTES_ID, $SUCURSAL_ID, $ARTICULO_ID, $FECHA_INI_FAL, $FECHA_FIN_FAL, $BD)
{
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;
    $hostname = $_SERVER['SERVER_NAME'];
    if ($conn) {

        if ($FALTANTES_ID == "-1") {
            //buscamos el ultimo faltantes id
            $faltantes = "SELECT FALTANTES_ID FROM FALTANTES WHERE SUCURSAL_ID = $SUCURSAL_ID order by FALTANTES_ID DESC limit 1";
            $stmt = mysqli_query($conn, $faltantes);
            if ($stmt) {
                while ($row = mysqli_fetch_assoc($stmt)) {
                    $solucion = $row["FALTANTES_ID"];
                    $FALTANTES_ID = $solucion;
                    //echo $FALTANTES_ID;
                }

            } else {
                //mysqli_close($conn);
                $FALTANTES_ID = "0";
            }
        }

        // <editor-fold defaultstate="collapsed" desc="SELECCION DE LOS DATOS DE LAS CATEGORIAS DEL DEPARTAMETNO EN EL SISTEMA">
        /*$select = "SELECT S.*";
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
        $select .= "     WHERE (F.ESTATUS = 'P'  OR F.ESTATUS = 'F')";
        if ($SUCURSAL_ID !== "0") {
            $select .= "     AND F.SUCURSAL_ID in ( $SUCURSAL_ID )";
        }
        if ($FALTANTES_ID !== "0") {
            $select .= "     AND F.FALTANTES_ID in( $FALTANTES_ID )";
        }
        if (isset($FECHA_INI_FAL) && isset($FECHA_FIN_FAL) && !empty($FECHA_INI_FAL) && !empty($FECHA_FIN_FAL)) {
            $fecha_ini = date('Y-m-d', strtotime($FECHA_INI_FAL));
            $fecha_fin = date('Y-m-d', strtotime($FECHA_FIN_FAL));

          //  $select .= " AND F.FECHA BETWEEN '$fecha_ini' and '$fecha_fin'";
        }
        if ($ARTICULO_ID !== "0") {
            $select .= " AND A.ARTICULO_ID IN( $ARTICULO_ID )";
        }

        $select .= " ) AS S";
        $select .= " ORDER BY S.FALTANTES_ID, S.FALTANTES_DETALLE_ID DESC;";*/

        $select = "SELECT * FROM
                    (
                            SELECT
                                F.FALTANTES_ID,
                                FD.FALTANTES_DETALLE_ID,
                                F.SUCURSAL_ID,
                                A.DESCRIPCION,
                                A.ARTICULO_ID,
                                A.SKU,
                                A.NOMBRE,
                                FD.PRECIO_ARTICULO,
                                FD.STOCK_FISICO,
                                F.FECHA,

                                (SELECT NOMBRE FROM SUCURSALES WHERE SUCURSAL_ID = F.SUCURSAL_ID) AS SUCURSAL,
                                TIMEDIFF(F.HORA_FIN, F.HORA_INICIO) AS TIEMPO

                                , EX.*,
                                IFNULL(EX.EXISTENCIA, 0) EXISTENCIA_TEORICA,
                                
                                CAT.CATEGORIA_ID,
                                CAT.NOMBRE AS CATEGORIA,
                                
                                DEP.DEPARTAMENTO_ID,
                                DEP.NOMBRE AS DEPARTAMENTO,
                                
                                DI.DIVISION_ID,
                                DI.NOMBRE AS DIVISION

                            FROM FALTANTES F
                            JOIN FALTANTES_DETALLE FD ON F.FALTANTES_ID = FD.FALTANTES_ID
                            JOIN SUCURSALES S ON F.SUCURSAL_ID = S.SUCURSAL_ID
                            JOIN CLIENTES C ON S.CLIENTE_ID = C.CLIENTE_ID
                            JOIN ARTICULOS A ON FD.ARTICULO_ID = A.ARTICULO_ID

                            LEFT JOIN CATEGORIAS CAT ON(A.CATEGORIA_ID = CAT.CATEGORIA_ID)
                            LEFT JOIN DEPARTAMENTOS DEP ON(CAT.DEPARTAMENTO_ID = DEP.DEPARTAMENTO_ID)
                            LEFT JOIN DIVISION DI ON(DEP.DIVISION_ID = DI.DIVISION_ID)

                            LEFT JOIN (SELECT E.EXISTENCIA_ID, E.ARTICULO_ID ART_ID, E.SUCURSAL_ID SUC_ID, E.FECHA FECHA_EX, E.EXISTENCIA
                                        FROM EXISTENCIAS E
                                       -- WHERE E.FECHA >= '$fecha_inicio'
                                        GROUP BY E.ARTICULO_ID, E.SUCURSAL_ID, E.FECHA
                                        ORDER BY E.ARTICULO_ID, E.SUCURSAL_ID, E.FECHA
                                    ) EX ON(EX.ART_ID = FD.ARTICULO_ID AND EX.SUC_ID = F.SUCURSAL_ID AND EX.FECHA_EX >= F.FECHA)
                            WHERE S.REPORTEAR = 1
                            AND F.FALTANTES_ID = $FALTANTES_ID
                            AND S.SUCURSAL_ID = $SUCURSAL_ID
                           -- AND C.CLIENTE_ID = '$clienteID'
                            -- AND (F.FECHA BETWEEN '$fecha_inicio' AND '$fecha_fin')
                            ORDER BY F.FALTANTES_ID, F.SUCURSAL_ID, A.ARTICULO_ID, EX.FECHA_EX
                    ) EXT
                    GROUP BY EXT.FALTANTES_ID,
                            EXT.SUCURSAL_ID,
                            EXT.DESCRIPCION,
                            EXT.ARTICULO_ID,
                            EXT.PRECIO_ARTICULO,
                            EXT.STOCK_FISICO,
                            EXT.FECHA
                    ORDER BY EXT.SUCURSAL_ID, EXT.FALTANTES_ID, EXT.ARTICULO_ID, EXT.FECHA_EX";
                    
                    //echo $select;


        // </editor-fold>    
        $stmt = mysqli_query($conn, $select);
        if ($stmt) {
            $result = array();
            $current_faltantes_id = null;
            $current_faltantes_data = null;
            while ($row = mysqli_fetch_assoc($stmt)) {
                if ($row['FALTANTES_ID'] !== $current_faltantes_id) {
                    // Si el FALTANTES_ID actual es diferente al anterior,
                    // almacenamos los datos del FALTANTES_ID anterior en el resultado
                    if ($current_faltantes_id !== null) {
                        $result[] = $current_faltantes_data;
                    }
                    // Inicializamos los datos del nuevo FALTANTES_ID
                    $current_faltantes_id = $row['FALTANTES_ID'];
                    $current_faltantes_data = array(
                        "FALTANTES_ID" => $current_faltantes_id,
                        "detalles" => array()
                    );
                }
                // Almacenamos los detalles del FALTANTES_ID actual
                $current_faltantes_data["detalles"][] = array(
                    "FALTANTES_DETALLE_ID" => $row["FALTANTES_DETALLE_ID"],
                    "ARTICULO_ID" => $row["ARTICULO_ID"],
                    "SKU" => $row["SKU"],
                    "NOMBRE" => $row["NOMBRE"],
                    "DESCRIPCION" => $row["DESCRIPCION"],
                    "STOCK_FISICO" => $row["STOCK_FISICO"],
                    "PRECIO_ARTICULO" => $row["PRECIO_ARTICULO"],
                    "IMAGEN" => $hostname . "/articulos/" . $row["IMAGEN"],
                    "SOLUCION" => $row["SOLUCION"],
                    "FECHA" => $row["FECHA"],
                    "EXISTENCIA_TEORICA" => $row["EXISTENCIA_TEORICA"]
                );
            }
            // Almacenamos los datos del último FALTANTES_ID en el resultado
            if ($current_faltantes_id !== null) {
                $result[] = $current_faltantes_data;
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


function ReporteSolucion($FALTANTES_ID, $SUCURSAL_ID, $BD)
{
    // Conectar a la base de datos
    $conn = ABRIR_CONEXION_MYSQL(FALSE, $BD);
    $result = null;

    if ($conn) {
        // Establecer el conjunto de caracteres a UTF-8
        mysqli_set_charset($conn, "utf8");

        // Consulta SQL
        $select = "SELECT 
            FD.FALTANTES_DETALLE_ID, 
            A.ARTICULO_ID, 
            A.SKU, 
            IFNULL(SP.NOMBRE, '') AS SOLUCION,
            SD.SOLUCIONADO
        FROM FALTANTES AS F 
        INNER JOIN FALTANTES_DETALLE AS FD ON F.FALTANTES_ID = FD.FALTANTES_ID 
        INNER JOIN ARTICULOS AS A ON FD.ARTICULO_ID = A.ARTICULO_ID 
        LEFT JOIN SOLUCION SOL ON SOL.FALTANTES_ID = F.FALTANTES_ID 
        LEFT JOIN SOLUCION_DETALLE SD ON SD.SOLUCION_ID = SOL.SOLUCION_ID AND SD.ARTICULO_ID = FD.ARTICULO_ID 
        LEFT JOIN SOLUCION_OPCIONES SP ON SP.SOLUCION_OPCIONES_ID = SD.SOLUCION_OPCIONES_ID 
        LEFT JOIN (
            SELECT 
                E.EXISTENCIA_ID, 
                E.ARTICULO_ID, 
                E.SUCURSAL_ID, 
                E.FECHA, 
                E.EXISTENCIA, 
                E.FECHA_ULT_RECIBO, 
                E.CAPACIDAD_EMPAQUE 
            FROM EXISTENCIAS E 
            WHERE E.FECHA >= (SELECT FECHA FROM FALTANTES WHERE FALTANTES_ID = ?) 
            GROUP BY E.EXISTENCIA_ID, E.ARTICULO_ID, E.SUCURSAL_ID, E.FECHA, E.EXISTENCIA, E.FECHA_ULT_RECIBO, E.CAPACIDAD_EMPAQUE 
            ORDER BY E.ARTICULO_ID, E.FECHA
        ) EX ON EX.ARTICULO_ID = FD.ARTICULO_ID 
           AND EX.SUCURSAL_ID = F.SUCURSAL_ID 
           AND EX.FECHA >= F.FECHA 
        WHERE F.FALTANTES_ID = ? 
        AND F.SUCURSAL_ID = ? 
        AND (F.ESTATUS = 'P' OR F.ESTATUS = 'F') 
        AND EX.EXISTENCIA > 0 
        ORDER BY SP.SOLUCION_OPCIONES_ID ASC;";

        // Preparar la consulta
        if ($stmt = mysqli_prepare($conn, $select)) {
            // Asignar parámetros
            mysqli_stmt_bind_param($stmt, "iii", $FALTANTES_ID, $FALTANTES_ID, $SUCURSAL_ID);

            // Ejecutar la consulta
            mysqli_stmt_execute($stmt);

            // Obtener resultados
            $result = array();
            mysqli_stmt_bind_result($stmt, $faltantes_detalle_id, $articulo_id, $sku, $solucion, $solucionado);

            while (mysqli_stmt_fetch($stmt)) {
                $result[] = array(
                    "FALTANTES_DETALLE_ID" => $faltantes_detalle_id,
                    "ARTICULO_ID" => $articulo_id,
                    "SKU" => $sku,
                    "SOLUCION" => $solucion,
                    "SOLUCIONADO" => $solucionado
                );
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_close($conn);
    }

    // Establecer el encabezado para la respuesta JSON en UTF-8
    header('Content-Type: application/json; charset=utf-8');
    return json_encode(["results" => $result], JSON_UNESCAPED_UNICODE);
}




$server->register(
    'ReporteSolucion',
    array(
        'FALTANTES_ID' => 'xsd:string',
        'SUCURSAL_ID' => 'xsd:string',
        'BD' => 'xsd:string'
    ),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    false,
    'Devuelve la informacion para el reporte de faltantes solucion'
);