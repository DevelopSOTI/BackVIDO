<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faltantes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_faltantes_pendiente($faltantes_id, $sucursal_id, $bd) {
        // Selecciona la base de datos
        $this->db = $this->load->database($bd, TRUE);

        $this->db->select('S.*, E.EXISTENCIA AS EXISTENCIA_TEORICA, E.FECHA_ULT_RECIBO, E.CAPACIDAD_EMPAQUE');
        $this->db->from('(SELECT F.FALTANTES_ID, FD.FALTANTES_DETALLE_ID, A.ARTICULO_ID, A.SKU, A.NOMBRE, A.DESCRIPCION, FD.STOCK_FISICO, FD.PRECIO_ARTICULO, A.IMAGEN, IFNULL(SP.NOMBRE, "") AS SOLUCION, F.FECHA, 
                          (SELECT E.EXISTENCIA_ID FROM EXISTENCIAS E WHERE E.ARTICULO_ID = FD.ARTICULO_ID AND E.SUCURSAL_ID = F.SUCURSAL_ID AND E.FECHA >= F.FECHA ORDER BY E.FECHA LIMIT 1) AS EXISTENCIA_ID
                          FROM FALTANTES AS F
                          INNER JOIN FALTANTES_DETALLE AS FD ON F.FALTANTES_ID = FD.FALTANTES_ID
                          INNER JOIN ARTICULOS AS A ON FD.ARTICULO_ID = A.ARTICULO_ID
                          LEFT JOIN SOLUCION SOL ON SOL.FALTANTES_ID = F.FALTANTES_ID
                          LEFT JOIN SOLUCION_DETALLE SD ON SD.SOLUCION_ID = SOL.SOLUCION_ID AND SD.ARTICULO_ID = FD.ARTICULO_ID
                          LEFT JOIN SOLUCION_OPCIONES SP ON SP.SOLUCION_OPCIONES_ID = SD.SOLUCION_OPCIONES_ID
                          WHERE F.FALTANTES_ID = ? AND F.SUCURSAL_ID = ? AND (F.ESTATUS = "P" OR F.ESTATUS = "F")) AS S', FALSE);
        $this->db->join('EXISTENCIAS E', 'S.EXISTENCIA_ID = E.EXISTENCIA_ID');
        $this->db->where('E.EXISTENCIA >', 0);
        $this->db->order_by('S.FALTANTES_DETALLE_ID', 'ASC');

        $query = $this->db->query($this->db->last_query(), array($faltantes_id, $sucursal_id));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }
}
