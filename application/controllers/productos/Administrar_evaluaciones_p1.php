<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrar_evaluaciones_p1 extends CI_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->library('user_agent');
        $this->load->database();
        $this->load->helper('cookie');
        session_start();
        $this->load->model('Tgasolutions');
        $this->Tgasolutions->usuario();
        $this->Tgasolutions->permisos();
        $this->load->library('pagination');
    }
	public function subir_excel(){
        $idTipoCuestionario = $_POST['hf_idTipoCuestionario'];
        $idPais = $_POST['hf_idPais'];
        $idInmobiliaria = $_POST['hf_idInmobiliaria'];
        $idProyecto = $_POST['hf_idProyecto'];
        $idEvaluacion = $_POST['hf_idEvaluacion'];
        $fileName = $_FILES['archivo']['name'];
        $fileSize = $_FILES['archivo']['size'];
        $target_dir = 'excel';
        $target_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $target_dir . '/' . basename($fileName);
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $mensaje = 'Error al subir archivo Excel seleccionado.';
        $status = 'ERROR';
        $archivo_xlsx = '';

        if(move_uploaded_file($_FILES['archivo']['tmp_name'], $target_file)){
            $idUser = $_SESSION['idUser'];
            $archivo_xlsx = $idTipoCuestionario . '_' . $idPais . '_' . $idInmobiliaria . '_' . $idProyecto . '_' . $idEvaluacion . '_' . $idUser . '_cuestionario.xlsx';
            $target_file_new = $_SERVER['DOCUMENT_ROOT'] . '/' . $target_dir . '/' . $archivo_xlsx;
            if(rename($target_file, $target_file_new)){
                $mensaje = 'Se ha subido correctamente el archivo Excel seleccionado.';
                $status = 'SUCCESS';
            }
            # guardar
            $dbData = $this->load->database('evaluaciones', TRUE);
            $sql = " UPDATE data_evaluaciones
                        SET avance = 2
                      WHERE idEvaluacion     = $idEvaluacion
                        AND idInmobiliaria   = $idInmobiliaria
                        AND idProyecto       = $idProyecto
                        AND idTipoC          = $idTipoCuestionario;";
            $dbData->query($sql);
        }
        $data = array(
            'status' => $status,
            'titulo' => 'Administrar evaluaciones',
            'mensaje' => $mensaje,
            'archivo_xlsx' => $archivo_xlsx,
            'upload_dir' => $target_dir
        );
        $output = json_encode($data);
        echo $output;
    }

}
