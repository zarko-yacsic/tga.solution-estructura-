<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Administrar_evaluaciones_p4 extends CI_Controller {
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
    private function _sacar_total($idEvaluacion,$totalNo){
        $dbData           = $this->load->database('evaluaciones', TRUE);

        $total = 0;
        $query = $dbData->query("SELECT count(DISTINCT num) as total
                                   FROM data_estructura
                                  WHERE idEvaluacion   = $idEvaluacion
                                    AND numSubPregunta < 2;");
        if ($query->num_rows() > 0){
            $row   = $query->row();
            $total = $row->total;
        }

        if ($totalNo==0) {
            $query = $dbData->query("SELECT count(DISTINCT num) as total
                                       FROM data_estructura
                                      WHERE idEvaluacion   = $idEvaluacion
                                        AND idCategoria    = 0
                                        AND numSubPregunta < 2;");
            if ($query->num_rows() > 0){
                $row     = $query->row();
                $totalNo = $row->total;
            }
        }

        if ($totalNo==0) {
            $sql = " UPDATE data_evaluaciones
                        SET avance = 5
                      WHERE idEvaluacion     = $idEvaluacion;";
            $dbData->query($sql);
            ?>
            <script type="text/javascript">
            $('#next_step .next_btn').css('display', 'block');
            </script>
            <?php
        }

        ?>
        <script type="text/javascript">
        $(".categoria-p4.lado-a p").html("Preguntas sin categoría: <?php echo $totalNo;?> / <?php echo $total;?>");
        </script>
        <?php
    }
    public function lista_a(){
        $idInmobiliaria   = trim($this->input->post('idInmobiliaria'));
        $idProyecto       = trim($this->input->post('idProyecto'));
        $idTipoC          = trim($this->input->post('var1'));
        $idPais           = trim($this->input->post('var2'));
        $idEvaluacion     = trim($this->input->post('var3'));
        $idUser           = $_SESSION['idUser'];
        $dbData           = $this->load->database('evaluaciones', TRUE);

        $totalNo = 0;
        $query = $dbData->query("SELECT idEstructura
                                      , nomCampo
                                      , numpregunta
                                      , numSubPregunta
                                      , pregunta
                                      , idCategoria
                                   FROM data_estructura
                                  WHERE idEvaluacion   = $idEvaluacion
                                    AND idCategoria    = 0
                                    AND numSubPregunta < 2
                                GROUP BY num
                                ORDER BY idEstructura ASC;");
        if ($query->num_rows() > 0){
            $row = $query->row();

            $totalNo = $query->num_rows();
            for ($i=1;$i<=$query->num_rows();$i++) {
                $idEstructura     = $row->idEstructura;
                $nomCampo         = $row->nomCampo;
                $numpregunta      = $row->numpregunta;
                $numSubPregunta   = $row->numSubPregunta;
                $pregunta         = $row->pregunta;
                $idCategoria      = $row->idCategoria;

                echo ' <div draggable="true" ondragstart="drag(event)" class="draggable_item" id="drag_PREG_'.$idEstructura.'">
                            <div class="draggable_inner" id="draggable_inner_PREG_'.$idEstructura.'">
                                <div class="preguntaTxt" id="pregTxt_PREG_'.$idEstructura.'">
                                    <strong>'.$nomCampo.'</strong> : '.$pregunta.'
                                </div>
                            </div>
                            <input type="hidden" name="id_estructura_PREG[]" id="id_estructura_PREG_'.$idEstructura.'" value="'.$idEstructura.'" data-tipo_hf="id_estructura">
                            <input type="hidden" name="num_pregunta_PREG[]" id="num_pregunta_PREG_'.$idEstructura.'" value="'.$numpregunta.'" data-tipo_hf="num_pregunta">
                            <input type="hidden" name="is_subpregunta_PREG[]" id="is_subpregunta_PREG_'.$idEstructura.'" value="'.$numSubPregunta.'" data-tipo_hf="subpregunta">
                        </div>';
                $row = $query->next_row();
            }
        }

        $this->_sacar_total($idEvaluacion,$totalNo);
    }
    public function lista_b(){
        $idInmobiliaria   = trim($this->input->post('idInmobiliaria'));
        $idProyecto       = trim($this->input->post('idProyecto'));
        $idTipoC          = trim($this->input->post('var1'));
        $idPais           = trim($this->input->post('var2'));
        $idEvaluacion     = trim($this->input->post('var3'));
        $idCategoria      = trim($this->input->post('var4'));
        $idUser           = $_SESSION['idUser'];
        $dbData           = $this->load->database('evaluaciones', TRUE);

        $totalNo = 0;
        $query = $dbData->query("SELECT idEstructura
                                      , nomCampo
                                      , numpregunta
                                      , numSubPregunta
                                      , pregunta
                                      , idCategoria
                                   FROM data_estructura
                                  WHERE idEvaluacion   = $idEvaluacion
                                    AND idCategoria    > 0
                                    AND idCategoria    = $idCategoria
                                    AND numSubPregunta < 2
                                GROUP BY num
                                ORDER BY idEstructura ASC;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            for ($i=1;$i<=$query->num_rows();$i++) {
                $idEstructura     = $row->idEstructura;
                $nomCampo         = $row->nomCampo;
                $numpregunta      = $row->numpregunta;
                $numSubPregunta   = $row->numSubPregunta;
                $pregunta         = $row->pregunta;
                $idCategoria      = $row->idCategoria;

                echo ' <div draggable="true" ondragstart="drag(event)" class="draggable_item" id="drag_PCAT_'.$idEstructura.'">
                            <div class="draggable_inner" id="draggable_inner_PCAT_'.$idEstructura.'">
                                <div class="preguntaTxt" id="pregTxt_PCAT_'.$idEstructura.'">
                                    <strong>'.$nomCampo.'</strong> : '.$pregunta.'
                                </div>
                            </div>
                            <input type="hidden" name="id_estructura_PCAT[]" id="id_estructura_PCAT_'.$idEstructura.'" value="'.$idEstructura.'" data-tipo_hf="id_estructura">
                            <input type="hidden" name="num_pregunta_PCAT[]" id="num_pregunta_PCAT_'.$idEstructura.'" value="'.$numpregunta.'" data-tipo_hf="num_pregunta">
                            <input type="hidden" name="is_subpregunta_PCAT[]" id="is_subpregunta_PCAT_'.$idEstructura.'" value="'.$numSubPregunta.'" data-tipo_hf="subpregunta">
                        </div>';
                $row = $query->next_row();
            }
        }
        $this->_sacar_total($idEvaluacion,$totalNo);
    }
    public function guardar_preguntas_categorias(){
        $error = false; $conteo = 0;
        $id_evaluacion = trim($_POST['hf_id_evaluacion']);
        $id_categoria = trim($_POST['sel_id_categoria']);
        $sql = "UPDATE data_estructura SET idCategoria=0 WHERE idCategoria=" . $id_categoria . " AND idEvaluacion=" . $id_evaluacion . ";";
        $result = $this->db->query($sql);
        if(isset($_POST['id_estructura_PCAT'])){
            $arr_id_estructura = $_POST['id_estructura_PCAT'];
            $arr_num_pregunta = $_POST['num_pregunta_PCAT'];
            $arr_is_subpregunta = $_POST['is_subpregunta_PCAT'];
            if(count($arr_id_estructura) > 0){
                $sql = "UPDATE data_estructura SET idCategoria=" . $id_categoria . " WHERE ";
                for($i = 0; $i < count($arr_id_estructura); $i++){
                    if($arr_is_subpregunta[$i] == 0){
                        $sql .= "idEstructura=" . $arr_id_estructura[$i] . " OR ";
                    }
                    if($arr_is_subpregunta[$i] == 1){
                        $sql .= "numpregunta=" . $arr_num_pregunta[$i] . " OR ";
                    }
                }
                $sql = substr($sql, 0, -4);
                $sql .= " AND idEvaluacion=" . $id_evaluacion . ";";
                $result = $this->db->query($sql);
                if(!$result){ $error = true;}
                $sql = "SELECT COUNT(idEstructura) AS conteo FROM data_estructura WHERE idCategoria=" . $id_categoria . " AND idEvaluacion=" . $id_evaluacion . ";";
                $result = $this->db->query($sql);
                $row = $result->row();
                $conteo = $row->conteo;
            }
        }
        $this->_sacar_total($id_evaluacion,0);
    }
    public function listar_preguntas(){
        $error            = false;
        $id_evaluacion    = trim($this->input->post('id_evaluacion'));
        $id_categoria     = trim($this->input->post('id_categoria'));

        if(is_numeric($id_evaluacion) && is_numeric($id_categoria)){
            $sql = "SELECT DISTINCT idEstructura AS id_preguntaE
                         , nomCampo
                         , numpregunta
                         , numSubPregunta
                         , pregunta
                         , idCategoria
                      FROM data_estructura
                     WHERE idEvaluacion   = $id_evaluacion
                       AND idCategoria    = $id_categoria
                       AND numSubPregunta < 2
                  GROUP BY num
                  ORDER BY num ASC;";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            if(!$result){ $error = true;}
        }
        else{ $error = true;}
        if($error == false){
            $mensaje = 'Se han obtenido correctamente las preguntas en la categoría seleccionada.';
            $data = array('status' => 'SUCCESS', 'titulo' => 'Administrar evaluaciones', 'mensaje' => $mensaje, 'data_evaluacion' => $result);
        }
        if($error == true){
            $mensaje = 'Error al obtener las preguntas en la categoría seleccionada.';
            $data = array('status' => 'ERROR', 'titulo' => 'Administrar evaluaciones', 'mensaje' => $mensaje);
        }
        $output = json_encode($data);
        echo $output;
    }
    public function preguntas_sin_categoria(){
        $id_evaluacion = trim($_GET['id_evaluacion']);
        $total_preguntas = $this->obtener_total_preguntas($id_evaluacion);
        $total_sin_categorizar = 0;

        // Obtener total preguntas no categorizadas...
        $sql = "SELECT DISTINCT idEstructura AS id_preguntaE FROM data_estructura WHERE idEvaluacion=" . $id_evaluacion . " AND idCategoria=0 GROUP BY pregunta;";
        $result2 = $this->db->query($sql);
        if($result2){
            $total_sin_categorizar = $result2->num_rows();
        }
        $output = json_encode(array('status' => 'SUCCESS', 'total_preguntas' => $total_preguntas, 'total_preguntas_sc' => $total_sin_categorizar));
        echo $output;
    }



    public function obtener_total_preguntas(){
        $id_evaluacion = trim($_GET['id_evaluacion']);
        $total_preguntas = 0;
        $sql = "SELECT DISTINCT idEstructura AS id_preguntaE FROM data_estructura WHERE idEvaluacion=" . $id_evaluacion . " GROUP BY pregunta;";
        $result = $this->db->query($sql);
        if($result){
            $total_preguntas = $result->num_rows();
        }
        return $total_preguntas;
    }



}
