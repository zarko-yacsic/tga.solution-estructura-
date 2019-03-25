<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Administrar_evaluaciones_p2 extends CI_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->library('user_agent');
        $this->load->library('spreadsheet_reader');
        $this->load->database('evaluaciones');
        $this->load->helper('cookie');
        session_start();
        $this->load->model('Tgasolutions');
        $this->Tgasolutions->usuario();
        $this->Tgasolutions->permisos();
        $this->load->library('pagination');
    }
	public function guardar_inicio_evaluacion(){
		$num     = trim($this->input->post('marcar'));
		if (!is_numeric($num)) {exit;}

        $idInmobiliaria   = trim($this->input->post('hf_idInmobiliaria'));
        $idProyecto       = trim($this->input->post('hf_idProyecto'));
        $idTipoC          = trim($this->input->post('hf_idTipoCuestionario'));
        $idPais           = trim($this->input->post('hf_idPais'));
        $idEvaluacion     = trim($this->input->post('hf_idEvaluacion'));

        $dbData           = $this->load->database('evaluaciones', TRUE);

		$sql = "UPDATE data_evaluaciones
				   SET numColumna = $num
				     ,     avance = 3
	            	WHERE idInmobiliaria  = $idInmobiliaria
	            	  AND idProyecto      = $idProyecto
	            	  AND idTipoC         = $idTipoC
	            	  AND idEvaluacion    = $idEvaluacion
	            	      LIMIT 1;";
		if ($dbData->query($sql)===true) {
            $status = 'SUCCESS';
            $mensaje = 'Se ha guardado correctamente la columna de inico de la evaluación.';
            $data = array('status' => $status, 'titulo' => 'Administrar evaluaciones', 'mensaje' => $mensaje);
            $output = json_encode($data);
            echo $output;
		}else{
            echo 'Error al actualizar la tabla data_evaluaciones :<br>';
            exit;
		}
    }
}
