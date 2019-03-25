<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrar_evaluaciones extends CI_Controller {
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

        $this->load->library('pagination');
        $this->load->library('spreadsheet_reader');
    }
    public function index(){
        $this->load->view('pre_body');

        $data["hoja"] = "home";
        $this->load->view('header',$data);

        $data["sasas"] = "asasas";
        $this->load->view('productos/administrador_evaluaciones/index',$data);

        $this->load->view('post_body');
    }
    public function bdd(){
        $data["dbData"] = $this->load->database('evaluaciones', TRUE);
        $this->load->view('productos/administrador_evaluaciones/bdd/index',$data);
    }
    public function respuestas(){
        $data["dbData"] = $this->load->database('evaluaciones', TRUE);
        $this->load->view('productos/administrador_evaluaciones/respuestas/index',$data);
    }
    public function evaluaciones(){
        $data["dbData"] = $this->load->database('evaluaciones', TRUE);
        $this->load->view('productos/administrador_evaluaciones/evaluaciones/index',$data);
    }
    public function categorias(){
        $data["dbData"] = $this->load->database('evaluaciones', TRUE);
        $this->load->view('productos/administrador_evaluaciones/categorias/index',$data);
    }
    public function disenio_preguntas(){
        $data["dbData"] = $this->load->database('evaluaciones', TRUE);
        $this->load->view('productos/administrador_evaluaciones/disenio_preguntas/index',$data);
    }
    public function preguntas(){
        $data["dbData"] = $this->load->database('evaluaciones', TRUE);
        $this->load->view('productos/administrador_evaluaciones/preguntas/index',$data);
    }
    public function estructura_respuestas(){
        $data["dbData"] = $this->load->database('evaluaciones', TRUE);
        $this->load->view('productos/administrador_evaluaciones/estructura_respuestas/index',$data);
    }
    public function plantillas(){
        $this->load->view('productos/administrador_evaluaciones/plantillas/index');
    }
    public function benchmark(){
        $this->load->view('productos/administrador_evaluaciones/benchmark/index');
    }
    # CUESTIONARIOS
    private function avance_cuestionario($numHoja){
        $dbData  = $this->load->database('evaluaciones', TRUE);

        $idInmobiliaria   = trim($this->input->post('idInmobiliaria'));
        $idProyecto       = trim($this->input->post('idProyecto'));
        $idTipoC          = trim($this->input->post('var1'));
        $idPais           = trim($this->input->post('var2'));
        $idEvaluacion     = trim($this->input->post('var3'));

        if (!is_numeric($idInmobiliaria) ||  !is_numeric($idProyecto) ||  !is_numeric($idTipoC) ||  !is_numeric($idEvaluacion)) {
            exit;
        }

        $query   = $dbData->query("SELECT avance
                                     FROM data_evaluaciones
                                    WHERE idEvaluacion     = $idEvaluacion
                                      AND idInmobiliaria   = $idInmobiliaria
                                      AND idProyecto       = $idProyecto
                                      AND idTipoC          = $idTipoC;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            $avance = $row->avance;

            $donde = array('','Subir archivo','Selección preguntas','Proceso','Agrupar categorias','Estructura del cuestionario','Conectar preguntas','Comparar cuestionario','Encuesta Ok');
            ?>
            <script type="text/javascript">
            $(".estadoMini").html("Estado: [P-<?php echo $avance;?>: <?php echo $donde[$avance];?>]");
            </script>
            <?php

            if ($numHoja>$avance) {
                ?>
                <script type="text/javascript">
                if (tgaSolution_admin_eva.cargaMenu != "vacio") {
                    clearTimeout(tgaSolution_admin_eva.cargaMenu);
                }
                tgaSolution_admin_eva.cargaMenu = setTimeout("contenidoSubInMenu(<?php echo $avance;?>);",500);
                </script>
                <?php
                exit;
            }
        }else{
            exit;
        }
    }
    public function cuestionarios(){
        $data["dbData"] = $this->load->database('evaluaciones', TRUE);
        $this->load->view('productos/administrador_evaluaciones/cuestionarios/index',$data);
    }
    public function p1(){
        $this->avance_cuestionario(1);
        $this->load->view('productos/administrador_evaluaciones/cuestionarios/p1');
    }
    public function p2(){
        $this->avance_cuestionario(2);

        $data["idInmobiliaria"]   = trim($this->input->post('idInmobiliaria'));
        $data["idProyecto"]       = trim($this->input->post('idProyecto'));
        $data["idTipoC"]          = trim($this->input->post('var1'));
        $data["idPais"]           = trim($this->input->post('var2'));
        $data["idEvaluacion"]     = trim($this->input->post('var3'));
        $data["idUser"]           = $_SESSION['idUser'];
        $this->load->view('productos/administrador_evaluaciones/cuestionarios/p2',$data);
    }
    public function p3(){
        $this->avance_cuestionario(3);

        $data["idInmobiliaria"]   = trim($this->input->post('idInmobiliaria'));
        $data["idProyecto"]       = trim($this->input->post('idProyecto'));
        $data["idTipoC"]          = trim($this->input->post('var1'));
        $data["idPais"]           = trim($this->input->post('var2'));
        $data["idEvaluacion"]     = trim($this->input->post('var3'));
        $data["idUser"]           = $_SESSION['idUser'];
        $this->load->view('productos/administrador_evaluaciones/cuestionarios/p3',$data);
    }
    public function p4(){
        $data["dbData"] = $this->load->database('evaluaciones', TRUE);
        $this->avance_cuestionario(4);

        $data["idInmobiliaria"]   = trim($this->input->post('idInmobiliaria'));
        $data["idProyecto"]       = trim($this->input->post('idProyecto'));
        $data["idTipoC"]          = trim($this->input->post('var1'));
        $data["idPais"]           = trim($this->input->post('var2'));
        $data["idEvaluacion"]     = trim($this->input->post('var3'));
        $data["idUser"]           = $_SESSION['idUser'];
        $this->load->view('productos/administrador_evaluaciones/cuestionarios/p4',$data);
    }
    public function p5(){
        $data["dbData"] = $this->load->database('evaluaciones', TRUE);
        $this->avance_cuestionario(5);
        $this->load->view('productos/administrador_evaluaciones/cuestionarios/p5');
    }
    public function p6(){
        $this->avance_cuestionario(6);

        $data["dbData"]           = $this->load->database('evaluaciones', TRUE);
        $data["idInmobiliaria"]   = trim($this->input->post('idInmobiliaria'));
        $data["idProyecto"]       = trim($this->input->post('idProyecto'));
        $data["idTipoC"]          = trim($this->input->post('var1'));
        $data["idPais"]           = trim($this->input->post('var2'));
        $data["idEvaluacion"]     = trim($this->input->post('var3'));
        $data["idUser"]           = $_SESSION['idUser'];
        $this->load->view('productos/administrador_evaluaciones/cuestionarios/p6',$data);
    }
    public function p7(){
        $this->avance_cuestionario(7);
        $this->load->view('productos/administrador_evaluaciones/cuestionarios/p7');
    }
    public function p8(){
        $this->avance_cuestionario(8);
        $this->load->view('productos/administrador_evaluaciones/cuestionarios/p8');
    }
    # FIN CUESTIONARIOS
    public function carga_empresa(){
        $idPais = trim($this->input->post('var1'));
        if (!is_numeric($idPais)) {exit;}

        $idempresa = array();
        $empresa   = array();

        $query = $this->db->query("SELECT idEmpresa, empresa
                                     FROM tga_empresas
                                    WHERE estado           = 1
                                      AND idTipoEmpresa    = 1
                                      AND idPais           = $idPais
                                 ORDER BY empresa ASC;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            for ($i=1;$i<=$query->num_rows();$i++) {
                $idempresa[$i] = $row->idEmpresa;
                $empresa[$i]   = $row->empresa;
                $row           = $query->next_row();
            }
        }else{
            exit;
        }

        ?>
        <script type="text/javascript">
        $(".tga-contenido .selectEmpresa .custom-select").html('<option value="0">Seleccione empresa</option>');
        <?php
        for ($i=1;$i<=count($idempresa);$i++) {
            echo '$(".tga-contenido .selectEmpresa .custom-select").append('."'".'<option value="'.$idempresa[$i].'">'.$empresa[$i].'</option>'."'".');'."\n";
        }
        ?>

        </script>
        <script type="text/javascript">
        $(".tga-contenido .selectEmpresa .custom-select").prop('disabled', false);
        </script>
        <?php
    }
    public function carga_proyecto(){
        $idEmpresa = trim($this->input->post('var1'));
        if (!is_numeric($idEmpresa)) {exit;}

        $idProyecto = array();
        $proyecto   = array();

        $query = $this->db->query("SELECT idProyecto, proyecto
                                     FROM tga_proyectos
                                    WHERE estado      = 1
                                      AND idEmpresa   = $idEmpresa
                                 ORDER BY proyecto ASC;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            for ($i=1;$i<=$query->num_rows();$i++) {
                $idProyecto[$i] = $row->idProyecto;
                $proyecto[$i]   = $row->proyecto;
                $row           = $query->next_row();
            }
        }else{
            exit;
        }

        ?>
        <script type="text/javascript">
        $(".tga-contenido .selectProyecto .custom-select").html('<option value="0">Seleccione proyecto</option>');
        <?php
        for ($i=1;$i<=count($idProyecto);$i++) {
            echo '$(".tga-contenido .selectProyecto .custom-select").append('."'".'<option value="'.$idProyecto[$i].'">'.$proyecto[$i].'</option>'."'".');'."\n";
        }
        ?>

        </script>
        <script type="text/javascript">
        $(".tga-contenido .selectProyecto .custom-select").prop('disabled', false);
        </script>
        <?php
    }
    public function carga_evaluacion(){
        $dbData = $this->load->database('evaluaciones', TRUE);

        $idTipoC       = trim($this->input->post('var1'));
        $idPais        = trim($this->input->post('var2'));
        $idEmpresa     = trim($this->input->post('var3'));
        $idProyecto    = trim($this->input->post('var4'));
        if (!is_numeric($idTipoC)) {exit;}
        if (!is_numeric($idPais)) {exit;}
        if (!is_numeric($idEmpresa)) {exit;}
        if (!is_numeric($idProyecto)) {exit;}

        $idEvaluacion = array();
        $evaluacion   = array();
echo "SELECT idEvaluacion, evaluacion
                                     FROM data_evaluaciones
                                    WHERE idInmobiliaria   = $idEmpresa
                                      AND idProyecto       = $idProyecto
                                      AND idTipoC          = $idTipoC
                                      AND estado           = 1
                                 ORDER BY evaluacion ASC;";
        $query = $dbData->query("SELECT idEvaluacion, evaluacion
                                     FROM data_evaluaciones
                                    WHERE idInmobiliaria   = $idEmpresa
                                      AND idProyecto       = $idProyecto
                                      AND idTipoC          = $idTipoC
                                      AND estado           = 1
                                 ORDER BY evaluacion ASC;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            for ($i=1;$i<=$query->num_rows();$i++) {
                $idEvaluacion[$i] = $row->idEvaluacion;
                $evaluacion[$i]   = $row->evaluacion;
                $row           = $query->next_row();
            }
        }else{
            exit;
        }

        ?>
        <script type="text/javascript">
        $(".tga-contenido .selectEvaluacion .custom-select").html('<option value="0">Seleccione evaluación</option>');
        <?php
        for ($i=1;$i<=count($idEvaluacion);$i++) {
            echo '$(".tga-contenido .selectEvaluacion .custom-select").append('."'".'<option value="'.$idEvaluacion[$i].'">'.$evaluacion[$i].'</option>'."'".');'."\n";
        }
        ?>

        </script>
        <script type="text/javascript">
        $(".tga-contenido .selectEvaluacion .custom-select").prop('disabled', false);
        </script>
        <?php
    }
    public function carga_bdd(){
        $dbData = $this->load->database('evaluaciones', TRUE);

        $idTipoC         = trim($this->input->post('var1'));
        $idPais          = trim($this->input->post('var2'));
        $idEmpresa       = trim($this->input->post('var3'));
        $idProyecto      = trim($this->input->post('var4'));
        $idEvaluacion    = trim($this->input->post('var5'));
        if (!is_numeric($idTipoC)) {exit;}
        if (!is_numeric($idPais)) {exit;}
        if (!is_numeric($idEmpresa)) {exit;}
        if (!is_numeric($idProyecto)) {exit;}
        if (!is_numeric($idEvaluacion)) {exit;}
        ?>
        <script type="text/javascript">
        $(".bddVer").prop('disabled', false);
        $(".bddSubida .custom-file-input").prop('disabled', false);
        </script>
        <?php
    }
    public function carga_cuestionario(){
        $dbData = $this->load->database('evaluaciones', TRUE);

        $idTipoC         = trim($this->input->post('var1'));
        $idPais          = trim($this->input->post('var2'));
        $idEmpresa       = trim($this->input->post('var3'));
        $idProyecto      = trim($this->input->post('var4'));
        $idEvaluacion    = trim($this->input->post('var5'));
        if (!is_numeric($idTipoC)) {exit;}
        if (!is_numeric($idPais)) {exit;}
        if (!is_numeric($idEmpresa)) {exit;}
        if (!is_numeric($idProyecto)) {exit;}
        if (!is_numeric($idEvaluacion)) {exit;}
        ?>
        <script type="text/javascript">
        $(".section-pasos").css("display","table");
        $("#contenidoSubIn").css("display","table");
        </script>
        <?php
    }
}

