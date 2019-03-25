<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Disenio_preguntas extends CI_Controller {
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
        $this->load->model('Admin_eva');
        $this->Tgasolutions->usuario();
        $this->Tgasolutions->permisos();
    }
    public function index(){

    }
    public function categoria(){
        $dbData    = $this->load->database('evaluaciones', TRUE);
        $idTipoC   = trim($this->input->post('var1'));
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
            $(".estructura_respuestas-1 .tga-box-2 select").html('<?php echo $opciones;?>');
            $(".estructura_respuestas-1 .tga-box-2 select").prop('disabled', false);
            </script>
            <?php
        }
    }
    public function contenido(){
        $data['dbData']        = $this->load->database('evaluaciones', TRUE);
        $data['idTipoC']       = trim($this->input->post('var1'));
        $data['idCategoria']   = trim($this->input->post('var2'));
        if (!is_numeric($data['idTipoC'])) {exit;}
        if (!is_numeric($data['idCategoria'])) {exit;}
        ?>
        <script type="text/javascript">
        $(".estructura_respuestas-1 .btn-mas").prop('disabled', false);
        </script>
        <?php
        $this->load->view('productos/administrador_evaluaciones/disenio_preguntas/lista',$data);
    }
    public function nueva_pregunta(){
        $data['dbData']        = $this->load->database('evaluaciones', TRUE);
        $data['idTipoC']       = trim($this->input->post('var1'));
        $data['idCategoria']   = trim($this->input->post('var2'));
        if (!is_numeric($data['idTipoC'])) {exit;}
        if (!is_numeric($data['idCategoria'])) {exit;}


        $this->load->view('productos/administrador_evaluaciones/disenio_preguntas/nueva_pregunta',$data);
    }
    public function code_pregunta(){
        $idTipoC       = trim($this->input->post('idTipoC'));
        $idCategoria   = trim($this->input->post('idCategoria'));
        $tipo          = trim($this->input->post('tipo'));
        $pregunta      = trim($this->input->post('pregunta'));
        if (!is_numeric($idTipoC)) {exit;}
        if (!is_numeric($idCategoria)) {exit;}

        if (!is_numeric($tipo)) {
            $this->Tgasolutions->mensaje(3,"¿Que tipo de pregunta es?","La pregunta es padre o normal, puede seleccionar una opción");
            exit;
        }


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
        $query = $dbData->query("SELECT 1
                                   FROM data_pregunta_disenio
                                  WHERE idTipoC        = $idTipoC
                                    AND idCategoria    = $idCategoria
                                    AND titulo         = '$pregunta';");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"La pregunta ya existe","Lo sentimos la pregunta ingresada ya existe.");
            exit;
        }

        $sql = "INSERT INTO data_pregunta_disenio (idTipoC,idCategoria,tipo,idPadre,titulo)
                                           VALUES ($idTipoC,$idCategoria,$tipo,0,'$pregunta');";
        if ($dbData->query($sql)===true) {
            $this->Tgasolutions->mensaje(0,"Pregunta creada","La pregunta fue creada correctamente");
            ?>
            <script type="text/javascript">
            setTimeout("cargarContenido();",1200);
            $('#tgaSleModal2').modal('toggle');

            </script>
            <?php
            exit;
        }else{
            $this->Tgasolutions->mensaje(3,"ERROR","Ocurrio un problema no se pudo guardar");
            exit;
        }
    }
    public function nuevo_item(){
        $data['dbData']              = $this->load->database('evaluaciones', TRUE);
        $data['idTipoC']             = trim($this->input->post('var1'));
        $data['idCategoria']         = trim($this->input->post('var2'));
        $data['idPreguntaDisenio']   = trim($this->input->post('var3'));
        if (!is_numeric($data['idTipoC'])) {exit;}
        if (!is_numeric($data['idCategoria'])) {exit;}
        if (!is_numeric($data['idPreguntaDisenio'])) {exit;}


        $this->load->view('productos/administrador_evaluaciones/disenio_preguntas/nuevo_item',$data);
    }
    public function code_item(){
        $idTipoC                = trim($this->input->post('idTipoC'));
        $idCategoria            = trim($this->input->post('idCategoria'));
        $item                   = trim($this->input->post('item'));
        $idPreguntaDisenio      = trim($this->input->post('idPreguntaDisenio'));
        if (!is_numeric($idTipoC)) {exit;}
        if (!is_numeric($idCategoria)) {exit;}
        if (!is_numeric($idPreguntaDisenio)) {exit;}


        if (strlen($item)<3) {
            $this->Tgasolutions->mensaje(3,"Pregunta sin texto","Tiene que ingresar el texto a la pregunta");
            exit;
        }

        $validar = $this->Tgasolutions->valida_texto($item,"-_¡!¿?=.,[]()1234567890 abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ");
        if ($validar[0]==0) {
            $this->Tgasolutions->mensaje(3,"El item esta mal escrito","El item solo acepta los siguientes caracteres: a-Z 0-9 .,-[] ()¡!¿?=<br>Caracter invalido [".$validar[1]."]");
            exit;
        }

        $dbData = $this->load->database('evaluaciones', TRUE);
        $query = $dbData->query("SELECT 1
                                   FROM data_pregunta_disenio
                                  WHERE idTipoC        = $idTipoC
                                    AND idCategoria    = $idCategoria
                                    AND titulo         = '$item'
                                    AND idPadre        = $idPreguntaDisenio;");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"El item ya existe","Lo sentimos el item ingresado ya existe.");
            exit;
        }

        $sql = "INSERT INTO data_pregunta_disenio (idTipoC,idCategoria,tipo,idPadre,titulo)
                                           VALUES ($idTipoC,$idCategoria,3,$idPreguntaDisenio,'$item');";
        if ($dbData->query($sql)===true) {
            $this->Tgasolutions->mensaje(0,"Item creado","El item fue creado correctamente");
            ?>
            <script type="text/javascript">
            setTimeout("cargarContenido();",1200);
            $('#tgaSleModal2').modal('toggle');

            </script>
            <?php
            exit;
        }else{
            $this->Tgasolutions->mensaje(3,"ERROR","Ocurrio un problema no se pudo guardar");
            exit;
        }
    }
    public function editar(){
        $data['dbData']              = $this->load->database('evaluaciones', TRUE);
        $data['idTipoC']             = trim($this->input->post('var1'));
        $data['idCategoria']         = trim($this->input->post('var2'));
        $data['idPreguntaDisenio']   = trim($this->input->post('var3'));
        if (!is_numeric($data['idTipoC'])) {exit;}
        if (!is_numeric($data['idCategoria'])) {exit;}
        if (!is_numeric($data['idPreguntaDisenio'])) {exit;}


        $this->load->view('productos/administrador_evaluaciones/disenio_preguntas/editar',$data);
    }
    public function code_editar(){
        $dbData                 = $this->load->database('evaluaciones', TRUE);
        $idTipoC                = trim($this->input->post('idTipoC'));
        $idCategoria            = trim($this->input->post('idCategoria'));
        $idPreguntaDisenio      = trim($this->input->post('idPreguntaDisenio'));
        $nombre                 = trim($this->input->post('nombre'));
        $idRespuestaEstructura  = trim($this->input->post('idRespuestaEstructura'));
        $estado                 = trim($this->input->post('estado'));
        $tipo                   = trim($this->input->post('tipo'));
        if (!is_numeric($idTipoC)) {exit;}
        if (!is_numeric($idCategoria)) {exit;}
        if (!is_numeric($idPreguntaDisenio)) {exit;}
        if (!is_numeric($idRespuestaEstructura) && $tipo!=2) {exit;}
        if (!is_numeric($estado)) {exit;}

        if (strlen($nombre)<3) {
            $this->Tgasolutions->mensaje(3,"Tiene que ingresar un texto","Tiene que ingresar el texto");
            exit;
        }

        if ($idRespuestaEstructura<1 && $tipo!=2) {
            $this->Tgasolutions->mensaje(3,"Tipo de respuesta","Tiene que seleccionar un tipo de respuesta");
            exit;
        }
        if ($tipo==2) {
            $idRespuestaEstructura = 0;
        }


        $validar = $this->Tgasolutions->valida_texto($nombre,"-_¡!¿?=.,[]()1234567890 abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ");
        if ($validar[0]==0) {
            $this->Tgasolutions->mensaje(3,"El texto esta mal escrito","El texto solo acepta los siguientes caracteres: a-Z 0-9 .,-[] ()¡!¿?=<br>Caracter invalido [".$validar[1]."]");
            exit;
        }

        if ($tipo<3) {
            $query = $dbData->query("SELECT 1
                                       FROM data_pregunta_disenio
                                      WHERE idTipoC            = $idTipoC
                                        AND idCategoria        = $idCategoria
                                        AND titulo             = '$nombre'
                                        AND idPreguntaDisenio != $idPreguntaDisenio;");
            if ($query->num_rows() > 0){
                $this->Tgasolutions->mensaje(3,"La pregunta ya existe","Lo sentimos la pregunta ingresada ya existe.");
                exit;
            }
        }else{
            $query = $dbData->query("SELECT 1
                                       FROM data_pregunta_disenio
                                      WHERE idTipoC            = $idTipoC
                                        AND idCategoria        = $idCategoria
                                        AND titulo             = '$nombre'
                                        AND idPadre            = $idPreguntaDisenio
                                        AND idPreguntaDisenio != $idPreguntaDisenio;");
            if ($query->num_rows() > 0){
                $this->Tgasolutions->mensaje(3,"El item ya existe","Lo sentimos el item ingresado ya existe.");
                exit;
            }
        }

        $sql = "UPDATE data_pregunta_disenio
                   SET titulo                = '$nombre'
                     , idRespuestaEstructura = $idRespuestaEstructura
                     , estado                = $estado
                 WHERE idTipoC               = $idTipoC
                   AND idCategoria           = $idCategoria
                   AND idPreguntaDisenio     = $idPreguntaDisenio;";
        if ($dbData->query($sql)===true) {
            $this->Tgasolutions->mensaje(0,"Actualización realizada","La actualización fue realizada con exito");
            ?>
            <script type="text/javascript">
            setTimeout("cargarContenido();",1200);
            $('#tgaSleModal2').modal('toggle');
            </script>
            <?php
            exit;
        }else{
            $this->Tgasolutions->mensaje(3,"ERROR","Ocurrio un problema no se pudo guardar");
            exit;
        }



    }
}
