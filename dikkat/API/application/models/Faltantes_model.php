<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faltantes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_faltantes_pendiente($faltantes_id, $sucursal_id, $bd) {
        // Configura la conexión a la base de datos dinámica
        $config = array(
            'dsn'   => '',
            'hostname' => 'localhost', // Cambia esto si tu hostname es diferente
            'username' => 'soticomm_VIDO', // Cambia esto a tu usuario de la base de datos
            //'username' => 'tu_usuario',
            'password' => 'Vido_2024', // Cambia esto a tu contraseña de la base de datos
            //'password' => 'tu_contraseña',
            'database' => $bd,
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt' => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE
        );

        // Carga la base de datos dinámica
        $db = $this->load->database($config, TRUE);

        $sql = "SELECT S.*, E.EXISTENCIA AS EXISTENCIA_TEORICA, E.FECHA_ULT_RECIBO, E.CAPACIDAD_EMPAQUE
                FROM (
                    SELECT F.FALTANTES_ID, FD.FALTANTES_DETALLE_ID, A.ARTICULO_ID, A.SKU, A.NOMBRE, A.DESCRIPCION, FD.STOCK_FISICO, FD.PRECIO_ARTICULO, A.IMAGEN, IFNULL(SP.NOMBRE, '') AS SOLUCION, F.FECHA, 
                           (SELECT E.EXISTENCIA_ID FROM EXISTENCIAS E WHERE E.ARTICULO_ID = FD.ARTICULO_ID AND E.SUCURSAL_ID = F.SUCURSAL_ID AND E.FECHA >= F.FECHA ORDER BY E.FECHA LIMIT 1) AS EXISTENCIA_ID
                    FROM FALTANTES AS F
                    INNER JOIN FALTANTES_DETALLE AS FD ON F.FALTANTES_ID = FD.FALTANTES_ID
                    INNER JOIN ARTICULOS AS A ON FD.ARTICULO_ID = A.ARTICULO_ID
                    LEFT JOIN SOLUCION SOL ON SOL.FALTANTES_ID = F.FALTANTES_ID
                    LEFT JOIN SOLUCION_DETALLE SD ON SD.SOLUCION_ID = SOL.SOLUCION_ID AND SD.ARTICULO_ID = FD.ARTICULO_ID
                    LEFT JOIN SOLUCION_OPCIONES SP ON SP.SOLUCION_OPCIONES_ID = SD.SOLUCION_OPCIONES_ID
                    WHERE F.FALTANTES_ID = ? AND F.SUCURSAL_ID = ? AND (F.ESTATUS = 'P' OR F.ESTATUS = 'F')
                ) AS S
                JOIN EXISTENCIAS E ON (S.EXISTENCIA_ID = E.EXISTENCIA_ID)
                WHERE E.EXISTENCIA > 0
                ORDER BY S.FALTANTES_DETALLE_ID ASC";

        $query = $db->query($sql, array($faltantes_id, $sucursal_id));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }
}
