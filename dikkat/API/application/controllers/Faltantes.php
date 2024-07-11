<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faltantes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Faltantes_model');
    }

    public function mostrarFaltantesPendiente($faltantes_id, $sucursal_id, $bd) {
        $data = $this->Faltantes_model->get_faltantes_pendiente($faltantes_id, $sucursal_id, $bd);
        if ($data) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($data));
        } else {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(404)
                 ->set_output(json_encode(['message' => 'No records found']));
        }
    }
}
