<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Administrar_evaluaciones_p6 extends CI_Controller {
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
    public function index(){

    }
    public function caja(){
        $data['idInmobiliaria']   = trim($this->input->post('idInmobiliaria'));
        $data['idProyecto']       = trim($this->input->post('idProyecto'));
        $data['idTipoC']          = trim($this->input->post('var1'));
        $data['idPais']           = trim($this->input->post('var2'));
        $data['idEvaluacion']     = trim($this->input->post('var3'));
        $data['idUser']           = $_SESSION['idUser'];
        $data['dbData']           = $this->load->database('evaluaciones', TRUE);


        $this->load->view('productos/administrador_evaluaciones/cuestionarios/p6_caja',$data);
        # ------------------------
    }
}
