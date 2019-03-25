<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Preguntas extends CI_Controller {
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
        $this->load->model('Admin_eva');
        $this->Tgasolutions->usuario();
        $this->Tgasolutions->permisos();
    }
    public function nueva_pregunta(){
        $data['idTipoC']          = trim($this->input->post('var1'));
        $data['idCategoria']     = trim($this->input->post('var2'));
        $data['dbData']           = $this->load->database('evaluaciones', TRUE);

        $this->load->view('productos/administrador_evaluaciones/preguntas/nuevo',$data);
    }
    public function nueva_subpregunta(){
        $data['idTipoC']          = trim($this->input->post('var1'));
        $data['idCategoria']     = trim($this->input->post('var2'));
        $data['idPregunta']      = trim($this->input->post('var3'));
        $data['idUser']           = $_SESSION['idUser'];
        $data['dbData']           = $this->load->database('evaluaciones', TRUE);

        $this->load->view('productos/administrador_evaluaciones/preguntas/nuevo_item.php', $data);
    }
    public function ficha(){
        $data['idTipoC']          = trim($this->input->post('var1'));
        $data['idCategoria']      = trim($this->input->post('var2'));
        $data['idPregunta']       = trim($this->input->post('var3'));
        $data['tipo']             = trim($this->input->post('var4'));
        $data['idUser']           = $_SESSION['idUser'];
        $data['dbData']           = $this->load->database('evaluaciones', TRUE);

        $this->load->view('productos/administrador_evaluaciones/preguntas/ficha', $data);
    }
    public function cargaCategoria(){
        $dbData           = $this->load->database('evaluaciones', TRUE);
        $idTipoC          = trim($this->input->post('var1'));
        if (!is_numeric($idTipoC)) {exit;}

        $opciones = '<option value="0">Seleccione</option>';
        $query = $dbData->query("SELECT idCategoria, categoria
                                   FROM data_categoria
                                  WHERE idTipoC = $idTipoC
                                    AND estado  = 1
                               ORDER BY categoria ASC;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            for ($i=1;$i<=$query->num_rows();$i++) {
                $opciones = $opciones.'<option value="'.$row->idCategoria.'">'.$row->categoria.'</option>';
                $row = $query->next_row();
            }
            ?>
            <script type="text/javascript">
            $(".tga-filtro .tga-box-2 select").html('<?php echo $opciones;?>');
            $(".tga-filtro .tga-box-2 select").prop('disabled', false);
            </script>
            <?php
        }
    }
    public function lista(){
        $data['dbData']               = $this->load->database('evaluaciones', TRUE);
        $data['idTipoC']              = trim($this->input->post('var1'));
        $data['idCategoria']          = trim($this->input->post('var2'));
        $this->load->view('productos/administrador_evaluaciones/preguntas/lista', $data);
    }
    public function crear_pregunta(){
        $idTipoC       = trim($this->input->post('idTipoC'));
        $idCategoria   = trim($this->input->post('idCategoria'));
        $tipo          = trim($this->input->post('tipo'));
        $pregunta      = trim($this->input->post('pregunta'));
        if (!is_numeric($idTipoC)) {exit;}
        if (!is_numeric($idCategoria)) {exit;}

        if (strlen($pregunta)<3) {
            $this->Tgasolutions->mensaje(3,"Pregunta sin texto","Tiene que ingresar el texto a la pregunta");
            exit;
        }

        $validar = $this->Tgasolutions->valida_texto($pregunta,"-_¡!¿?=.,[]()1234567890 abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ");
        if ($validar[0]==0) {
            $this->Tgasolutions->mensaje(3,"Pregunta mal escrita","La pregunta solo acepta los siguientes caracteres: a-Z 0-9 .,-[] ()¡!¿?=<br>Caracter invalido [".$validar[1]."]");
            exit;
        }

        if (!is_numeric($tipo)) {
            $this->Tgasolutions->mensaje(3,"¿Que tipo de pregunta es?","La pregunta es padre o normal, puede seleccionar una opción");
            exit;
        }

        if ($tipo!=2) {$tipo = 1;}

        $dbData = $this->load->database('evaluaciones', TRUE);
        $query = $dbData->query("SELECT 1
                                   FROM data_pregunta
                                  WHERE idTipoC        = $idTipoC
                                    AND idCategoria    = $idCategoria
                                    AND titulo         = '$pregunta'
                                    AND idPadre        = 0;");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"La pregunta ya existe","Lo sentimos la pregunta ingresada ya existe.");
            exit;
        }

        $sql = "INSERT INTO data_pregunta (idTipoC
                                         , idCategoria
                                         , titulo
                                         , tipo)
                                           VALUES ($idTipoC
                                                 , $idCategoria
                                                 , '$pregunta'
                                                 , $tipo);";
        if ($dbData->query($sql)===true) {
            $this->Tgasolutions->mensaje(0,"Pregunta creada","La pregunta fue creada correctamente");
            ?>
            <script type="text/javascript">
            setTimeout("cargaContenido();",1200);
            $('#tgaSleModal2').modal('toggle');
            </script>
            <?php
            exit;
        }else{
            $this->Tgasolutions->mensaje(3,"ERROR","Ocurrio un problema no se pudo guardar");
            exit;
        }
    }
    public function crear_subpregunta(){
        $idTipoC       = trim($this->input->post('idTipoC'));
        $idCategoria   = trim($this->input->post('idCategoria'));
        $idPregunta    = trim($this->input->post('idPregunta'));
        $pregunta      = trim($this->input->post('pregunta'));
        if (!is_numeric($idTipoC)) {exit;}
        if (!is_numeric($idCategoria)) {exit;}
        if (!is_numeric($idPregunta)) {exit;}

        if (strlen($pregunta)<3) {
            $this->Tgasolutions->mensaje(3,"Item sin texto","Tiene que ingresar el texto a la pregunta");
            exit;
        }

        $validar = $this->Tgasolutions->valida_texto($pregunta,"-_¡!¿?=.,[]()1234567890 abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ");
        if ($validar[0]==0) {
            $this->Tgasolutions->mensaje(3,"Item mal escrita","La pregunta solo acepta los siguientes caracteres: a-Z 0-9 .,-[] ()¡!¿?=<br>Caracter invalido [".$validar[1]."]");
            exit;
        }

        $dbData = $this->load->database('evaluaciones', TRUE);
        $query = $dbData->query("SELECT 1
                                   FROM data_pregunta
                                  WHERE idTipoC        = $idTipoC
                                    AND idCategoria    = $idCategoria
                                    AND titulo         = '$pregunta'
                                    AND idPadre        = $idPregunta;");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"El item ya existe","Lo sentimos el item ingresado ya existe.");
            exit;
        }

        $sql = "INSERT INTO data_pregunta (idTipoC
                                         , idCategoria
                                         , titulo
                                         , idPadre
                                         , tipo)
                                           VALUES ($idTipoC
                                                 , $idCategoria
                                                 , '$pregunta'
                                                 , $idPregunta
                                                 , 3);";
        if ($dbData->query($sql)===true) {
            $this->Tgasolutions->mensaje(0,"Item creado","el item fue creada correctamente");
            ?>
            <script type="text/javascript">
            setTimeout("cargaContenido();",1200);
            $('#tgaSleModal2').modal('toggle');
            </script>
            <?php
            exit;
        }else{
            $this->Tgasolutions->mensaje(3,"ERROR","Ocurrio un problema no se pudo guardar");
            exit;
        }

    }
    public function editar_ficha(){
        $idTipoC               = trim($this->input->post('idTipoC'));
        $idCategoria           = trim($this->input->post('idCategoria'));
        $idPregunta            = trim($this->input->post('idPregunta'));
        $tipo                  = trim($this->input->post('tipo'));
        $pregunta              = trim($this->input->post('pregunta'));
        $idPadre               = trim($this->input->post('idPadre'));
        $estado                = trim($this->input->post('estado'));
        $idPreguntaDisenio     = trim($this->input->post('idPreguntaDisenio'));

        if (!is_numeric($idTipoC)) {exit;}
        if (!is_numeric($idCategoria)) {exit;}
        if (!is_numeric($idPregunta)) {exit;}
        if (!is_numeric($tipo)) {exit;}
        if (!is_numeric($idPadre)) {exit;}
        if (!is_numeric($idPreguntaDisenio)) {exit;}

        $estado = (!is_numeric($estado)) ? 0 : $estado;

        if (strlen($pregunta)<3) {
            $this->Tgasolutions->mensaje(3,"Pregunta sin texto","Tiene que ingresar el texto a la pregunta");
            exit;
        }

        $validar = $this->Tgasolutions->valida_texto($pregunta,"-_¡!¿?=.,[]()1234567890 abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ");
        if ($validar[0]==0) {
            $this->Tgasolutions->mensaje(3,"Pregunta mal escrita","La pregunta solo acepta los siguientes caracteres: a-Z 0-9 .,-[] ()¡!¿?=<br>Caracter invalido [".$validar[1]."]");
            exit;
        }

        $dbData = $this->load->database('evaluaciones', TRUE);

        if ($tipo<3) {
            $query = $dbData->query("SELECT 1
                                       FROM data_pregunta
                                      WHERE idTipoC        = $idTipoC
                                        AND idCategoria    = $idCategoria
                                        AND titulo         = '$pregunta'
                                        AND idPadre        = 0
                                        AND idPregunta    != $idPregunta;");
            if ($query->num_rows() > 0){
                $this->Tgasolutions->mensaje(3,"La pregunta ya existe","Lo sentimos la pregunta ingresada ya existe.");
                exit;
            }
        }else{
            $query = $dbData->query("SELECT 1
                                       FROM data_pregunta
                                      WHERE idTipoC        = $idTipoC
                                        AND idCategoria    = $idCategoria
                                        AND titulo         = '$pregunta'
                                        AND idPadre        = $idPadre
                                        AND idPregunta    != $idPregunta;");
            if ($query->num_rows() > 0){
                $this->Tgasolutions->mensaje(3,"El item ya existe","Lo sentimos el item ingresado ya existe.");
                exit;
            }
        }

        $query = $dbData->query("SELECT codigo
                                   FROM data_pregunta
                                  WHERE idTipoC       = $idTipoC
                                    AND idCategoria   = $idCategoria
                                    AND idPregunta    = $idPregunta;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            $codigo               = $row->codigo;
        }

        $codigoIn            = '';
        $numIn               = '';
        $codigoNumIn         = '';
        $codigoNumSubIn      = '';
        $codigoTablaIn       = '';

        if ($codigo=='' && $idPreguntaDisenio > 0 && $estado == 1) {

            # Sacar pregunta Numero
            $query = $dbData->query("SELECT IF(codigoNum IS NULL,1,SUM(codigoNum+1)) as codigoN
                                          , IF(num IS NULL,1,SUM(num+1)) as num
                                       FROM (SELECT MAX(codigoNum) as codigoNum, MAX(num) as num
                                               FROM data_pregunta
                                              WHERE idTipoC       = $idTipoC
                                                AND idCategoria   = $idCategoria) x");
            if ($query->num_rows() > 0){
                $row       = $query->row();
                $codigoN   = $row->codigoN;
                $num       = $row->num;
            }else{
                exit;
            }

            # Si es hijo
            if ($tipo==3) {
                $query = $dbData->query("SELECT codigoNum
                                           FROM data_pregunta
                                          WHERE idTipoC       = $idTipoC
                                            AND idPregunta    = $idPadre;");
                if ($query->num_rows() > 0){
                    $row          = $query->row();
                    $codigoN      = $row->codigoNum;
                }else{
                    exit;
                }

                $query = $dbData->query("SELECT IF(codigoNumSub IS NULL,1,SUM(codigoNumSub+1)) as codigoN
                                           FROM (SELECT MAX(codigoNumSub) as codigoNumSub
                                                   FROM data_pregunta
                                                  WHERE idTipoC       = $idTipoC
                                                    AND idCategoria   = $idCategoria
                                                    AND idPadre       = $idPadre) x;");
                if ($query->num_rows() > 0){
                    $row          = $query->row();
                    $codigoNSub   = $row->codigoN;
                }else{
                    exit;
                }
                # -------------
            }

            $query = $dbData->query("SELECT sigla
                                       FROM data_categoria
                                      WHERE idTipoC       = $idTipoC
                                        AND idCategoria   = $idCategoria;");
            if ($query->num_rows() > 0){
                $row       = $query->row();
                $sigla     = $row->sigla;
            }else{
                exit;
            }

            if ($tipo<3) {
                $codigoIn       = ", codigo         = '$sigla$codigoN'";
                $codigoNumIn    = ', codigoNum      = '.$codigoN;
            }else{
                $codigoIn       = ", codigo         = '$sigla$codigoN".'_'."$codigoNSub'";
                $codigoNumIn    = ', codigoNum      = '.$codigoN;
                $codigoNumSubIn = ', codigoNumSub   = '.$codigoNSub;
            }

            $numIn          = ', num         = '.$num;
            $codigoTablaIn  = ", codigoTabla = ".ceil($num/40);
            # ----------------------------
        }

        if ($idPreguntaDisenio==0) {
            $estado         = 0;
        }

        $sql = "UPDATE data_pregunta
                   SET titulo             = '$pregunta'
                     , idPreguntaDisenio  = $idPreguntaDisenio
                     , estado             = $estado
                     $codigoIn
                     $numIn
                     $codigoNumIn
                     $codigoNumSubIn
                     $codigoTablaIn
                 WHERE idPregunta   = $idPregunta
                   AND idTipoC      = $idTipoC
                   AND idCategoria  = $idCategoria
                   AND idPadre      = $idPadre;";
        if ($dbData->query($sql)===true) {
            $this->Tgasolutions->mensaje(0,"Ficha actualizada","La ficha fue actualizada correctamente");
            ?>
            <script type="text/javascript">
            setTimeout("cargaContenido();",1200);
            $('#tgaSleModal2').modal('toggle');
            </script>
            <?php
            exit;
        }else{
            $this->Tgasolutions->mensaje(3,"ERROR","Ocurrio un problema no se pudo guardar");
            exit;
        }
    }
    public function lista_respuestas(){
        $dbData                  = $this->load->database('evaluaciones', TRUE);
        $idRespuestaEstructura   = trim($this->input->post('idTipoC'));
        if (!is_numeric($idRespuestaEstructura)) {exit;}
        $this->Tgasolutions->lista_respuesta($dbData,$idRespuestaEstructura);
    }
}
