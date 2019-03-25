<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Evaluaciones extends CI_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->library('user_agent');
        $this->load->library('spreadsheet_reader');
        $this->load->database('evaluaciones');
        $this->load->helper('cookie');
        session_start();
        date_default_timezone_set('UTC');
        $this->load->model('Tgasolutions');
        $this->Tgasolutions->usuario();
        $this->Tgasolutions->permisos();
    }


    public function listar_evaluaciones(){
        $sql = "SELECT * FROM data_evaluaciones ORDER BY idEvaluacion ASC;";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $data['result'] = $result;
        $this->load->view('productos/administrador_evaluaciones/evaluaciones/listar_evaluaciones', $data);
    }


    public function agregar_evaluacion(){
        $this->load->view('productos/administrador_evaluaciones/evaluaciones/form_agregar');
    }


    public function agregar_evaluacion_guardar(){
        $id_tipo_cuestionario = $_POST['hf_idTipoCuestionario'];
        $id_inmobiliaria = $_POST['hf_idInmobiliaria'];
        $id_proyecto = $_POST['id_proyecto'];
        $evaluacion = $_POST['evaluacion'];
        
        /* $sql = "INSERT INTO data_evaluaciones (idInmobiliaria, idProyecto, idTipoC, evaluacion, numColumna, avance, estado, fechaInicioCampo, ";
        $sql .= "fechaFinCampo, fechaEvaluacion) VALUES (" . $id_inmobiliaria . ", " . $id_proyecto . ", " . $id_tipo_cuestionario . ", '";
        $sql .= $evaluacion . "', 0, 1, 2, '0000-00-00', '0000-00-00', '0000-00-00');"; */
        
        $sql = "INSERT INTO data_evaluaciones (idInmobiliaria, idProyecto, idTipoC, evaluacion, numColumna, avance, estado) ";
        $sql .= "VALUES (" . $id_inmobiliaria . ", " . $id_proyecto . ", " . $id_tipo_cuestionario . ", '" . $evaluacion . "', 0, 1, 2);";

        $result = $this->db->query($sql);
        if($result){
            $mensaje = 'Se ha guardado correctamente una nueva evaluación.';
            $status = 'SUCCESS';
        }
        else{
            $mensaje = 'Error al crear una nueva evaluación.';
            $status = 'ERROR';
        }
        $data = array('status' => $status, 'mensaje' => $mensaje);
        $output = json_encode($data);
        echo $output;
    }

    
    public function editar_evaluacion($id_evaluacion){
        $sql = "SELECT * FROM data_evaluaciones WHERE idEvaluacion=" . $id_evaluacion . " LIMIT 1;";
        $query = $this->db->query($sql);
        $data = $query->row_array();
        if(isset($data)){
            $this->load->view('productos/administrador_evaluaciones/evaluaciones/form_editar', $data);
        }
    }


    public function editar_evaluacion_guardar(){
        $id_tipo_cuestionario = $_POST['hf_idTipoCuestionario'];
        $id_inmobiliaria = $_POST['hf_idInmobiliaria'];
        $id_proyecto = $_POST['id_proyecto'];
        $id_evaluacion = $_POST['hf_idEvaluacion'];
        $evaluacion = $_POST['evaluacion'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $fecha_evaluacion = $_POST['fecha_evaluacion'];
        $sql = "UPDATE data_evaluaciones SET idInmobiliaria=" . $id_inmobiliaria . ", idProyecto=" . $id_proyecto . ", idTipoC=" . $id_tipo_cuestionario;
        $sql .= ", evaluacion='" . $evaluacion . "', fechaInicioCampo='" . $fecha_inicio . "', fechaFinCampo='" . $fecha_fin . "', fechaEvaluacion='";
        $sql .= $fecha_evaluacion . "' WHERE idEvaluacion=" . $id_evaluacion . " LIMIT 1;";
        $result = $this->db->query($sql);
        if($result){
            $mensaje = 'Se ha editado correctamente la evaluación seleccionada.';
            $status = 'SUCCESS';
        }
        else{
            $mensaje = 'Error al editar la evaluación seleccionada.';
            $status = 'ERROR';
        }
        $data = array('status' => $status, 'mensaje' => $mensaje);
        $output = json_encode($data);
        echo $output;
    }


}