<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class G_leads extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->library('user_agent');
        $this->load->database();
        $this->load->helper('cookie');
        session_start();

        $this->load->model('Tgasolutions');
        # ------------------ -------------------
        $this->Tgasolutions->usuario();
        $this->Tgasolutions->permisos();
    }
    public function index(){
        $this->load->view('pre_body');

        $data["hoja"] = "home";
        $this->load->view('header',$data);

        $data["sasas"] = "asasas";
        $this->load->view('productos/g_leads/index',$data);

        $this->load->view('post_body');
    }
    public function v1(){
        $this->load->view('productos/g_leads/inmobiliaria/index');
    }
    public function v2(){
        $this->load->view('productos/g_leads/proyectos/index');
    }
    public function v3(){
        echo "Portales";
    }
    public function v4(){
        echo "Q-quest";
    }
    public function v5(){
        echo "G-cotizador";
    }
    public function v6(){
        echo "G-contact";
    }
}
